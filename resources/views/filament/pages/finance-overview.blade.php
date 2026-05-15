<x-filament-panels::page>

    {{-- Period selector --}}
    <div class="flex gap-2 flex-wrap">
        @foreach([1 => 'This month', 3 => 'Last 3 months', 6 => 'Last 6 months', 12 => 'This year'] as $months => $label)
        <button
            wire:click="setPeriod({{ $months }})"
            @class([
                'px-4 py-1.5 rounded-full text-sm font-medium transition-colors',
                'bg-primary-600 text-white shadow-sm'  => $this->period === $months,
                'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' => $this->period !== $months,
            ])
        >
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- KPI cards --}}
    @php
        $revenue    = $this->getRevenue();
        $expenses   = $this->getExpenses();
        $net        = $this->getNetProfit();
        $orderCount = $this->getOrderCount();
        $profitable = $net >= 0;
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
        {{-- Revenue --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Revenue</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rs. {{ number_format($revenue) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ number_format($orderCount) }} paid {{ Str::plural('order', $orderCount) }}</p>
        </div>

        {{-- Expenses --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Expenses</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rs. {{ number_format($expenses) }}</p>
            <p class="text-xs text-gray-400 mt-1">Total outgoings</p>
        </div>

        {{-- Net Profit --}}
        <div @class([
            'rounded-2xl border p-5 shadow-sm col-span-2 lg:col-span-1',
            'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800' => $profitable,
            'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-800'             => ! $profitable,
        ])>
            <p @class([
                'text-xs font-medium uppercase tracking-widest mb-1',
                'text-emerald-600 dark:text-emerald-400' => $profitable,
                'text-rose-600 dark:text-rose-400'       => ! $profitable,
            ])>Net Profit</p>
            <p @class([
                'text-2xl font-bold',
                'text-emerald-700 dark:text-emerald-300' => $profitable,
                'text-rose-700 dark:text-rose-300'       => ! $profitable,
            ])>{{ $profitable ? '' : '−' }}Rs. {{ number_format(abs($net)) }}</p>
            <p @class([
                'text-xs mt-1',
                'text-emerald-500' => $profitable,
                'text-rose-500'    => ! $profitable,
            ])>
                @if($revenue > 0)
                    {{ round(($net / $revenue) * 100) }}% margin
                @else
                    No revenue yet
                @endif
            </p>
        </div>

        {{-- Avg order value --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Avg Order</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                Rs. {{ $orderCount > 0 ? number_format(intdiv($revenue, $orderCount)) : '—' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Per paid order</p>
        </div>
    </div>

    {{-- Chart + Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-2">

        {{-- Monthly bar chart --}}
        <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Monthly Revenue vs Expenses</p>
            @php $chart = $this->getChartData(); @endphp
            <canvas id="financeChart" height="120"></canvas>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                initFinanceChart();
            });
            document.addEventListener('livewire:navigated', function () {
                initFinanceChart();
            });
            // Re-init after Livewire updates the DOM
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => {
                    requestAnimationFrame(() => initFinanceChart());
                });
            });
            function initFinanceChart() {
                const canvas = document.getElementById('financeChart');
                if (!canvas) return;
                if (canvas._chartInstance) {
                    canvas._chartInstance.destroy();
                }
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
                const textColor = isDark ? '#9CA3AF' : '#6B7280';
                canvas._chartInstance = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: @json($chart['labels']),
                        datasets: [
                            {
                                label: 'Revenue',
                                data: @json($chart['revenue']),
                                backgroundColor: 'rgba(191,164,206,0.85)',
                                borderRadius: 6,
                                borderSkipped: false,
                            },
                            {
                                label: 'Expenses',
                                data: @json($chart['expenses']),
                                backgroundColor: 'rgba(201,110,110,0.75)',
                                borderRadius: 6,
                                borderSkipped: false,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                labels: { color: textColor, font: { size: 12 }, boxWidth: 12, boxHeight: 12 },
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ' Rs. ' + ctx.parsed.y.toLocaleString(),
                                },
                            },
                        },
                        scales: {
                            x: { grid: { color: gridColor }, ticks: { color: textColor } },
                            y: {
                                grid: { color: gridColor },
                                ticks: {
                                    color: textColor,
                                    callback: val => 'Rs. ' + (val >= 1000 ? (val/1000).toFixed(0) + 'k' : val),
                                },
                            },
                        },
                    },
                });
            }
            </script>
        </div>

        {{-- Expense breakdown by category --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Expenses by Category</p>
            @php $breakdown = $this->getCategoryBreakdown(); @endphp
            @if(empty($breakdown))
                <p class="text-sm text-gray-400 italic">No expenses logged yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($breakdown as $row)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600 dark:text-gray-300">{{ $row['label'] }}</span>
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Rs. {{ number_format($row['amount']) }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width: {{ $row['percent'] }}%; background: {{ $row['color'] }}"></div>
                        </div>
                        <p class="text-xs text-gray-400 text-right mt-0.5">{{ $row['percent'] }}%</p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent expenses table --}}
    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm mt-2">
        <div class="flex justify-between items-center mb-4">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Recent Expenses</p>
            <a href="{{ \App\Filament\Resources\ExpenseResource::getUrl('index') }}"
               class="text-xs text-primary-600 hover:text-primary-500 font-medium">
                View all →
            </a>
        </div>
        @php $recent = $this->getRecentExpenses(); @endphp
        @if($recent->isEmpty())
            <p class="text-sm text-gray-400 italic">No expenses logged yet. Use "Add expense" to record your first one.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left pb-2 font-medium">Date</th>
                            <th class="text-left pb-2 font-medium">Category</th>
                            <th class="text-left pb-2 font-medium">Description</th>
                            <th class="text-right pb-2 font-medium">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach($recent as $exp)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-2.5 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $exp->expense_date->format('d M Y') }}
                            </td>
                            <td class="py-2.5">
                                <span class="inline-flex items-center gap-1 text-xs">
                                    <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $exp->category->color() }}"></span>
                                    {{ $exp->category->label() }}
                                </span>
                            </td>
                            <td class="py-2.5 text-gray-700 dark:text-gray-300">{{ $exp->description }}</td>
                            <td class="py-2.5 text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Rs. {{ number_format($exp->amount_pkr) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Chart.js (loaded once per page visit) --}}
    <script>
    if (typeof Chart === 'undefined') {
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
        s.onload = () => initFinanceChart();
        document.head.appendChild(s);
    } else {
        initFinanceChart();
    }
    </script>

</x-filament-panels::page>
