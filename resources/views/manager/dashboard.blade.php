<x-layouts.app title="Branch Dashboard" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $kpis = [
            ['label' => 'Sales (Today)', 'value' => '₱ 8,150.00', 'hint' => '14 checkouts', 'icon' => 'cash', 'badge' => '+9.2%', 'tone' => 'emerald'],
            ['label' => 'Revenue (MTD)', 'value' => '₱ 121,450.00', 'hint' => '42.6% of network', 'icon' => 'reports', 'badge' => 'On Track', 'tone' => 'gold'],
            ['label' => 'Low Stock Alerts', 'value' => '3', 'hint' => '2 low · 1 critical', 'icon' => 'alert', 'badge' => 'Restock Due', 'tone' => 'amber'],
            ['label' => 'Unpaid Commissions', 'value' => '₱ 4,840.00', 'hint' => '4 technicians', 'icon' => 'technicians', 'badge' => 'Pending', 'tone' => 'amber'],
        ];

        $lowStockItems = [
            ['product' => 'Rebond Cream', 'qty' => '2.00', 'min' => '2.00', 'unit' => 'jar', 'status' => 'low'],
            ['product' => 'Hair Color Tube', 'qty' => '7.00', 'min' => '10.00', 'unit' => 'tube', 'status' => 'low'],
            ['product' => 'Nail Polish Remover', 'qty' => '0.00', 'min' => '3.00', 'unit' => 'bottle', 'status' => 'out'],
            ['product' => 'Cuticle Oil', 'qty' => '6.00', 'min' => '4.00', 'unit' => 'bottle', 'status' => 'ok'],
        ];

        $recentTransactions = [
            ['ref' => 'TX-TCC-041', 'time' => '11:42 AM', 'cashier' => 'Ana Reyes', 'services' => 'Rebond, Hair Serum Care', 'amount' => '₱ 2,850.00', 'status' => 'completed'],
            ['ref' => 'TX-TCC-040', 'time' => '11:15 AM', 'cashier' => 'Ana Reyes', 'services' => 'Gel Polish', 'amount' => '₱ 450.00', 'status' => 'voided'],
            ['ref' => 'TX-TCC-039', 'time' => '10:34 AM', 'cashier' => 'Ana Reyes', 'services' => 'Haircut (Women)', 'amount' => '₱ 350.00', 'status' => 'completed'],
            ['ref' => 'TX-TCC-038', 'time' => '09:50 AM', 'cashier' => 'Ana Reyes', 'services' => 'Hair Color (Full)', 'amount' => '₱ 1,800.00', 'status' => 'completed'],
            ['ref' => 'TX-TCC-037', 'time' => '09:18 AM', 'cashier' => 'Ana Reyes', 'services' => 'Foot Spa, Pedicure', 'amount' => '₱ 800.00', 'status' => 'completed'],
        ];

        $topTechnicians = [
            ['name' => 'Maria Santos', 'rate' => '15%', 'items' => 24, 'unpaid' => '₱ 2,280.00'],
            ['name' => 'Jun Reyes', 'rate' => '12%', 'items' => 19, 'unpaid' => '₱ 1,420.00'],
            ['name' => 'Ana Dela Cruz', 'rate' => '10%', 'items' => 16, 'unpaid' => '₱ 720.00'],
            ['name' => 'Liza Mendoza', 'rate' => '10%', 'items' => 12, 'unpaid' => '₱ 420.00'],
        ];

        $chartConfig = [
            'days7' => [
                'labels' => ['Nov 20', 'Nov 21', 'Nov 22', 'Nov 23', 'Nov 24', 'Nov 25', 'Nov 26'],
                'revenue' => [12800, 15200, 11400, 16900, 14300, 17100, 13500],
            ],
            'days14' => [
                'labels' => ['Nov 13', 'Nov 15', 'Nov 17', 'Nov 19', 'Nov 21', 'Nov 23', 'Nov 26'],
                'revenue' => [11900, 13400, 14100, 12600, 15200, 16900, 13500],
            ],
            'categories' => [
                'labels' => ['Hair Color (Full)', 'Rebond', 'Gel Polish', 'Foot Spa', 'Haircut'],
                'values' => [37800, 32500, 22950, 16500, 11700],
            ],
        ];
    @endphp

    <div class="space-y-8">
        {{-- Group 1: Branch Snapshot --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Branch Pulse" title="Tagum City Center">
                <x-slot:actions>
                    <a href="{{ route('manager.inventory.index') }}" class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium">
                        <x-ui.icon name="inventory" class="h-4 w-4 text-gold-accent" />
                        <span>Restock</span>
                    </a>
                    <a href="{{ route('manager.technicians.index') }}" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs">
                        <x-ui.icon name="technicians" class="h-4 w-4" />
                        <span>Commissions</span>
                    </a>
                </x-slot:actions>
            </x-ui.section-heading>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($kpis as $kpi)
                    <x-ui.stat-card
                        :label="$kpi['label']"
                        :value="$kpi['value']"
                        :hint="$kpi['hint']"
                        :icon="$kpi['icon']"
                        :badge="$kpi['badge']"
                        :badgeTone="$kpi['tone']"
                    />
                @endforeach
            </div>
        </section>

        {{-- Group 2: Sales & Service Charts --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Performance" title="Branch Sales & Mix" />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
                <x-ui.card
                    title="Daily Revenue"
                    class="xl:col-span-3"
                    x-data="managerRevenueChart(@js($chartConfig))"
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
                        <canvas x-ref="canvas" aria-label="Branch daily revenue chart"></canvas>
                    </div>
                </x-ui.card>

                <x-ui.card title="Top Services" class="xl:col-span-2">
                    <div x-data="managerCategoryChart(@js($chartConfig['categories']))" class="space-y-4">
                        <div class="relative h-44 w-full">
                            <canvas x-ref="canvas" aria-label="Branch top services chart"></canvas>
                        </div>

                        <div class="space-y-2 border-t border-theme pt-3">
                            @foreach ($topTechnicians as $tech)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-theme-primary">{{ $tech['name'] }}</span>
                                    <div class="flex items-center gap-3 tabular-nums">
                                        <span class="text-theme-muted">{{ $tech['rate'] }}</span>
                                        <span class="font-semibold text-gold-accent">{{ $tech['unpaid'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- Group 3: Stock Watchlist & Recent Sales --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Operations" title="Stock & Recent Sales" />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                <x-ui.card title="Stock Watchlist" class="xl:col-span-5">
                    <x-slot:actions>
                        <a href="{{ route('manager.inventory.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            Manage →
                        </a>
                    </x-slot:actions>

                    <div class="space-y-2.5">
                        @foreach ($lowStockItems as $item)
                            <div class="sig-elevated flex flex-wrap items-center justify-between gap-3 rounded-xl px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-theme-primary">{{ $item['product'] }}</p>
                                    <p class="text-xs text-theme-muted tabular-nums">Min {{ $item['min'] }} {{ $item['unit'] }}</p>
                                </div>
                                <x-ui.low-stock-badge :status="$item['status']" :qty="$item['qty']" :unit="$item['unit']" />
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="Recent Transactions" class="xl:col-span-7">
                    <x-slot:actions>
                        <a href="{{ route('manager.transactions.index') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            View all →
                        </a>
                    </x-slot:actions>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-theme">
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Ref</th>
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Time</th>
                                    <th class="pb-2.5 text-left text-xs font-medium text-theme-muted">Services</th>
                                    <th class="pb-2.5 text-right text-xs font-medium text-theme-muted">Amount</th>
                                    <th class="pb-2.5 text-right text-xs font-medium text-theme-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-theme">
                                @foreach ($recentTransactions as $tx)
                                    <tr class="hover:bg-theme-elevated/45 transition">
                                        <td class="py-3 pr-3 font-mono text-xs text-theme-muted whitespace-nowrap">{{ $tx['ref'] }}</td>
                                        <td class="py-3 pr-3 text-xs text-theme-muted tabular-nums whitespace-nowrap">{{ $tx['time'] }}</td>
                                        <td class="py-3 pr-3 max-w-[200px] truncate text-theme-primary">{{ $tx['services'] }}</td>
                                        <td class="py-3 pr-3 text-right font-semibold tabular-nums text-theme-primary whitespace-nowrap">{{ $tx['amount'] }}</td>
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
            window.managerRevenueChart = function (config) {
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

                            if (chart) chart.destroy();

                            chart = new window.Chart(this.$refs.canvas, {
                                type: 'bar',
                                data: {
                                    labels: set.labels,
                                    datasets: [
                                        {
                                            label: 'Revenue (₱)',
                                            data: set.revenue,
                                            backgroundColor: p.gold,
                                            borderRadius: 6,
                                            borderSkipped: false,
                                        },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => ` ₱ ${Number(ctx.parsed.y).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`,
                                            },
                                        },
                                    },
                                    scales: {
                                        x: { ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 } }, grid: { display: false } },
                                        y: { ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 }, callback: (v) => `₱${(v / 1000).toFixed(0)}k` }, grid: { color: p.grid } },
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
                            chart.data.datasets[0].data = set.revenue;
                            chart.update();
                        }
                    },
                    destroy() {
                        if (themeListener) window.removeEventListener('signature:theme-changed', themeListener);
                        if (chart) chart.destroy();
                    },
                };
            };

            window.managerCategoryChart = function (categoryData) {
                let chart = null;
                let themeListener = null;

                return {
                    init() {
                        const renderChart = () => {
                            if (!this.$refs.canvas || typeof window.Chart === 'undefined') {
                                return;
                            }

                            const p = window.signatureThemePalette ? window.signatureThemePalette() : {};
                            if (chart) chart.destroy();

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
                                        },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '66%',
                                    plugins: {
                                        legend: {
                                            position: 'right',
                                            labels: { color: p.legend, boxWidth: 10, usePointStyle: true, font: { family: 'DM Sans', size: 11 } },
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => ` ${ctx.label}: ₱ ${Number(ctx.parsed).toLocaleString('en-PH')}`,
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
                        if (themeListener) window.removeEventListener('signature:theme-changed', themeListener);
                        if (chart) chart.destroy();
                    },
                };
            };
        </script>
    @endPushOnce
</x-layouts.app>
