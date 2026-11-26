<x-layouts.app title="Executive Dashboard" portal="hq">
    @php
        $kpis = [
            ['label' => 'Revenue (MTD)', 'value' => '₱ 284,750.00', 'icon' => 'cash', 'badge' => '+12.4%', 'tone' => 'emerald'],
            ['label' => 'Transactions', 'value' => '1,342', 'icon' => 'transactions', 'badge' => '+8.1%', 'tone' => 'emerald'],
            ['label' => 'Branches', 'value' => '3', 'icon' => 'branches', 'badge' => 'Online', 'tone' => 'gold'],
            ['label' => 'Stock Alerts', 'value' => '6', 'icon' => 'alert', 'badge' => '2 Out', 'tone' => 'rose'],
        ];

        $branchSnapshots = [
            ['code' => 'TCC', 'name' => 'Tagum City Center', 'revenue' => '₱ 121,450.00', 'share' => '42.6%', 'alerts' => 2],
            ['code' => 'APK', 'name' => 'Apokon', 'revenue' => '₱ 91,800.00', 'share' => '32.2%', 'alerts' => 2],
            ['code' => 'VSV', 'name' => 'Visayan Village', 'revenue' => '₱ 71,500.00', 'share' => '25.2%', 'alerts' => 2],
        ];

        $lowStockWatchlist = [
            ['branch' => 'APK', 'product' => 'Nail Polish Remover', 'qty' => '0.00', 'status' => 'out'],
            ['branch' => 'VSV', 'product' => 'Gel Polish', 'qty' => '0.00', 'status' => 'out'],
            ['branch' => 'TCC', 'product' => 'Rebond Cream', 'qty' => '2.00', 'status' => 'low'],
            ['branch' => 'TCC', 'product' => 'Hair Color Tube', 'qty' => '7.00', 'status' => 'low'],
            ['branch' => 'APK', 'product' => 'Cuticle Oil', 'qty' => '3.00', 'status' => 'low'],
        ];

        $recentTransactions = [
            ['ref' => 'TX-TCC-041', 'branch' => 'TCC', 'service' => 'Rebond, Hair Serum Care', 'amount' => '₱ 2,850.00', 'status' => 'completed'],
            ['ref' => 'TX-APK-028', 'branch' => 'APK', 'service' => 'Gel Polish, Pedicure', 'amount' => '₱ 750.00', 'status' => 'completed'],
            ['ref' => 'TX-VSV-019', 'branch' => 'VSV', 'service' => 'Hair Color (Full)', 'amount' => '₱ 1,800.00', 'status' => 'completed'],
            ['ref' => 'TX-APK-027', 'branch' => 'APK', 'service' => 'Foot Spa, Manicure', 'amount' => '₱ 750.00', 'status' => 'voided'],
            ['ref' => 'TX-TCC-039', 'branch' => 'TCC', 'service' => 'Haircut (Women)', 'amount' => '₱ 350.00', 'status' => 'completed'],
        ];

        $categorySplits = [
            ['name' => 'Hair Services', 'formatted' => '₱ 176,400.00', 'pct' => '61.9%'],
            ['name' => 'Nail & Foot Spa', 'formatted' => '₱ 108,350.00', 'pct' => '38.1%'],
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
                'labels' => ['Hair Color (Full)', 'Rebond', 'Haircut', 'Gel Polish & Nail', 'Foot Spa'],
                'values' => [84600, 62500, 29300, 64850, 43500],
            ],
        ];
    @endphp

    <div class="space-y-8">
        {{-- Group 1: Overview Metrics + Unified Branch Strip --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Overview" title="Network Snapshot">
                <x-slot:actions>
                    <a href="{{ route('hq.reports.index') }}" class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium">
                        <x-ui.icon name="reports" class="h-4 w-4 text-gold-accent" />
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('hq.branches.index') }}" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs">
                        <x-ui.icon name="branches" class="h-4 w-4" />
                        <span>Branches</span>
                    </a>
                </x-slot:actions>
            </x-ui.section-heading>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($kpis as $kpi)
                    <x-ui.stat-card
                        :label="$kpi['label']"
                        :value="$kpi['value']"
                        :icon="$kpi['icon']"
                        :badge="$kpi['badge']"
                        :badgeTone="$kpi['tone']"
                    />
                @endforeach
            </div>

            {{-- Unified 3-Column Branch Summary Card --}}
            <div class="sig-card grid grid-cols-1 divide-y divide-theme rounded-2xl md:grid-cols-3 md:divide-x md:divide-y-0">
                @foreach ($branchSnapshots as $branch)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-[11px] font-semibold text-gold-accent">
                                    {{ $branch['code'] }}
                                </span>
                                <span class="truncate text-xs font-medium text-theme-secondary">{{ $branch['name'] }}</span>
                            </div>
                            <div class="flex items-baseline gap-2.5">
                                <p class="font-display text-xl font-semibold tabular-nums text-theme-primary">
                                    {{ $branch['revenue'] }}
                                </p>
                                <span class="text-xs text-theme-muted tabular-nums">{{ $branch['share'] }}</span>
                            </div>
                        </div>
                        <x-ui.low-stock-badge status="low" :label="$branch['alerts'].' alerts'" />
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Group 2: Revenue Analytics --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Analytics" title="Sales &amp; Service Mix" />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
                <x-ui.card
                    title="Daily Revenue"
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
                                7D
                            </button>
                            <button
                                type="button"
                                x-on:click="setRange('days14')"
                                :class="range === 'days14' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                                class="rounded-lg px-2.5 py-1 font-medium cursor-pointer transition"
                            >
                                14D
                            </button>
                        </div>
                    </x-slot:actions>

                    <div class="relative h-64 w-full">
                        <canvas x-ref="canvas" aria-label="Cross-branch daily revenue chart"></canvas>
                    </div>
                </x-ui.card>

                <x-ui.card title="Category Split" class="xl:col-span-2">
                    <div x-data="hqCategoryChart(@js($chartConfig['categories']))" class="space-y-4">
                        <div class="relative mx-auto h-48 w-48">
                            <canvas x-ref="canvas" aria-label="Revenue by service category"></canvas>
                        </div>

                        <div class="space-y-2 border-t border-theme pt-3">
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

        {{-- Group 3: Operations & Activity --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Operations" title="Alerts &amp; Recent Activity" />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                <x-ui.card title="Low-Stock Watchlist" class="xl:col-span-5">
                    <x-slot:actions>
                        <a href="{{ route('hq.products.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            View all →
                        </a>
                    </x-slot:actions>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-theme">
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Branch</th>
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Product</th>
                                    <th class="pb-2.5 text-right text-xs font-medium text-theme-muted">Qty</th>
                                    <th class="pb-2.5 text-right text-xs font-medium text-theme-muted">Status</th>
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
                                        <td class="py-3 pr-3 font-medium text-theme-primary">{{ $item['product'] }}</td>
                                        <td class="py-3 pr-3 text-right tabular-nums text-theme-secondary">{{ $item['qty'] }}</td>
                                        <td class="py-3 text-right">
                                            <x-ui.low-stock-badge :status="$item['status']" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>

                <x-ui.card title="Recent Transactions" class="xl:col-span-7">
                    <x-slot:actions>
                        <a href="{{ route('hq.reports.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            View all →
                        </a>
                    </x-slot:actions>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-theme">
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Ref</th>
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Branch</th>
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Services</th>
                                    <th class="pb-2.5 text-right text-xs font-medium text-theme-muted">Amount</th>
                                    <th class="pb-2.5 text-right text-xs font-medium text-theme-muted">Status</th>
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
                                        <td class="py-3 pr-3 max-w-[200px] truncate text-theme-primary">{{ $tx['service'] }}</td>
                                        <td class="py-3 pr-3 text-right font-semibold tabular-nums text-theme-primary whitespace-nowrap">
                                            {{ $tx['amount'] }}
                                        </td>
                                        <td class="py-3 text-right">
                                            <x-ui.status-badge :status="$tx['status']" />
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
                                        { label: 'TCC', data: set.tcc, backgroundColor: p.gold, borderRadius: 6, borderSkipped: false },
                                        { label: 'APK', data: set.apk, backgroundColor: p.champagne, borderRadius: 6, borderSkipped: false },
                                        { label: 'VSV', data: set.vsv, backgroundColor: p.emerald, borderRadius: 6, borderSkipped: false },
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
                                                font: { family: 'DM Sans', size: 11 },
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
                                            ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 } },
                                            grid: { display: false },
                                        },
                                        y: {
                                            ticks: {
                                                color: p.tick,
                                                font: { family: 'DM Sans', size: 11 },
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
