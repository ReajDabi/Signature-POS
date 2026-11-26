<x-layouts.app title="Executive Dashboard" subtitle="Real-time overview · All Branches" portal="hq">

    @php
        /* ── Demo KPI data ─────────────────────────────── */
        $kpis = [
            ['label' => 'Total Revenue', 'value' => '₱ 284,750.00', 'icon' => 'cash',       'hint' => 'All branches · MTD', 'badge' => '+12.4% vs last month', 'tone' => 'emerald'],
            ['label' => 'Transactions',  'value' => '1,342',         'icon' => 'transactions','hint' => 'Completed · MTD',   'badge' => '+8.1%',               'tone' => 'emerald'],
            ['label' => 'Active Branches','value' => '3',            'icon' => 'branches',   'hint' => 'TCC · APK · VSV',   'badge' => 'All Online',          'tone' => 'gold'],
            ['label' => 'Low-Stock Items','value' => '7',            'icon' => 'alert',      'hint' => 'Across all branches','badge' => '2 Out of Stock',      'tone' => 'rose'],
        ];

        /* ── Chart datasets (JSON for Alpine / Chart.js) ─ */
        $revenueLabels = json_encode(['Nov 20','Nov 21','Nov 22','Nov 23','Nov 24','Nov 25','Nov 26']);
        $revenueDatasets = json_encode([
            ['label' => 'TCC', 'data' => [12800, 15200, 11400, 16900, 14300, 17100, 13500]],
            ['label' => 'APK', 'data' => [9100,  10400, 8700,  12100, 11200, 13000, 10800]],
            ['label' => 'VSV', 'data' => [7300,  8500,  7100,  9800,  8900,  10200, 8600 ]],
        ]);
        $catLabels   = json_encode(['Hair', 'Nail', 'Facial', 'Lash & Brow', 'Waxing']);
        $catRevenue  = json_encode([98400, 72300, 54200, 38100, 21750]);

        /* ── Low-stock watchlist ─────────────────────────── */
        $lowStock = [
            ['branch' => 'TCC', 'product' => 'Bleaching Powder 500g', 'qty' => 1,  'threshold' => 5,  'status' => 'low'],
            ['branch' => 'APK', 'product' => 'Nail Polish Remover 1L', 'qty' => 0,  'threshold' => 3,  'status' => 'out'],
            ['branch' => 'TCC', 'product' => 'Hair Toner 200ml',       'qty' => 2,  'threshold' => 5,  'status' => 'low'],
            ['branch' => 'VSV', 'product' => 'Gel Base Coat 50ml',     'qty' => 0,  'threshold' => 4,  'status' => 'out'],
            ['branch' => 'APK', 'product' => 'Keratin Treatment 300ml','qty' => 3,  'threshold' => 6,  'status' => 'low'],
            ['branch' => 'VSV', 'product' => 'Facial Serum 30ml',      'qty' => 2,  'threshold' => 5,  'status' => 'low'],
            ['branch' => 'TCC', 'product' => 'Wax Strip Roll 100pcs',  'qty' => 4,  'threshold' => 10, 'status' => 'low'],
        ];

        /* ── Recent transactions ─────────────────────────── */
        $recentTx = [
            ['ref' => 'TX-2026-004812', 'branch' => 'TCC', 'services' => 'Full Hair Color, Blow Dry',       'amount' => '₱ 2,850.00', 'cashier' => 'Maria L.',  'status' => 'completed', 'time' => '11:42 AM'],
            ['ref' => 'TX-2026-004811', 'branch' => 'APK', 'services' => 'Gel Manicure, Pedicure',          'amount' => '₱ 1,200.00', 'cashier' => 'Rosa T.',   'status' => 'completed', 'time' => '11:30 AM'],
            ['ref' => 'TX-2026-004810', 'branch' => 'VSV', 'services' => 'Facial Treatment',                'amount' => '₱ 1,800.00', 'cashier' => 'Liza M.',   'status' => 'completed', 'time' => '11:15 AM'],
            ['ref' => 'TX-2026-004809', 'branch' => 'TCC', 'services' => 'Hair Rebond',                     'amount' => '₱ 3,500.00', 'cashier' => 'Maria L.',  'status' => 'completed', 'time' => '10:58 AM'],
            ['ref' => 'TX-2026-004808', 'branch' => 'APK', 'services' => 'Lash Lift, Brow Lamination',      'amount' => '₱ 2,100.00', 'cashier' => 'Rosa T.',   'status' => 'voided',    'time' => '10:40 AM'],
            ['ref' => 'TX-2026-004807', 'branch' => 'TCC', 'services' => 'Cut & Style',                     'amount' => '₱  650.00',  'cashier' => 'Maria L.',  'status' => 'completed', 'time' => '10:22 AM'],
            ['ref' => 'TX-2026-004806', 'branch' => 'VSV', 'services' => 'Underarm Waxing',                 'amount' => '₱  400.00',  'cashier' => 'Liza M.',   'status' => 'completed', 'time' => '10:05 AM'],
            ['ref' => 'TX-2026-004805', 'branch' => 'APK', 'services' => 'Nail Art (Full Set)',             'amount' => '₱  950.00',  'cashier' => 'Rosa T.',   'status' => 'completed', 'time' => '09:48 AM'],
            ['ref' => 'TX-2026-004804', 'branch' => 'TCC', 'services' => 'Keratin Treatment',              'amount' => '₱ 4,200.00', 'cashier' => 'Maria L.',  'status' => 'completed', 'time' => '09:30 AM'],
            ['ref' => 'TX-2026-004803', 'branch' => 'VSV', 'services' => 'Facial + Dermaplaning',          'amount' => '₱ 2,400.00', 'cashier' => 'Liza M.',   'status' => 'completed', 'time' => '09:12 AM'],
        ];
    @endphp

    {{-- KPI Row --------------------------------------------------------- --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($kpis as $kpi)
            <x-ui.stat-card
                :label="$kpi['label']"
                :value="$kpi['value']"
                :icon="$kpi['icon']"
                :hint="$kpi['hint']"
                :badge="$kpi['badge']"
                :badgeTone="$kpi['tone']"
            />
        @endforeach
    </div>

    {{-- Charts Row ------------------------------------------------------ --}}
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-5">

        {{-- Cross-branch revenue bar (3/5 width) --}}
        <x-ui.card title="Daily Revenue · Last 7 Days" subtitle="₱ per branch" class="xl:col-span-3">
            <x-slot:actions>
                <span class="text-xs text-theme-muted tabular-nums">Nov 20–26, 2026</span>
            </x-slot:actions>
            <div
                x-data="branchRevenueChart()"
                x-init="init()"
                class="relative h-72"
            >
                <canvas x-ref="canvas" class="w-full h-full"></canvas>
            </div>
        </x-ui.card>

        {{-- Service category doughnut (2/5 width) --}}
        <x-ui.card title="Revenue by Category" subtitle="Month-to-date split" class="xl:col-span-2">
            <div
                x-data="categoryChart()"
                x-init="init()"
                class="relative flex flex-col items-center gap-4"
            >
                <div class="relative h-56 w-56">
                    <canvas x-ref="canvas" class="w-full h-full"></canvas>
                </div>
                {{-- Legend --}}
                <ul class="w-full space-y-1 text-xs">
                    @php $catPairs = [['Hair','₱ 98,400'],['Nail','₱ 72,300'],['Facial','₱ 54,200'],['Lash & Brow','₱ 38,100'],['Waxing','₱ 21,750']]; @endphp
                    @foreach ($catPairs as [$cat, $rev])
                        <li class="flex items-center justify-between gap-2">
                            <span class="text-theme-secondary">{{ $cat }}</span>
                            <span class="tabular-nums font-medium text-theme-primary">{{ $rev }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </x-ui.card>
    </div>

    {{-- Low-stock Watchlist -------------------------------------------- --}}
    <div class="mt-6">
        <x-ui.card title="Low-Stock Watchlist" subtitle="Products below reorder threshold">
            <x-slot:actions>
                <a href="{{ route('hq.products.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                    View All Products →
                </a>
            </x-slot:actions>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Branch</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Product</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Qty</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Threshold</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        @foreach ($lowStock as $row)
                            <tr class="hover:bg-theme-elevated/50 transition">
                                <td class="py-3 pr-4">
                                    <span class="rounded-md bg-gold-soft px-2 py-0.5 text-xs font-semibold text-gold-accent">
                                        {{ $row['branch'] }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-theme-primary font-medium">{{ $row['product'] }}</td>
                                <td class="py-3 pr-4 text-right tabular-nums text-theme-secondary">{{ $row['qty'] }}</td>
                                <td class="py-3 pr-4 text-right tabular-nums text-theme-muted">{{ $row['threshold'] }}</td>
                                <td class="py-3 pl-4">
                                    <x-ui.low-stock-badge :status="$row['status']" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

    {{-- Recent Transactions --------------------------------------------- --}}
    <div class="mt-6">
        <x-ui.card title="Recent Transactions" subtitle="Latest 10 across all branches">
            <x-slot:actions>
                <a href="{{ route('hq.reports.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                    Full Reports →
                </a>
            </x-slot:actions>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Ref #</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Branch</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Services</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Amount</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Status</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Cashier</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        @foreach ($recentTx as $tx)
                            <tr class="hover:bg-theme-elevated/50 transition">
                                <td class="py-3 pr-4 font-mono text-xs text-theme-muted">{{ $tx['ref'] }}</td>
                                <td class="py-3 pr-4">
                                    <span class="rounded-md bg-gold-soft px-2 py-0.5 text-xs font-semibold text-gold-accent">
                                        {{ $tx['branch'] }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-theme-secondary max-w-[220px] truncate">{{ $tx['services'] }}</td>
                                <td class="py-3 pr-4 text-right tabular-nums font-medium text-theme-primary">{{ $tx['amount'] }}</td>
                                <td class="py-3 pl-4">
                                    <x-ui.status-badge :status="$tx['status']" />
                                </td>
                                <td class="py-3 pl-4 text-theme-secondary">{{ $tx['cashier'] }}</td>
                                <td class="py-3 text-right tabular-nums text-xs text-theme-muted">{{ $tx['time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

    {{-- Alpine / Chart.js component scripts ----------------------------- --}}
    @push('scripts')
    <script>
    function branchRevenueChart() {
        return {
            init() {
                const palette = window.signatureThemePalette ? window.signatureThemePalette() : {};
                const gold    = palette.gold    ?? '#c9a84c';
                const accent1 = palette.accent1 ?? '#7c6f3e';
                const muted   = palette.muted   ?? '#94a3b8';

                new Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels: {!! $revenueLabels !!},
                        datasets: {!! $revenueDatasets !!}.map((ds, i) => ({
                            ...ds,
                            backgroundColor: [gold, accent1, muted][i] + (i === 0 ? '' : '99'),
                            borderRadius: 6,
                            borderSkipped: false,
                        })),
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: palette.text ?? '#94a3b8', font: { family: 'Inter', size: 11 } } },
                            tooltip: { callbacks: { label: (ctx) => ' ₱ ' + ctx.parsed.y.toLocaleString('en-PH') } },
                        },
                        scales: {
                            x: { ticks: { color: palette.text ?? '#94a3b8', font: { size: 11 } }, grid: { color: palette.grid ?? 'rgba(255,255,255,0.06)' } },
                            y: { ticks: { color: palette.text ?? '#94a3b8', font: { size: 11 }, callback: (v) => '₱' + (v/1000).toFixed(0) + 'k' }, grid: { color: palette.grid ?? 'rgba(255,255,255,0.06)' } },
                        },
                    },
                });
            },
        };
    }

    function categoryChart() {
        return {
            init() {
                const palette = window.signatureThemePalette ? window.signatureThemePalette() : {};
                new Chart(this.$refs.canvas, {
                    type: 'doughnut',
                    data: {
                        labels: {!! $catLabels !!},
                        datasets: [{ data: {!! $catRevenue !!}, backgroundColor: ['#c9a84c','#7c6f3e','#a89560','#d4b96e','#5a5030'], hoverOffset: 8, borderWidth: 2, borderColor: 'transparent' }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: (ctx) => ' ₱ ' + ctx.parsed.toLocaleString('en-PH') } },
                        },
                    },
                });
            },
        };
    }
    </script>
    @endpush

</x-layouts.app>
