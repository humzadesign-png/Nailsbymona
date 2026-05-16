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

{{-- All-periods data for JS switching — updated by Livewire on re-render --}}
<div id="nbm-periods-data" style="display:none;"
     data-periods='@json($this->getAllPeriodsData(), JSON_HEX_APOS)'></div>

{{-- Period selector — onclick JS, no Livewire round-trip --}}
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    @foreach([1 => 'This month', 3 => 'Last 3 months', 6 => 'Last 6 months', 12 => 'This year'] as $months => $label)
    <button
        class="nbm-period-btn {{ $months === 1 ? '' : 'nbm-period-btn-inactive' }}"
        style="{{ $months === 1 ? 'background:#BFA4CE;border:1px solid #BFA4CE;color:#fff;' : '' }}"
        data-months="{{ $months }}"
        onclick="switchPeriod({{ $months }})"
    >{{ $label }}</button>
    @endforeach
</div>

{{-- KPI cards — initial values from PHP; JS updates on tab switch --}}
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
        <p class="nbm-value" id="nbm-revenue">Rs. {{ number_format($revenue) }}</p>
        <p class="nbm-sub" id="nbm-orders">{{ number_format($orderCount) }} paid {{ Str::plural('order', $orderCount) }}</p>
    </div>

    <div class="nbm-card">
        <p class="nbm-label">Expenses</p>
        <p class="nbm-value" id="nbm-expenses">Rs. {{ number_format($expenses) }}</p>
        <p class="nbm-sub">Total outgoings</p>
    </div>

    <div id="nbm-net-card" class="{{ $profitable ? 'nbm-card-profit-pos' : 'nbm-card-profit-neg' }}">
        <p id="nbm-net-label" class="{{ $profitable ? 'nbm-label-pos' : 'nbm-label-neg' }}">Net Profit</p>
        <p id="nbm-net" class="{{ $profitable ? 'nbm-value-pos' : 'nbm-value-neg' }}">
            {{ $profitable ? '' : '−' }}Rs. {{ number_format(abs($net)) }}
        </p>
        <p id="nbm-margin" class="{{ $profitable ? 'nbm-sub-pos' : 'nbm-sub-neg' }}">
            @if($revenue > 0) {{ round(($net / $revenue) * 100) }}% margin
            @else No revenue yet @endif
        </p>
    </div>

    <div class="nbm-card">
        <p class="nbm-label">Avg Order</p>
        <p class="nbm-value" id="nbm-avg">{{ $orderCount > 0 ? 'Rs. ' . number_format(intdiv($revenue, $orderCount)) : '—' }}</p>
        <p class="nbm-sub">Per paid order</p>
    </div>
</div>

{{-- Chart + Breakdown --}}
@php $chart = $this->getChartData(); @endphp
<div class="nbm-grid-21">
    <div class="nbm-card">
        <p class="nbm-section-title">Monthly Revenue vs Expenses</p>
        {{-- wire:ignore prevents Livewire morphdom from destroying the chart canvas on period tab changes --}}
        <div wire:ignore style="position:relative;height:240px;">
            <canvas id="financeChart"
                data-labels='@json($chart["labels"])'
                data-revenue='@json($chart["revenue"])'
                data-expenses='@json($chart["expenses"])'
            ></canvas>
        </div>
    </div>

    <div class="nbm-card">
        <p class="nbm-section-title">Expenses by Category</p>
        {{-- id="nbm-breakdown" is the target JS updates when switching periods --}}
        @php $breakdown = $this->getCategoryBreakdown(); @endphp
        <div id="nbm-breakdown">
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

{{-- Period switching (client-side, no Livewire round-trip) + Chart.js --}}
<script>
let _currentPeriod = 1;
let _periodsData   = {};

function _loadPeriods() {
    const el = document.getElementById('nbm-periods-data');
    if (!el) return;
    _periodsData = JSON.parse(el.dataset.periods);
    switchPeriod(_currentPeriod);
}

