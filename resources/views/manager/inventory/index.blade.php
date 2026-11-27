<x-layouts.app title="Branch Inventory" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $initialInventory = [
            ['id' => 1, 'product' => 'Hair Serum 100ml', 'unit' => 'bottle', 'qty_on_hand' => '18.00', 'low_stock_threshold' => '5.00'],
            ['id' => 2, 'product' => 'Hair Color Tube', 'unit' => 'tube', 'qty_on_hand' => '7.00', 'low_stock_threshold' => '10.00'],
            ['id' => 3, 'product' => 'Cuticle Oil', 'unit' => 'bottle', 'qty_on_hand' => '6.00', 'low_stock_threshold' => '4.00'],
            ['id' => 4, 'product' => 'Nail Polish Remover', 'unit' => 'bottle', 'qty_on_hand' => '0.00', 'low_stock_threshold' => '3.00'],
            ['id' => 5, 'product' => 'Gel Polish', 'unit' => 'bottle', 'qty_on_hand' => '14.00', 'low_stock_threshold' => '8.00'],
            ['id' => 6, 'product' => 'Rebond Cream', 'unit' => 'jar', 'qty_on_hand' => '2.00', 'low_stock_threshold' => '2.00'],
        ];

        $initialLogs = [
            ['id' => 101, 'time' => '2026-11-26 11:42', 'product' => 'Rebond Cream', 'change' => '-1.00', 'unit' => 'jar', 'reason' => 'sale', 'user' => 'Ana Reyes'],
            ['id' => 100, 'time' => '2026-11-26 09:50', 'product' => 'Hair Color Tube', 'change' => '-1.00', 'unit' => 'tube', 'reason' => 'sale', 'user' => 'Ana Reyes'],
            ['id' => 99, 'time' => '2026-11-25 14:10', 'product' => 'Hair Serum 100ml', 'change' => '+12.00', 'unit' => 'bottle', 'reason' => 'restock', 'user' => 'Tagum City Center Manager'],
            ['id' => 98, 'time' => '2026-11-23 11:09', 'product' => 'Gel Polish', 'change' => '+14.00', 'unit' => 'bottle', 'reason' => 'import', 'user' => 'Tagum City Center Manager'],
        ];
    @endphp

    <div
        x-data="{
            items: @js($initialInventory),
            logs: @js($initialLogs),
            tab: 'stock',
            search: '',
            stockFilter: 'all',
            notice: null,
            selectedItem: null,
            adjustForm: { mode: 'restock', qty: '1.00', threshold: '0.00', note: '' },
            stockStatus(item) {
                const qty = Number(item.qty_on_hand);
                const min = Number(item.low_stock_threshold);
                if (qty <= 0) return 'out';
                if (qty <= min) return 'low';
                return 'ok';
            },
            get filteredStock() {
                const q = this.search.trim().toLowerCase();
                return this.items.filter((item) => {
                    const st = this.stockStatus(item);
                    const matchQ = !q || item.product.toLowerCase().includes(q) || item.unit.toLowerCase().includes(q);
                    const matchFilter = this.stockFilter === 'all' || (this.stockFilter === 'alerts' ? st !== 'ok' : st === this.stockFilter);
                    return matchQ && matchFilter;
                });
            },
            get filteredLogs() {
                const q = this.search.trim().toLowerCase();
                return this.logs.filter((l) => !q || l.product.toLowerCase().includes(q) || l.reason.toLowerCase().includes(q));
            },
            get alertCount() {
                return this.items.filter((i) => this.stockStatus(i) !== 'ok').length;
            },
            get outCount() {
                return this.items.filter((i) => this.stockStatus(i) === 'out').length;
            },
            openStockModal(item, mode) {
                this.selectedItem = item;
                this.adjustForm = {
                    mode,
                    qty: mode === 'restock' ? '10.00' : '1.00',
                    threshold: item.low_stock_threshold,
                    note: '',
                };
                $dispatch('open-modal', 'stock-adjuster');
            },
            applyAdjustment() {
                if (!this.selectedItem) return;
                const delta = Number(this.adjustForm.qty || 0);
                if (delta <= 0 && this.adjustForm.mode !== 'threshold') return;

                let change = 0;
                let reason = 'restock';
                if (this.adjustForm.mode === 'restock') {
                    change = delta;
                    reason = 'restock';
                } else if (this.adjustForm.mode === 'deduct') {
                    change = -Math.min(delta, Number(this.selectedItem.qty_on_hand));
                    reason = 'adjustment';
                }
                this.selectedItem.qty_on_hand = Math.max(0, Number(this.selectedItem.qty_on_hand) + change).toFixed(2);
                this.selectedItem.low_stock_threshold = Math.max(0, Number(this.adjustForm.threshold || 0)).toFixed(2);

                if (change !== 0) {
                    this.logs.unshift({
                        id: Date.now(),
                        time: 'Just now',
                        product: this.selectedItem.product,
                        change: (change > 0 ? '+' : '') + change.toFixed(2),
                        unit: this.selectedItem.unit,
                        reason,
                        user: 'Tagum City Center Manager',
                    });
                }

                this.notice = `Updated ${this.selectedItem.product} stock.`;
                $dispatch('close-modal', 'stock-adjuster');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Inventory & Stock" title="Branch Inventory">
            <x-slot:actions>
                <a href="{{ route('manager.imports.index') }}" class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium">
                    <x-ui.icon name="import" class="h-4 w-4 text-gold-accent" />
                    <span>Import CSV</span>
                </a>
            </x-slot:actions>
        </x-ui.section-heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Tracked Products" valueBind="items.length" icon="products" />
            <x-ui.stat-card label="Low & Out Alerts" valueBind="alertCount" icon="alert" badge="Needs Attention" badgeTone="amber" />
            <x-ui.stat-card label="Out of Stock" valueBind="outCount" icon="inventory" badge="Critical" badgeTone="rose" />
        </div>

        <x-ui.notice />

        <x-ui.card>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                        <button
                            type="button"
                            x-on:click="tab = 'stock'"
                            :class="tab === 'stock' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                            class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                        >
                            Stock Levels
                        </button>
                        <button
                            type="button"
                            x-on:click="tab = 'ledger'"
                            :class="tab === 'ledger' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                            class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                        >
                            Movement Ledger
                        </button>
                    </div>

                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input type="search" x-model="search" placeholder="Search product…" class="sig-input rounded-xl py-1.5 pl-9 pr-3 text-xs w-52">
                    </div>

                    <select x-show="tab === 'stock'" x-model="stockFilter" class="sig-input rounded-xl px-3 py-1.5 text-xs">
                        <option value="all">All Stock</option>
                        <option value="alerts">Low & Out</option>
                        <option value="out">Out of Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="ok">Healthy</option>
                    </select>
                </div>

                <span class="text-xs text-theme-muted tabular-nums" x-text="tab === 'stock' ? filteredStock.length + ' items' : filteredLogs.length + ' entries'"></span>
            </div>

            <div x-show="tab === 'stock'" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Product</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Unit</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">On Hand</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Min Level</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="item in filteredStock" :key="item.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="item.product"></td>
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-muted" x-text="item.unit"></td>
                                <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-theme-primary" x-text="item.qty_on_hand"></td>
                                <td class="py-3.5 pr-4 text-right tabular-nums text-theme-muted" x-text="item.low_stock_threshold"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="sig-low-stock-badge inline-flex items-center gap-2 border px-2.5 py-1 text-xs font-semibold tracking-wide"
                                        :class="{
                                            'border-rose-500/40 bg-rose-500/12 text-rose-700 dark:text-rose-300': stockStatus(item) === 'out',
                                            'border-amber-500/45 bg-amber-500/14 text-amber-900 dark:text-amber-300': stockStatus(item) === 'low',
                                            'border-emerald-500/35 bg-emerald-500/10 text-emerald-800 dark:text-emerald-300': stockStatus(item) === 'ok',
                                        }"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full" :class="{
                                            'bg-rose-500': stockStatus(item) === 'out',
                                            'bg-amber-500': stockStatus(item) === 'low',
                                            'bg-emerald-500': stockStatus(item) === 'ok',
                                        }"></span>
                                        <span x-text="stockStatus(item) === 'out' ? 'Out of Stock' : (stockStatus(item) === 'low' ? 'Low Stock' : 'Healthy')"></span>
                                    </span>
                                </td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-3">
                                        <button type="button" x-on:click="openStockModal(item, 'restock')" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                            Restock
                                        </button>
                                        <button type="button" x-on:click="openStockModal(item, 'deduct')" class="text-xs font-medium text-theme-secondary hover:text-theme-primary hover:underline cursor-pointer">
                                            Adjust
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredStock.length === 0" x-cloak>
                            <td colspan="6" class="py-8 text-center text-xs text-theme-muted">No matching inventory items.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div x-show="tab === 'ledger'" x-cloak class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Time (PHT)</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Product</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Change</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Reason</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Staff</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="log in filteredLogs" :key="log.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="log.time"></td>
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="log.product"></td>
                                <td
                                    class="py-3.5 pr-4 text-right font-mono text-xs font-semibold tabular-nums whitespace-nowrap"
                                    :class="log.change.startsWith('+') ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500'"
                                    x-text="log.change + ' ' + log.unit"
                                ></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span class="rounded-md bg-theme-elevated px-2.5 py-0.5 text-xs capitalize text-theme-secondary" x-text="log.reason"></span>
                                </td>
                                <td class="py-3.5 text-theme-secondary" x-text="log.user"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="stock-adjuster" title="Update Stock" maxWidth="max-w-md">
            <form x-on:submit.prevent="applyAdjustment()" class="space-y-4">
                <div class="sig-elevated flex items-center justify-between rounded-xl px-4 py-3 text-xs">
                    <div>
                        <p class="font-semibold text-theme-primary" x-text="selectedItem?.product"></p>
                        <p class="text-theme-muted tabular-nums" x-text="'Current: ' + selectedItem?.qty_on_hand + ' ' + selectedItem?.unit"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button
                        type="button"
                        x-on:click="adjustForm.mode = 'restock'"
                        :class="adjustForm.mode === 'restock' ? 'bg-gold-soft text-gold-accent border-theme-strong' : 'border-theme text-theme-secondary'"
                        class="rounded-xl border py-2 font-medium cursor-pointer transition"
                    >
                        Restock (+)
                    </button>
                    <button
                        type="button"
                        x-on:click="adjustForm.mode = 'deduct'"
                        :class="adjustForm.mode === 'deduct' ? 'bg-rose-500/12 text-rose-500 border-rose-500/40' : 'border-theme text-theme-secondary'"
                        class="rounded-xl border py-2 font-medium cursor-pointer transition"
                    >
                        Deduct (-)
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Quantity</label>
                        <input type="number" step="0.01" min="0" x-model="adjustForm.qty" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Alert Threshold</label>
                        <input type="number" step="0.01" min="0" x-model="adjustForm.threshold" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'stock-adjuster')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Save Stock
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
