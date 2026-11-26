<x-layouts.app
    title="Executive Dashboard"
    subtitle="Real-time multi-branch performance · Tagum Network (TCC · APK · VSV)"
    portal="hq"
>
    @php
        $kpis = [
            [
                'label' => 'Gross Revenue (MTD)',
                'value' => '₱ 284,750.00',
                'icon' => 'cash',
                'hint' => 'Cash-only · No VAT v1',
                'badge' => '+12.4% vs Oct',
                'tone' => 'emerald',
            ],
            [
                'label' => 'Completed Transactions',
                'value' => '1,342',
                'icon' => 'transactions',
                'hint' => '14 voided excluded',
                'badge' => '+8.1% MoM',
                'tone' => 'emerald',
            ],
            [
                'label' => 'Active Branches',
                'value' => '3',
                'icon' => 'branches',
                'hint' => 'TCC · APK · VSV online',
                'badge' => '19 Technicians',
                'tone' => 'gold',
            ],
            [
                'label' => 'Low-Stock Alerts',
                'value' => '6',
                'icon' => 'alert',
                'hint' => 'At or below threshold',
                'badge' => '2 Out of Stock',
                'tone' => 'rose',
            ],
        ];

        $branchSnapshots = [
            ['code' => 'TCC', 'name' => 'Tagum City Center', 'revenue' => '₱ 121,450.00', 'tx' => 568, 'share' => '42.6%', 'alerts' => 2],
            ['code' => 'APK', 'name' => 'Apokon', 'revenue' => '₱ 91,800.00', 'tx' => 441, 'share' => '32.2%', 'alerts' => 2],
            ['code' => 'VSV', 'name' => 'Visayan Village', 'revenue' => '₱ 71,500.00', 'tx' => 333, 'share' => '25.2%', 'alerts' => 2],
        ];

        $lowStockWatchlist = [
            ['branch' => 'APK', 'product' => 'Nail Polish Remover', 'unit' => 'bottle', 'qty' => '0.00', 'threshold' => '3.00', 'status' => 'out'],
            ['branch' => 'VSV', 'product' => 'Gel Polish', 'unit' => 'bottle', 'qty' => '0.00', 'threshold' => '8.00', 'status' => 'out'],
            ['branch' => 'TCC', 'product' => 'Rebond Cream', 'unit' => 'jar', 'qty' => '2.00', 'threshold' => '2.00', 'status' => 'low'],
            ['branch' => 'TCC', 'product' => 'Hair Color Tube', 'unit' => 'tube', 'qty' => '7.00', 'threshold' => '10.00', 'status' => 'low'],
            ['branch' => 'APK', 'product' => 'Cuticle Oil', 'unit' => 'bottle', 'qty' => '3.00', 'threshold' => '4.00', 'status' => 'low'],
            ['branch' => 'VSV', 'product' => 'Hair Serum 100ml', 'unit' => 'bottle', 'qty' => '4.00', 'threshold' => '5.00', 'status' => 'low'],
        ];

        $recentTransactions = [
            ['ref' => 'TX-TCC-261126-041', 'branch' => 'TCC', 'services' => 'Rebond, Hair Serum Care', 'technician' => 'Maria Santos', 'amount' => '₱ 2,850.00', 'status' => 'completed', 'time' => '11:42 AM'],
            ['ref' => 'TX-APK-261126-028', 'branch' => 'APK', 'services' => 'Gel Polish, Pedicure', 'technician' => 'Ana Dela Cruz', 'amount' => '₱ 750.00', 'status' => 'completed', 'time' => '11:30 AM'],
            ['ref' => 'TX-VSV-261126-019', 'branch' => 'VSV', 'services' => 'Hair Color (Full)', 'technician' => 'Jun Reyes', 'amount' => '₱ 1,800.00', 'status' => 'completed', 'time' => '11:15 AM'],
            ['ref' => 'TX-TCC-261126-040', 'branch' => 'TCC', 'services' => 'Rebond (Senior 20% Off)', 'technician' => 'Maria Santos', 'amount' => '₱ 2,000.00', 'status' => 'completed', 'time' => '10:58 AM'],
            ['ref' => 'TX-APK-261126-027', 'branch' => 'APK', 'services' => 'Foot Spa, Manicure', 'technician' => 'Liza Mendoza', 'amount' => '₱ 750.00', 'status' => 'voided', 'time' => '10:40 AM'],
            ['ref' => 'TX-TCC-261126-039', 'branch' => 'TCC', 'services' => 'Haircut (Women)', 'technician' => 'Ana Dela Cruz', 'amount' => '₱ 350.00', 'status' => 'completed', 'time' => '10:22 AM'],
            ['ref' => 'TX-VSV-261126-018', 'branch' => 'VSV', 'services' => 'Gel Polish', 'technician' => 'Liza Mendoza', 'amount' => '₱ 450.00', 'status' => 'completed', 'time' => '10:05 AM'],
            ['ref' => 'TX-APK-261126-026', 'branch' => 'APK', 'services' => 'Hair Color (Full), Haircut (Women)', 'technician' => 'Jun Reyes', 'amount' => '₱ 2,150.00', 'status' => 'completed', 'time' => '09:48 AM'],
            ['ref' => 'TX-TCC-261126-038', 'branch' => 'TCC', 'services' => 'Foot Spa', 'technician' => 'Liza Mendoza', 'amount' => '₱ 500.00', 'status' => 'completed', 'time' => '09:30 AM'],
            ['ref' => 'TX-VSV-261126-017', 'branch' => 'VSV', 'services' => 'Haircut (Men)', 'technician' => 'Jun Reyes', 'amount' => '₱ 200.00', 'status' => 'completed', 'time' => '09:12 AM'],
        ];

        $categorySplits = [
            ['name' => 'Hair Services', 'revenue' => 176400, 'formatted' => '₱ 176,400.00', 'pct' => '61.9%'],
            ['name' => 'Nail & Foot Spa', 'revenue' => 108350, 'formatted' => '₱ 108,350.00', 'pct' => '38.1%'],
        ];

        $chartConfig = [
            'days7' => [
                'labels' => ['Nov 20', 'Nov 21', 'Nov 22', 'Nov 23', 'Nov 24', 'Nov 25', 'Nov 26'],
                'tcc' => [12800, 15200, 11400, 16900, 14300, 17100, 13500],
                'apk' => [9100, 10400, 8700, 12100, 11200, 13000, 10800],
                'vsv' => [7300, 8500, 7100, 9800, 8900, 10200, 8600],
            ],
            'days14' => [
                'labels' => ['Nov 13', 'Nov 15', 'Nov 17', 'Nov 19', 'Nov 21', 'Nov 23', 'Nov 26'],
                'tcc' => [11900, 13400, 14100, 12600, 15200, 16900, 13500],
                'apk' => [8400, 9600, 10100, 9300, 10400, 12100, 10800],
                'vsv' => [6800, 7900, 8200, 7400, 8500, 9800, 8600],
            ],
            'categories' => [
                'labels' => ['Hair Color (Full)', 'Rebond', 'Haircut (W/M)', 'Gel Polish & Nail', 'Foot Spa'],
                'values' => [84600, 62500, 29300, 64850, 43500],
            ],
        ];
    @endphp

    <div class="space-y-8">
        {{-- Section 1: KPI Cards --}}
        <section class="space-y-4">
            <x-ui.section-heading
                eyebrow="Executive Pulse · HQ Global Scope"
                title="Network Financial & Branch Snapshot"
                description="Consolidated figures across all active Tagum City branches (Asia/Manila)."
            >
                <x-slot:actions>
                    <a href="{{ route('hq.reports.index') }}" class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium">
                        <x-ui.icon name="reports" class="h-4 w-4 text-gold-accent" />
                        <span>Cross-Branch Reports</span>
                    </a>
                    <a href="{{ route('hq.branches.index') }}" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs">
                        <x-ui.icon name="branches" class="h-4 w-4" />
                        <span>Manage Branches</span>
                    </a>
                </x-slot:actions>
            </x-ui.section-heading>

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

            {{-- Branch Share Strip --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($branchSnapshots as $branch)
                    <div class="sig-card rounded-2xl px-5 py-4 flex items-center justify-between gap-4">
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-[11px] font-semibold text-gold-accent">
                                    {{ $branch['code'] }}
                                </span>
                                <span class="truncate text-sm font-medium text-theme-primary">{{ $branch['name'] }}</span>
                            </div>
                            <p class="font-display text-2xl font-semibold tabular-nums text-theme-primary">
                                {{ $branch['revenue'] }}
                            </p>
                            <p class="text-xs text-theme-muted tabular-nums">
                                {{ $branch['tx'] }} txns · {{ $branch['share'] }} network share
                            </p>
                        </div>
                        <x-ui.low-stock-badge status="low" :label="$branch['alerts'].' alerts'" />
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Section 2: Analytics Charts --}}
        <section class="space-y-4">
            <x-ui.section-heading
                eyebrow="Revenue Analytics"
                title="Branch Comparison & Service Mix"
                description="Interactive Chart.js visualizations synchronized automatically with Dark Glam and Light Mode."
            />

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
                {{-- Cross-branch daily revenue bar chart --}}
                <x-ui.card
                    title="Daily Revenue Comparison"
                    subtitle="Peso gross sales by branch"
                    class="xl:col-span-3"
                    x-data="hqBranchRevenueChart(@js($chartConfig))"
                >
                    <x-slot:actions>
                        <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                            <button
                                type="button"
                                x-on:click="setRange('days7')"
                                :class="range === 'days7' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                                class="rounded-lg px-2.5 py-1 font-medium cursor-pointer transition"
                            >
                                7 Days
                            </button>
                            <button
                                type="button"
                                x-on:click="setRange('days14')"
                                :class="range === 'days14' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                                class="rounded-lg px-2.5 py-1 font-medium cursor-pointer transition"
                            >
                                14 Days
                            </button>
                        </div>
                    </x-slot:actions>

                    <div class="relative h-72 w-full">
                        <canvas x-ref="canvas" aria-label="Cross-branch daily revenue chart"></canvas>
                    </div>
                </x-ui.card>

                {{-- Service Mix Doughnut Chart --}}
                <x-ui.card title="Service Revenue Split" subtitle="Top master catalog groups" class="xl:col-span-2">
                    <div x-data="hqCategoryChart(@js($chartConfig['categories']))" class="space-y-5">
                        <div class="relative mx-auto h-52 w-52">
                            <canvas x-ref="canvas" aria-label="Revenue by service category"></canvas>
                        </div>

                        <div class="space-y-2 border-t border-theme pt-4">
                            @foreach ($categorySplits as $split)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-theme-secondary">{{ $split['name'] }}</span>
                                    <div class="flex items-center gap-3 tabular-nums">
                                        <span class="text-theme-muted">{{ $split['pct'] }}</span>
                                        <span class="font-semibold text-theme-primary">{{ $split['formatted'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- Section 3: Watchlist & Recent Ledger --}}
        <section class="space-y-4">
            <x-ui.section-heading
                eyebrow="Operational Governance"
                title="Stock Watchlist & Live Network Transactions"
                description="Branch inventory alerts use strict 9px boxes; completed and voided checkouts appear network-wide."
            />

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
                {{-- Low-Stock Watchlist --}}
                <x-ui.card title="Low-Stock Watchlist" subtitle="Products at or below threshold" class="xl:col-span-5">
                    <x-slot:actions>
                        <a href="{{ route('hq.products.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            Master Products →
                        </a>
                    </x-slot:actions>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-theme">
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Branch</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Product</th>
                                    <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">On Hand</th>
                                    <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-theme">
                                @foreach ($lowStockWatchlist as $item)
                                    <tr class="hover:bg-theme-elevated/45 transition">
                                        <td class="py-3 pr-3">
                                            <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-xs font-semibold text-gold-accent">
                                                {{ $item['branch'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-3">
                                            <p class="font-medium text-theme-primary">{{ $item['product'] }}</p>
                                            <p class="text-[11px] text-theme-muted tabular-nums">Min: {{ $item['threshold'] }} {{ $item['unit'] }}</p>
                                        </td>
                                        <td class="py-3 pr-3 text-right tabular-nums text-theme-secondary">
                                            {{ $item['qty'] }}
                                        </td>
                                        <td class="py-3 text-right">
                                            <x-ui.low-stock-badge :status="$item['status']" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>

                {{-- Recent Transactions --}}
                <x-ui.card title="Recent Transactions" subtitle="Latest 10 across TCC, APK & VSV" class="xl:col-span-7">
                    <x-slot:actions>
                        <a href="{{ route('hq.audit.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            Audit Trail →
                        </a>
                    </x-slot:actions>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-theme">
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Reference</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Branch</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Services &amp; Tech</th>
                                    <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Total</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Status</th>
                                    <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-theme">
                                @foreach ($recentTransactions as $tx)
                                    <tr class="hover:bg-theme-elevated/45 transition">
                                        <td class="py-3 pr-3 font-mono text-xs text-theme-muted whitespace-nowrap">{{ $tx['ref'] }}</td>
                                        <td class="py-3 pr-3">
                                            <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-xs font-semibold text-gold-accent">
                                                {{ $tx['branch'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-3">
                                            <p class="max-w-[200px] truncate font-medium text-theme-primary">{{ $tx['services'] }}</p>
                                            <p class="text-[11px] text-theme-muted">{{ $tx['technician'] }}</p>
                                        </td>
                                        <td class="py-3 pr-3 text-right font-semibold tabular-nums text-theme-primary whitespace-nowrap">
                                            {{ $tx['amount'] }}
                                        </td>
                                        <td class="py-3 pl-4">
                                            <x-ui.status-badge :status="$tx['status']" />
                                        </td>
                                        <td class="py-3 text-right tabular-nums text-xs text-theme-muted whitespace-nowrap">
                                            {{ $tx['time'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </div>

    @pushOnce('scripts')
        <script>
            window.hqBranchRevenueChart = function (config) {
                let chart = null;
                let themeListener = null;

                return {
                    range: 'days7',
                    init() {
                        const renderChart = () => {
                            if (!this.$refs.canvas || typeof window.Chart === 'undefined') {
                                return;
                            }

                            const p = window.signatureThemePalette ? window.signatureThemePalette() : {};
                            const set = config[this.range];

                            if (chart) {
                                chart.destroy();
                            }

                            chart = new window.Chart(this.$refs.canvas, {
                                type: 'bar',
                                data: {
                                    labels: set.labels,
                                    datasets: [
                                        { label: 'TCC · Center', data: set.tcc, backgroundColor: p.gold, borderRadius: 6, borderSkipped: false },
                                        { label: 'APK · Apokon', data: set.apk, backgroundColor: p.champagne, borderRadius: 6, borderSkipped: false },
                                        { label: 'VSV · Visayan', data: set.vsv, backgroundColor: p.emerald, borderRadius: 6, borderSkipped: false },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: p.legend,
                                                boxWidth: 10,
                                                usePointStyle: true,
                                                pointStyle: 'circle',
                                                font: { family: 'Inter', size: 11 },
                                            },
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => ` ${ctx.dataset.label}: ₱ ${Number(ctx.parsed.y).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`,
                                            },
                                        },
                                    },
                                    scales: {
                                        x: {
                                            ticks: { color: p.tick, font: { family: 'Inter', size: 11 } },
                                            grid: { display: false },
                                        },
                                        y: {
                                            ticks: {
                                                color: p.tick,
                                                font: { family: 'Inter', size: 11 },
                                                callback: (v) => `₱${(v / 1000).toFixed(0)}k`,
                                            },
                                            grid: { color: p.grid },
                                        },
                                    },
                                },
                            });
                        };

                        renderChart();
                        themeListener = () => renderChart();
                        window.addEventListener('signature:theme-changed', themeListener);
                    },
                    setRange(nextRange) {
                        this.range = nextRange;
                        const p = window.signatureThemePalette ? window.signatureThemePalette() : {};
                        const set = config[this.range];
                        if (chart && set) {
                            chart.data.labels = set.labels;
                            chart.data.datasets[0].data = set.tcc;
                            chart.data.datasets[1].data = set.apk;
                            chart.data.datasets[2].data = set.vsv;
                            chart.update();
                        }
                    },
                    destroy() {
                        if (themeListener) {
                            window.removeEventListener('signature:theme-changed', themeListener);
                        }
                        if (chart) {
                            chart.destroy();
                        }
                    },
                };
            };

            window.hqCategoryChart = function (categoryData) {
                let chart = null;
                let themeListener = null;

                return {
                    init() {
                        const renderChart = () => {
                            if (!this.$refs.canvas || typeof window.Chart === 'undefined') {
                                return;
                            }

                            const p = window.signatureThemePalette ? window.signatureThemePalette() : {};

                            if (chart) {
                                chart.destroy();
                            }

                            chart = new window.Chart(this.$refs.canvas, {
                                type: 'doughnut',
                                data: {
                                    labels: categoryData.labels,
                                    datasets: [
                                        {
                                            data: categoryData.values,
                                            backgroundColor: [p.gold, p.champagne, p.emerald, '#9d7636', '#7b756b'],
                                            borderColor: p.surface,
                                            borderWidth: 3,
                                            hoverOffset: 6,
                                        },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '66%',
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => ` ${ctx.label}: ₱ ${Number(ctx.parsed).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`,
                                            },
                                        },
                                    },
                                },
                            });
                        };

                        renderChart();
                        themeListener = () => renderChart();
                        window.addEventListener('signature:theme-changed', themeListener);
                    },
                    destroy() {
                        if (themeListener) {
                            window.removeEventListener('signature:theme-changed', themeListener);
                        }
                        if (chart) {
                            chart.destroy();
                        }
                    },
                };
            };
        </script>
    @endPushOnce
</x-layouts.app>
