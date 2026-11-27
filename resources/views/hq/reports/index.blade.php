<x-layouts.app title="Cross-Branch Reports" portal="hq">
    @php
        $reportPresets = [
            'mtd' => [
                'label' => 'Nov 1–26, 2026',
                'kpis' => [
                    'gross' => '₱ 298,400.00',
                    'discounts' => '₱ 13,650.00',
                    'net' => '₱ 284,750.00',
                    'commissions' => '₱ 35,840.00',
                ],
                'branchChart' => [
                    'labels' => ['TCC · Center', 'APK · Apokon', 'VSV · Visayan Village'],
                    'gross' => [127200, 96400, 74800],
                    'net' => [121450, 91800, 71500],
                    'commissions' => [15650, 11420, 8770],
                ],
                'topServices' => [
                    ['name' => 'Hair Color (Full)', 'category' => 'Hair', 'qty' => 47, 'revenue' => 84600, 'formatted' => '₱ 84,600.00', 'share' => '29.7%'],
                    ['name' => 'Gel Polish', 'category' => 'Nail', 'qty' => 144, 'revenue' => 64800, 'formatted' => '₱ 64,800.00', 'share' => '22.8%'],
                    ['name' => 'Rebond', 'category' => 'Hair', 'qty' => 25, 'revenue' => 62500, 'formatted' => '₱ 62,500.00', 'share' => '21.9%'],
                    ['name' => 'Foot Spa', 'category' => 'Nail', 'qty' => 87, 'revenue' => 43500, 'formatted' => '₱ 43,500.00', 'share' => '15.3%'],
                    ['name' => 'Haircut (Women)', 'category' => 'Hair', 'qty' => 84, 'revenue' => 29350, 'formatted' => '₱ 29,350.00', 'share' => '10.3%'],
                ],
                'commissions' => [
                    ['name' => 'Maria Santos (TCC)', 'rate' => '15%', 'items' => 68, 'paid' => 5400, 'unpaid' => 2280, 'total_fmt' => '₱ 7,680.00', 'status' => 'unpaid'],
                    ['name' => 'Maria Santos (APK)', 'rate' => '15%', 'items' => 51, 'paid' => 4800, 'unpaid' => 980, 'total_fmt' => '₱ 5,780.00', 'status' => 'unpaid'],
                    ['name' => 'Jun Reyes (TCC)', 'rate' => '12%', 'items' => 54, 'paid' => 3900, 'unpaid' => 1120, 'total_fmt' => '₱ 5,020.00', 'status' => 'unpaid'],
                    ['name' => 'Ana Dela Cruz (APK)', 'rate' => '10%', 'items' => 62, 'paid' => 3150, 'unpaid' => 0, 'total_fmt' => '₱ 3,150.00', 'status' => 'paid'],
                    ['name' => 'Liza Mendoza (VSV)', 'rate' => '10%', 'items' => 49, 'paid' => 2640, 'unpaid' => 420, 'total_fmt' => '₱ 3,060.00', 'status' => 'unpaid'],
                ],
            ],
            'week' => [
                'label' => 'Nov 20–26, 2026',
                'kpis' => [
                    'gross' => '₱ 108,200.00',
                    'discounts' => '₱ 4,850.00',
                    'net' => '₱ 103,350.00',
                    'commissions' => '₱ 12,910.00',
                ],
                'branchChart' => [
                    'labels' => ['TCC · Center', 'APK · Apokon', 'VSV · Visayan Village'],
                    'gross' => [46200, 34900, 27100],
                    'net' => [44100, 33300, 25950],
                    'commissions' => [5620, 4120, 3170],
                ],
                'topServices' => [
                    ['name' => 'Hair Color (Full)', 'category' => 'Hair', 'qty' => 18, 'revenue' => 32400, 'formatted' => '₱ 32,400.00', 'share' => '31.3%'],
                    ['name' => 'Gel Polish', 'category' => 'Nail', 'qty' => 52, 'revenue' => 23400, 'formatted' => '₱ 23,400.00', 'share' => '22.6%'],
                    ['name' => 'Rebond', 'category' => 'Hair', 'qty' => 9, 'revenue' => 22500, 'formatted' => '₱ 22,500.00', 'share' => '21.8%'],
                    ['name' => 'Foot Spa', 'category' => 'Nail', 'qty' => 31, 'revenue' => 15500, 'formatted' => '₱ 15,500.00', 'share' => '15.0%'],
                    ['name' => 'Haircut (Women)', 'category' => 'Hair', 'qty' => 27, 'revenue' => 9550, 'formatted' => '₱ 9,550.00', 'share' => '9.3%'],
                ],
                'commissions' => [
                    ['name' => 'Maria Santos (TCC)', 'rate' => '15%', 'items' => 24, 'paid' => 1200, 'unpaid' => 1650, 'total_fmt' => '₱ 2,850.00', 'status' => 'unpaid'],
                    ['name' => 'Maria Santos (APK)', 'rate' => '15%', 'items' => 18, 'paid' => 1100, 'unpaid' => 980, 'total_fmt' => '₱ 2,080.00', 'status' => 'unpaid'],
                    ['name' => 'Jun Reyes (TCC)', 'rate' => '12%', 'items' => 19, 'paid' => 960, 'unpaid' => 840, 'total_fmt' => '₱ 1,800.00', 'status' => 'unpaid'],
                    ['name' => 'Ana Dela Cruz (APK)', 'rate' => '10%', 'items' => 22, 'paid' => 1120, 'unpaid' => 0, 'total_fmt' => '₱ 1,120.00', 'status' => 'paid'],
                    ['name' => 'Liza Mendoza (VSV)', 'rate' => '10%', 'items' => 17, 'paid' => 760, 'unpaid' => 420, 'total_fmt' => '₱ 1,180.00', 'status' => 'unpaid'],
                ],
            ],
            'today' => [
                'label' => 'Nov 26, 2026',
                'kpis' => [
                    'gross' => '₱ 18,450.00',
                    'discounts' => '₱ 700.00',
                    'net' => '₱ 17,750.00',
                    'commissions' => '₱ 2,195.00',
                ],
                'branchChart' => [
                    'labels' => ['TCC · Center', 'APK · Apokon', 'VSV · Visayan Village'],
                    'gross' => [8150, 5850, 4450],
                    'net' => [7850, 5600, 4300],
                    'commissions' => [980, 695, 520],
                ],
                'topServices' => [
                    ['name' => 'Hair Color (Full)', 'category' => 'Hair', 'qty' => 3, 'revenue' => 5400, 'formatted' => '₱ 5,400.00', 'share' => '30.4%'],
                    ['name' => 'Rebond', 'category' => 'Hair', 'qty' => 2, 'revenue' => 5000, 'formatted' => '₱ 5,000.00', 'share' => '28.2%'],
                    ['name' => 'Gel Polish', 'category' => 'Nail', 'qty' => 8, 'revenue' => 3600, 'formatted' => '₱ 3,600.00', 'share' => '20.3%'],
                    ['name' => 'Foot Spa', 'category' => 'Nail', 'qty' => 4, 'revenue' => 2000, 'formatted' => '₱ 2,000.00', 'share' => '11.3%'],
                    ['name' => 'Haircut (Women)', 'category' => 'Hair', 'qty' => 5, 'revenue' => 1750, 'formatted' => '₱ 1,750.00', 'share' => '9.8%'],
                ],
                'commissions' => [
                    ['name' => 'Maria Santos (TCC)', 'rate' => '15%', 'items' => 4, 'paid' => 0, 'unpaid' => 580, 'total_fmt' => '₱ 580.00', 'status' => 'unpaid'],
                    ['name' => 'Maria Santos (APK)', 'rate' => '15%', 'items' => 3, 'paid' => 0, 'unpaid' => 420, 'total_fmt' => '₱ 420.00', 'status' => 'unpaid'],
                    ['name' => 'Jun Reyes (TCC)', 'rate' => '12%', 'items' => 3, 'paid' => 0, 'unpaid' => 360, 'total_fmt' => '₱ 360.00', 'status' => 'unpaid'],
                    ['name' => 'Ana Dela Cruz (APK)', 'rate' => '10%', 'items' => 4, 'paid' => 275, 'unpaid' => 0, 'total_fmt' => '₱ 275.00', 'status' => 'paid'],
                    ['name' => 'Liza Mendoza (VSV)', 'rate' => '10%', 'items' => 3, 'paid' => 0, 'unpaid' => 290, 'total_fmt' => '₱ 290.00', 'status' => 'unpaid'],
                ],
            ],
        ];
    @endphp

    <div
        x-data="hqReportsWorkspace(@js($reportPresets))"
        class="space-y-8"
    >
        {{-- Group 1: Filter Bar & Financial Summary --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Summary" title="Sales & Commissions">
                <x-slot:actions>
                    <button
                        type="button"
                        x-on:click="exportNotice = `CSV exported for ${activePreset.label}.`"
                        class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium cursor-pointer"
                    >
                        <x-ui.icon name="download" class="h-4 w-4 text-gold-accent" />
                        <span>Export CSV</span>
                    </button>
                </x-slot:actions>
            </x-ui.section-heading>

            <div class="sig-card rounded-2xl p-3.5 flex flex-wrap items-center justify-between gap-3">
                <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                    <button
                        type="button"
                        x-on:click="setPreset('today')"
                        :class="preset === 'today' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                        class="rounded-lg px-3 py-1.5 font-medium cursor-pointer transition"
                    >
                        Today
                    </button>
                    <button
                        type="button"
                        x-on:click="setPreset('week')"
                        :class="preset === 'week' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                        class="rounded-lg px-3 py-1.5 font-medium cursor-pointer transition"
                    >
                        7 Days
                    </button>
                    <button
                        type="button"
                        x-on:click="setPreset('mtd')"
                        :class="preset === 'mtd' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                        class="rounded-lg px-3 py-1.5 font-medium cursor-pointer transition"
                    >
                        Month to Date
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <input type="date" x-model="dateFrom" class="sig-input rounded-xl px-3 py-1.5 text-xs tabular-nums" aria-label="Start date">
                    <span class="text-theme-muted">to</span>
                    <input type="date" x-model="dateTo" class="sig-input rounded-xl px-3 py-1.5 text-xs tabular-nums" aria-label="End date">
                </div>
            </div>

            <x-ui.notice model="exportNotice" />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <x-ui.stat-card label="Gross Sales" valueBind="activePreset.kpis.gross" hintBind="activePreset.label" icon="cash" />
                <x-ui.stat-card label="Discounts" valueBind="activePreset.kpis.discounts" hint="Senior · PWD · Promo" icon="discounts" />
                <x-ui.stat-card label="Net Revenue" valueBind="activePreset.kpis.net" valueTone="gold" hint="Cash collected" icon="sparkle" />
                <x-ui.stat-card label="Commissions" valueBind="activePreset.kpis.commissions" hint="Technician earnings" icon="technicians" />
            </div>
        </section>

        {{-- Group 2: Charts --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Performance" title="Branch & Service Charts" />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                <x-ui.card title="Revenue by Branch">
                    <div class="relative h-64 w-full">
                        <canvas x-ref="branchCanvas" aria-label="Branch revenue comparison chart"></canvas>
                    </div>
                </x-ui.card>

                <x-ui.card title="Top Services">
                    <div class="relative h-64 w-full">
                        <canvas x-ref="serviceCanvas" aria-label="Top services horizontal bar chart"></canvas>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- Group 3: Commissions & Breakdown Tables --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Breakdowns" title="Technician Commissions & Services" />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                <x-ui.card title="Commission Payouts" class="xl:col-span-5">
                    <div class="relative h-64 w-full">
                        <canvas x-ref="commissionCanvas" aria-label="Commission payouts stacked bar chart"></canvas>
                    </div>
                </x-ui.card>

                <x-ui.card title="Commission Ledger" class="xl:col-span-7">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-theme">
                                    <th class="pb-3 text-left text-xs font-medium text-theme-muted">Technician</th>
                                    <th class="pb-3 text-right text-xs font-medium text-theme-muted">Rate</th>
                                    <th class="pb-3 text-right text-xs font-medium text-theme-muted">Items</th>
                                    <th class="pb-3 text-right text-xs font-medium text-theme-muted">Earned</th>
                                    <th class="pb-3 text-right text-xs font-medium text-theme-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-theme">
                                <template x-for="row in activePreset.commissions" :key="row.name">
                                    <tr class="hover:bg-theme-elevated/45 transition">
                                        <td class="py-3 pr-3 font-medium text-theme-primary" x-text="row.name"></td>
                                        <td class="py-3 pr-3 text-right font-mono text-xs tabular-nums text-theme-secondary" x-text="row.rate"></td>
                                        <td class="py-3 pr-3 text-right tabular-nums text-theme-secondary" x-text="row.items"></td>
                                        <td class="py-3 pr-3 text-right font-semibold tabular-nums text-gold-accent" x-text="row.total_fmt"></td>
                                        <td class="py-3 text-right">
                                            <span
                                                class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium capitalize"
                                                :class="row.status === 'paid'
                                                    ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                                    : 'border-amber-500/40 bg-amber-500/12 text-amber-800 dark:text-amber-300'"
                                                x-text="row.status"
                                            ></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>
            </div>

            <x-ui.card title="Top Services Breakdown">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-theme">
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Service</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Category</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Sold</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Share</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Net Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-theme">
                            <template x-for="svc in activePreset.topServices" :key="svc.name">
                                <tr class="hover:bg-theme-elevated/45 transition">
                                    <td class="py-3 pr-3 font-medium text-theme-primary" x-text="svc.name"></td>
                                    <td class="py-3 pr-3">
                                        <span class="rounded-md bg-theme-elevated px-2.5 py-0.5 text-xs text-theme-secondary" x-text="svc.category"></span>
                                    </td>
                                    <td class="py-3 pr-3 text-right tabular-nums text-theme-secondary" x-text="svc.qty"></td>
                                    <td class="py-3 pr-3 text-right tabular-nums text-theme-muted" x-text="svc.share"></td>
                                    <td class="py-3 text-right font-semibold tabular-nums text-theme-primary" x-text="svc.formatted"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </section>
    </div>

    @pushOnce('scripts')
        <script>
            window.hqReportsWorkspace = function (presets) {
                let branchChart = null;
                let serviceChart = null;
                let commissionChart = null;
                let themeListener = null;

                return {
                    preset: 'mtd',
                    dateFrom: '2026-11-01',
                    dateTo: '2026-11-26',
                    exportNotice: null,
                    get activePreset() {
                        return presets[this.preset];
                    },
                    init() {
                        this.renderAllCharts();
                        themeListener = () => this.renderAllCharts();
                        window.addEventListener('signature:theme-changed', themeListener);
                    },
                    setPreset(next) {
                        this.preset = next;
                        if (next === 'today') {
                            this.dateFrom = '2026-11-26';
                            this.dateTo = '2026-11-26';
                        } else if (next === 'week') {
                            this.dateFrom = '2026-11-20';
                            this.dateTo = '2026-11-26';
                        } else {
                            this.dateFrom = '2026-11-01';
                            this.dateTo = '2026-11-26';
                        }
                        this.renderAllCharts();
                    },
                    renderAllCharts() {
                        if (typeof window.Chart === 'undefined') {
                            return;
                        }

                        const p = window.signatureThemePalette ? window.signatureThemePalette() : {};
                        const data = this.activePreset;

                        if (branchChart) branchChart.destroy();
                        if (serviceChart) serviceChart.destroy();
                        if (commissionChart) commissionChart.destroy();

                        if (this.$refs.branchCanvas) {
                            branchChart = new window.Chart(this.$refs.branchCanvas, {
                                type: 'bar',
                                data: {
                                    labels: data.branchChart.labels,
                                    datasets: [
                                        { label: 'Gross', data: data.branchChart.gross, backgroundColor: p.champagne, borderRadius: 6 },
                                        { label: 'Net', data: data.branchChart.net, backgroundColor: p.gold, borderRadius: 6 },
                                        { label: 'Commissions', data: data.branchChart.commissions, backgroundColor: p.emerald, borderRadius: 6 },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { labels: { color: p.legend, boxWidth: 10, usePointStyle: true, font: { family: 'DM Sans', size: 11 } } },
                                        tooltip: { callbacks: { label: (ctx) => ` ${ctx.dataset.label}: ₱ ${Number(ctx.parsed.y).toLocaleString('en-PH')}` } },
                                    },
                                    scales: {
                                        x: { ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 } }, grid: { display: false } },
                                        y: { ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 }, callback: (v) => `₱${(v / 1000).toFixed(0)}k` }, grid: { color: p.grid } },
                                    },
                                },
                            });
                        }

                        if (this.$refs.serviceCanvas) {
                            serviceChart = new window.Chart(this.$refs.serviceCanvas, {
                                type: 'bar',
                                data: {
                                    labels: data.topServices.map((s) => s.name),
                                    datasets: [
                                        { label: 'Revenue (₱)', data: data.topServices.map((s) => s.revenue), backgroundColor: p.gold, borderRadius: 6 },
                                    ],
                                },
                                options: {
                                    indexAxis: 'y',
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: { callbacks: { label: (ctx) => ` ₱ ${Number(ctx.parsed.x).toLocaleString('en-PH')}` } },
                                    },
                                    scales: {
                                        x: { ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 }, callback: (v) => `₱${(v / 1000).toFixed(0)}k` }, grid: { color: p.grid } },
                                        y: { ticks: { color: p.legend, font: { family: 'DM Sans', size: 11 } }, grid: { display: false } },
                                    },
                                },
                            });
                        }

                        if (this.$refs.commissionCanvas) {
                            commissionChart = new window.Chart(this.$refs.commissionCanvas, {
                                type: 'bar',
                                data: {
                                    labels: data.commissions.map((c) => c.name),
                                    datasets: [
                                        { label: 'Paid', data: data.commissions.map((c) => c.paid), backgroundColor: p.emerald, borderRadius: 4 },
                                        { label: 'Unpaid', data: data.commissions.map((c) => c.unpaid), backgroundColor: p.gold, borderRadius: 4 },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { labels: { color: p.legend, boxWidth: 10, usePointStyle: true, font: { family: 'DM Sans', size: 11 } } },
                                        tooltip: { callbacks: { label: (ctx) => ` ${ctx.dataset.label}: ₱ ${Number(ctx.parsed.y).toLocaleString('en-PH')}` } },
                                    },
                                    scales: {
                                        x: { stacked: true, ticks: { color: p.tick, font: { family: 'DM Sans', size: 10 } }, grid: { display: false } },
                                        y: { stacked: true, ticks: { color: p.tick, font: { family: 'DM Sans', size: 11 }, callback: (v) => `₱${v.toLocaleString('en-PH')}` }, grid: { color: p.grid } },
                                    },
                                },
                            });
                        }
                    },
                    destroy() {
                        if (themeListener) {
                            window.removeEventListener('signature:theme-changed', themeListener);
                        }
                        if (branchChart) branchChart.destroy();
                        if (serviceChart) serviceChart.destroy();
                        if (commissionChart) commissionChart.destroy();
                    },
                };
            };
        </script>
    @endPushOnce
</x-layouts.app>
