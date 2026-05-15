<x-filament-panels::page>

<style>
.nbm-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.nbm-card-profit-pos {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.nbm-card-profit-neg {
    background: #fff1f2;
    border: 1px solid #fecdd3;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.nbm-label { font-size:11px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; margin:0 0 6px; }
.nbm-value { font-size:22px; font-weight:700; color:#111827; margin:0 0 4px; }
.nbm-sub   { font-size:12px; color:#9ca3af; margin:0; }
.nbm-label-pos { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.08em; margin:0 0 6px; color:#16a34a; }
.nbm-label-neg { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.08em; margin:0 0 6px; color:#e11d48; }
.nbm-value-pos { font-size:22px; font-weight:700; margin:0 0 4px; color:#15803d; }
.nbm-value-neg { font-size:22px; font-weight:700; margin:0 0 4px; color:#be123c; }
.nbm-sub-pos   { font-size:12px; margin:0; color:#16a34a; }
.nbm-sub-neg   { font-size:12px; margin:0; color:#e11d48; }
.nbm-section-title { font-size:14px; font-weight:600; color:#374151; margin:0 0 20px; }

html.dark .nbm-card {
    background: #1f2937;
    border-color: #374151;
    box-shadow: 0 1px 3px rgba(0,0,0,.3);
}
html.dark .nbm-card-profit-pos {
    background: #052e16;
    border-color: #166534;
}
html.dark .nbm-card-profit-neg {
    background: #4c0519;
    border-color: #9f1239;
}
html.dark .nbm-label  { color:#9ca3af; }
html.dark .nbm-value  { color:#f9fafb; }
html.dark .nbm-sub    { color:#6b7280; }
html.dark .nbm-label-pos { color:#4ade80; }
html.dark .nbm-label-neg { color:#fb7185; }
html.dark .nbm-value-pos { color:#86efac; }
html.dark .nbm-value-neg { color:#fda4af; }
html.dark .nbm-sub-pos   { color:#4ade80; }
html.dark .nbm-sub-neg   { color:#fb7185; }
html.dark .nbm-section-title { color:#e5e7eb; }

.nbm-period-btn {
    padding: 6px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}
.nbm-period-btn-inactive {
    background: transparent;
    border: 1px solid #e5e7eb;
    color: #6b7280;
}
html.dark .nbm-period-btn-inactive {
    border-color: #374151;
    color: #9ca3af;
}
.nbm-grid-4  { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
.nbm-grid-21 { display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:20px; }
@media(max-width:900px) {
    .nbm-grid-4  { grid-template-columns:repeat(2,1fr); }
    .nbm-grid-21 { grid-template-columns:1fr; }
}
.nbm-divider { height:1px; background:#e5e7eb; margin:16px 0; }
html.dark .nbm-divider { background:#374151; }
.nbm-table { width:100%; border-collapse:collapse; font-size:13px; }
.nbm-table th { text-align:left; padding:0 0 10px; font-size:11px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid #f3f4f6; }
.nbm-table th.right { text-align:right; }
html.dark .nbm-table th { border-bottom-color:#374151; }
.nbm-table td { padding:11px 0; border-bottom:1px solid #f9fafb; }
html.dark .nbm-table td { border-bottom-color:#1f2937; }
.nbm-table tr:last-child td { border-bottom:none; }
.nbm-bar-bg { height:6px; background:#f3f4f6; border-radius:9999px; overflow:hidden; }
html.dark .nbm-bar-bg { background:#374151; }
.nbm-link { font-size:12px; color:#BFA4CE; font-weight:500; text-decoration:none; }
</style>

{{-- Period selector --}}
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    @foreach([1 => 'This month', 3 => 'Last 3 months', 6 => 'Last 6 months', 12 => 'This year'] as $months => $label)
    <button
        wire:click="setPeriod({{ $months }})"
        class="nbm-period-btn {{ $this->period === $months ? '' : 'nbm-period-btn-inactive' }}"
        style="{{ $this->period === $months ? 'background:#BFA4CE;border:1px solid #BFA4CE;color:#fff;' : '' }}"
    >{{ $label }}</button>
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

<div class="nbm-grid-4">
    <div class="nbm-card">
        <p class="nbm-label">Revenue</p>
        <p class="nbm-value">Rs. {{ number_format($revenue) }}</p>
        <p class="nbm-sub">{{ number_format($orderCount) }} paid {{ Str::plural('order', $orderCount) }}</p>
    </div>

    <div class="nbm-card">
        <p class="nbm-label">Expenses</p>
        <p class="nbm-value">Rs. {{ number_format($expenses) }}</p>
        <p class="nbm-sub">Total outgoings</p>
    </div>

    <div class="{{ $profitable ? 'nbm-card-profit-pos' : 'nbm-card-profit-neg' }}">
        <p class="{{ $profitable ? 'nbm-label-pos' : 'nbm-label-neg' }}">Net Profit</p>
        <p class="{{ $profitable ? 'nbm-value-pos' : 'nbm-value-neg' }}">
            {{ $profitable ? '' : '−' }}Rs. {{ number_format(abs($net)) }}
        </p>
        <p class="{{ $profitable ? 'nbm-sub-pos' : 'nbm-sub-neg' }}">
            @if($revenue > 0) {{ round(($net / $revenue) * 100) }}% margin
            @else No revenue yet @endif
        </p>
    </div>

    <div class="nbm-card">
        <p class="nbm-label">Avg Order</p>
        <p class="nbm-value">Rs. {{ $orderCount > 0 ? number_format(intdiv($revenue, $orderCount)) : '—' }}</p>
        <p class="nbm-sub">Per paid order</p>
    </div>
</div>

{{-- Chart + Breakdown --}}
@php $chart = $this->getChartData(); @endphp
<div class="nbm-grid-21">
    <div class="nbm-card">
        <p class="nbm-section-title">Monthly Revenue vs Expenses</p>
        <canvas id="financeChart" style="width:100%;max-height:240px;"></canvas>
    </div>

    <div class="nbm-card">
        <p class="nbm-section-title">Expenses by Category</p>
        @php $breakdown = $this->getCategoryBreakdown(); @endphp
        @if(empty($breakdown))
            <p style="font-size:13px;color:#9ca3af;font-style:italic;margin:0;">No expenses logged yet.</p>
        @else
            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach($breakdown as $row)
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                        <span style="font-size:12px;">{{ $row['label'] }}</span>
                        <span style="font-size:12px;font-weight:600;">Rs. {{ number_format($row['amount']) }}</span>
                    </div>
                    <div class="nbm-bar-bg">
                        <div style="height:100%;border-radius:9999px;width:{{ $row['percent'] }}%;background:{{ $row['color'] }};"></div>
                    </div>
                    <p style="font-size:11px;color:#9ca3af;text-align:right;margin:3px 0 0;">{{ $row['percent'] }}%</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Recent expenses --}}
<div class="nbm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <p class="nbm-section-title" style="margin:0;">Recent Expenses</p>
        <a href="{{ \App\Filament\Resources\ExpenseResource::getUrl('index') }}" class="nbm-link">View all →</a>
    </div>
    @php $recent = $this->getRecentExpenses(); @endphp
    @if($recent->isEmpty())
        <p style="font-size:13px;color:#9ca3af;font-style:italic;margin:0;">No expenses logged yet.</p>
    @else
        <div style="overflow-x:auto;">
            <table class="nbm-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th class="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $exp)
                    <tr>
                        <td style="color:#6b7280;white-space:nowrap;">{{ $exp->expense_date->format('d M Y') }}</td>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;">
                                <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $exp->category->color() }};"></span>
                                {{ $exp->category->label() }}
                            </span>
                        </td>
                        <td>{{ $exp->description }}</td>
                        <td style="text-align:right;font-weight:600;white-space:nowrap;">Rs. {{ number_format($exp->amount_pkr) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Chart.js --}}
<script>
document.addEventListener('DOMContentLoaded', function () { loadOrInitChart(); });
document.addEventListener('livewire:navigated', function () { loadOrInitChart(); });
Livewire.hook('commit', ({ succeed }) => {
    succeed(() => { requestAnimationFrame(() => initFinanceChart()); });
});
function loadOrInitChart() {
    if (typeof Chart === 'undefined') {
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
        s.onload = () => initFinanceChart();
        document.head.appendChild(s);
    } else {
        initFinanceChart();
    }
}
function initFinanceChart() {
    const canvas = document.getElementById('financeChart');
    if (!canvas) return;
    if (canvas._chartInstance) canvas._chartInstance.destroy();
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#9CA3AF' : '#6B7280';
    canvas._chartInstance = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: @json($chart['labels']),
            datasets: [
                { label: 'Revenue', data: @json($chart['revenue']), backgroundColor: 'rgba(191,164,206,0.85)', borderRadius: 5, borderSkipped: false },
                { label: 'Expenses', data: @json($chart['expenses']), backgroundColor: 'rgba(201,110,110,0.75)', borderRadius: 5, borderSkipped: false },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { labels: { color: textColor, font: { size: 12 }, boxWidth: 12, boxHeight: 12 } },
                tooltip: { callbacks: { label: ctx => ' Rs. ' + ctx.parsed.y.toLocaleString() } },
            },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 } } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 }, callback: val => 'Rs. ' + (val >= 1000 ? (val/1000).toFixed(0)+'k' : val) } },
            },
        },
    });
}
</script>

</x-filament-panels::page>