function switchPeriod(months) {
    _currentPeriod = months;
    const d = _periodsData[months];
    if (!d) return;

    // Buttons
    document.querySelectorAll('[data-months]').forEach(function (btn) {
        const m = parseInt(btn.dataset.months, 10);
        if (m === months) {
            btn.style.cssText = 'background:#BFA4CE;border:1px solid #BFA4CE;color:#fff;';
            btn.classList.remove('nbm-period-btn-inactive');
        } else {
            btn.style.cssText = '';
            btn.classList.add('nbm-period-btn-inactive');
        }
    });

    // KPI values
    document.getElementById('nbm-revenue').textContent  = 'Rs. ' + d.revenue.toLocaleString();
    document.getElementById('nbm-orders').textContent   = d.orderCount.toLocaleString() + ' paid ' + (d.orderCount === 1 ? 'order' : 'orders');
    document.getElementById('nbm-expenses').textContent = 'Rs. ' + d.expenses.toLocaleString();
    document.getElementById('nbm-avg').textContent      = d.avgOrder !== null ? 'Rs. ' + d.avgOrder.toLocaleString() : '—';

    // Net profit card
    document.getElementById('nbm-net-card').className  = d.profitable ? 'nbm-card-profit-pos' : 'nbm-card-profit-neg';
    document.getElementById('nbm-net-label').className = d.profitable ? 'nbm-label-pos' : 'nbm-label-neg';
    const netEl = document.getElementById('nbm-net');
    netEl.className   = d.profitable ? 'nbm-value-pos' : 'nbm-value-neg';
    netEl.textContent = (d.profitable ? '' : '−') + 'Rs. ' + Math.abs(d.net).toLocaleString();
    const marginEl    = document.getElementById('nbm-margin');
    marginEl.className   = d.profitable ? 'nbm-sub-pos' : 'nbm-sub-neg';
    marginEl.textContent = d.margin !== null ? d.margin + '% margin' : 'No revenue yet';

    // Breakdown
    const bEl = document.getElementById('nbm-breakdown');
    if (!bEl) return;
    if (!d.breakdown || d.breakdown.length === 0) {
        bEl.innerHTML = '<p style="font-size:13px;color:#9ca3af;font-style:italic;margin:0;">No expenses logged yet.</p>';
    } else {
        bEl.innerHTML = '<div style="display:flex;flex-direction:column;gap:14px;">'
            + d.breakdown.map(function (r) {
                return '<div>'
                    + '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">'
                    + '<span style="font-size:12px;">' + _esc(r.label) + '</span>'
                    + '<span style="font-size:12px;font-weight:600;">Rs. ' + r.amount.toLocaleString() + '</span>'
                    + '</div>'
                    + '<div class="nbm-bar-bg"><div style="height:100%;border-radius:9999px;width:' + r.percent + '%;background:' + _esc(r.color) + ';"></div></div>'
                    + '<p style="font-size:11px;color:#9ca3af;text-align:right;margin:3px 0 0;">' + r.percent + '%</p>'
                    + '</div>';
            }).join('')
            + '</div>';
    }
}

function _esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Chart.js
function _loadOrInitChart() {
    if (typeof Chart === 'undefined') {
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
        s.onload = function () { _initChart(); };
        document.head.appendChild(s);
    } else {
        _initChart();
    }
}
function _initChart() {
    const canvas = document.getElementById('financeChart');
    if (!canvas) return;
    if (canvas._chartInstance) canvas._chartInstance.destroy();
    const isDark    = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#9CA3AF' : '#6B7280';
    canvas._chartInstance = new Chart(canvas, {
        type: 'bar',
        data: {
            labels:   JSON.parse(canvas.dataset.labels),
            datasets: [
                { label: 'Revenue',  data: JSON.parse(canvas.dataset.revenue),  backgroundColor: 'rgba(191,164,206,0.85)', borderRadius: 5, borderSkipped: false },
                { label: 'Expenses', data: JSON.parse(canvas.dataset.expenses), backgroundColor: 'rgba(201,110,110,0.75)', borderRadius: 5, borderSkipped: false },
            ],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend:  { labels: { color: textColor, font: { size: 12 }, boxWidth: 12, boxHeight: 12 } },
                tooltip: { callbacks: { label: function (ctx) { return ' Rs. ' + ctx.parsed.y.toLocaleString(); } } },
            },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 } } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 }, callback: function (val) { return 'Rs. ' + (val >= 1000 ? (val/1000).toFixed(0)+'k' : val); } } },
            },
        },
    });
}

document.addEventListener('DOMContentLoaded', function () { _loadPeriods(); _loadOrInitChart(); });
document.addEventListener('livewire:navigated', function () { _loadPeriods(); _loadOrInitChart(); });
// After Livewire re-renders (e.g. add-expense modal), refresh period data and restore active tab
document.addEventListener('livewire:update', function () { _loadPeriods(); });
</script>

</x-filament-panels::page>
