<?php

namespace App\Filament\Pages;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentStatus;
use App\Models\Expense;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid as SchemaGrid;

class FinanceOverview extends Page
{
    protected static string | \BackedEnum | null $navigationIcon  = 'heroicon-o-chart-bar';
    protected static string | \UnitEnum | null  $navigationGroup = 'Finance';
    protected static ?int                       $navigationSort  = 1;
    protected static ?string                    $navigationLabel = 'Finance Overview';
    protected static ?string                    $title           = 'Finance Overview';

    protected string $view = 'filament.pages.finance-overview';

    /** Active period tab: 1 | 3 | 6 | 12 */
    public int $period = 1;

    public function setPeriod(int $months): void
    {
        $this->period = $months;
    }

    // ── KPI helpers ───────────────────────────────────────────────────────────

    private function periodStart(): Carbon
    {
        return now()->startOfMonth()->subMonths($this->period - 1);
    }

    public function getRevenue(): int
    {
        return (int) Order::where('payment_status', PaymentStatus::Paid)
            ->where('created_at', '>=', $this->periodStart())
            ->sum('total_pkr');
    }

    public function getExpenses(): int
    {
        return (int) Expense::where('expense_date', '>=', $this->periodStart())->sum('amount_pkr');
    }

    public function getNetProfit(): int
    {
        return $this->getRevenue() - $this->getExpenses();
    }

    public function getOrderCount(): int
    {
        return Order::where('payment_status', PaymentStatus::Paid)
            ->where('created_at', '>=', $this->periodStart())
            ->count();
    }

    // ── Monthly chart data (last 12 months always shown in chart) ─────────────

    public function getChartData(): array
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->startOfMonth()->subMonths($i));
        }

        $labels   = [];
        $revenue  = [];
        $expenses = [];

        foreach ($months as $month) {
            $labels[] = $month->format('M y');

            $revenue[] = (int) Order::where('payment_status', PaymentStatus::Paid)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_pkr');

            $expenses[] = (int) Expense::whereYear('expense_date', $month->year)
                ->whereMonth('expense_date', $month->month)
                ->sum('amount_pkr');
        }

        return compact('labels', 'revenue', 'expenses');
    }

    // ── Expense breakdown by category ─────────────────────────────────────────

    public function getCategoryBreakdown(): array
    {
        $rows = Expense::selectRaw('category, SUM(amount_pkr) as total')
            ->where('expense_date', '>=', $this->periodStart())
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalExpenses = $this->getExpenses() ?: 1;

        return $rows->map(function ($row) use ($totalExpenses) {
            $cat = ExpenseCategory::from($row->category);
            return [
                'label'   => $cat->label(),
                'color'   => $cat->color(),
                'amount'  => (int) $row->total,
                'percent' => round(($row->total / $totalExpenses) * 100),
            ];
        })->toArray();
    }

    // ── All-periods data for JS tab switching (no round-trip) ────────────────

    public function getAllPeriodsData(): array
    {
        $original = $this->period;
        $result   = [];
        foreach ([1, 3, 6, 12] as $months) {
            $this->period = $months;
            $revenue      = $this->getRevenue();
            $expenses     = $this->getExpenses();
            $net          = $revenue - $expenses;
            $orderCount   = $this->getOrderCount();
            $result[$months] = [
                'revenue'    => $revenue,
                'expenses'   => $expenses,
                'net'        => $net,
                'orderCount' => $orderCount,
                'profitable' => $net >= 0,
                'margin'     => $revenue > 0 ? round(($net / $revenue) * 100) : null,
                'avgOrder'   => $orderCount > 0 ? intdiv($revenue, $orderCount) : null,
                'breakdown'  => $this->getCategoryBreakdown(),
                'chart'      => $this->getChartDataForPeriod($months),
            ];
        }
        $this->period = $original;
        return $result;
    }

    private function getChartDataForPeriod(int $months): array
    {
        if ($months === 1) {
            return $this->getChartDataDaily();
        }

        $labels   = [];
        $revenue  = [];
        $expenses = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month      = now()->startOfMonth()->subMonths($i);
            $labels[]   = $month->format('M y');
            $revenue[]  = (int) Order::where('payment_status', PaymentStatus::Paid)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_pkr');
            $expenses[] = (int) Expense::whereYear('expense_date', $month->year)
                ->whereMonth('expense_date', $month->month)
                ->sum('amount_pkr');
        }
        return compact('labels', 'revenue', 'expenses');
    }

    private function getChartDataDaily(): array
    {
        $labels   = [];
        $revenue  = [];
        $expenses = [];
        $today    = now()->day;

        for ($day = 1; $day <= $today; $day++) {
            $labels[]   = $day . ' ' . now()->format('M');
            $revenue[]  = (int) Order::where('payment_status', PaymentStatus::Paid)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->whereDay('created_at', $day)
                ->sum('total_pkr');
            $expenses[] = (int) Expense::whereYear('expense_date', now()->year)
                ->whereMonth('expense_date', now()->month)
                ->whereDay('expense_date', $day)
                ->sum('amount_pkr');
        }
        return compact('labels', 'revenue', 'expenses');
    }

    // ── Recent expenses ───────────────────────────────────────────────────────

    public function getRecentExpenses(): \Illuminate\Support\Collection
    {
        return Expense::latest('expense_date')
            ->take(8)
            ->get();
    }

    // ── Quick-add expense action ──────────────────────────────────────────────

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_expense')
                ->label('Add expense')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    SchemaGrid::make(2)->schema([
                        Forms\Components\Select::make('category')
                            ->options(collect(ExpenseCategory::cases())->mapWithKeys(
                                fn ($e) => [$e->value => $e->label()]
                            ))
                            ->required(),

                        Forms\Components\DatePicker::make('expense_date')
                            ->label('Date')
                            ->required()
                            ->default(now()),

                        Forms\Components\TextInput::make('description')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('amount_pkr')
                            ->label('Amount (Rs.)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('Rs.'),

                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                ])
                ->action(function (array $data) {
                    Expense::create($data);
                    Notification::make()->title('Expense recorded.')->success()->send();
                }),
        ];
    }
}
