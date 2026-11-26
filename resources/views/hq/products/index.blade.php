<x-layouts.app
    title="Global Products"
    subtitle="Master consumable & ingredient catalog shared across branch inventories"
    portal="hq"
>
    @php
        $initialProducts = [
            ['id' => 1, 'name' => 'Hair Serum 100ml', 'unit' => 'bottle', 'used_in' => 2, 'lowest_status' => 'low', 'lowest_branch' => 'VSV', 'total_qty' => '52.00', 'is_active' => true],
            ['id' => 2, 'name' => 'Hair Color Tube', 'unit' => 'tube', 'used_in' => 1, 'lowest_status' => 'low', 'lowest_branch' => 'TCC', 'total_qty' => '84.00', 'is_active' => true],
            ['id' => 3, 'name' => 'Cuticle Oil', 'unit' => 'bottle', 'used_in' => 2, 'lowest_status' => 'low', 'lowest_branch' => 'APK', 'total_qty' => '36.00', 'is_active' => true],
            ['id' => 4, 'name' => 'Nail Polish Remover', 'unit' => 'bottle', 'used_in' => 1, 'lowest_status' => 'out', 'lowest_branch' => 'APK', 'total_qty' => '18.00', 'is_active' => true],
            ['id' => 5, 'name' => 'Gel Polish', 'unit' => 'bottle', 'used_in' => 1, 'lowest_status' => 'out', 'lowest_branch' => 'VSV', 'total_qty' => '42.00', 'is_active' => true],
            ['id' => 6, 'name' => 'Rebond Cream', 'unit' => 'jar', 'used_in' => 1, 'lowest_status' => 'low', 'lowest_branch' => 'TCC', 'total_qty' => '11.00', 'is_active' => true],
            ['id' => 7, 'name' => 'Argan Mask 500ml', 'unit' => 'jar', 'used_in' => 0, 'lowest_status' => 'ok', 'lowest_branch' => 'All', 'total_qty' => '30.00', 'is_active' => false],
        ];
    @endphp

    <div
        x-data="{
            products: @js($initialProducts),
            search: '',
            stockFilter: 'all',
            notice: null,
            editingId: null,
            form: { name: '', unit: 'bottle', is_active: true },
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.products.filter((p) => {
                    const matchQ = !q || p.name.toLowerCase().includes(q) || p.unit.toLowerCase().includes(q);
                    const matchStock = this.stockFilter === 'all'
                        || (this.stockFilter === 'alerts' ? p.lowest_status !== 'ok' : p.lowest_status === this.stockFilter);
                    return matchQ && matchStock;
                });
            },
            openCreate() {
                this.editingId = null;
                this.form = { name: '', unit: 'bottle', is_active: true };
                $dispatch('open-modal', 'product-editor');
            },
            openEdit(prod) {
                this.editingId = prod.id;
                this.form = { name: prod.name, unit: prod.unit, is_active: prod.is_active };
                $dispatch('open-modal', 'product-editor');
            },
            saveProduct() {
                if (!this.form.name.trim()) {
                    return;
                }
                if (this.editingId) {
                    const prod = this.products.find((p) => p.id === this.editingId);
                    if (prod) {
                        prod.name = this.form.name.trim();
                        prod.unit = this.form.unit;
                        prod.is_active = this.form.is_active;
                    }
                    this.notice = `Updated product '${this.form.name.trim()}' in UI preview.`;
                } else {
                    this.products.push({
                        id: Date.now(),
                        name: this.form.name.trim(),
                        unit: this.form.unit,
                        used_in: 0,
                        lowest_status: 'ok',
                        lowest_branch: 'All',
                        total_qty: '0.00',
                        is_active: this.form.is_active,
                    });
                    this.notice = `Added global product '${this.form.name.trim()}' in UI preview.`;
                }
                $dispatch('close-modal', 'product-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading
            eyebrow="Master Inventory Definitions"
            title="Global Products Catalog"
            description="Products are defined once at HQ and stocked individually in branch_inventory per location."
        >
            <x-slot:actions>
                <button
                    type="button"
                    x-on:click="openCreate()"
                    class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer"
                >
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add Product</span>
                </button>
            </x-slot:actions>
        </x-ui.section-heading>

        <div
            x-show="notice"
            x-cloak
            class="flex items-center justify-between gap-3 rounded-xl border border-emerald-500/35 bg-emerald-500/10 px-4 py-3 text-xs text-emerald-800 dark:text-emerald-200"
        >
            <div class="flex items-center gap-2.5">
                <x-ui.icon name="check" class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                <span x-text="notice"></span>
            </div>
            <button type="button" x-on:click="notice = null" class="text-theme-muted hover:text-theme-primary cursor-pointer" aria-label="Dismiss notice">
                <x-ui.icon name="close" class="h-4 w-4" />
            </button>
        </div>

        <x-ui.card title="Master Products" subtitle="Lowest cross-branch stock status displayed using 9px box alert badges">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Search products…"
                            class="sig-input rounded-xl py-2 pl-9 pr-3 text-xs w-56"
                        >
                    </div>
                    <select x-model="stockFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Stock States</option>
                        <option value="alerts">Low &amp; Out Alerts</option>
                        <option value="out">Out of Stock Only</option>
                        <option value="low">Low Stock Only</option>
                        <option value="ok">Healthy Only</option>
                    </select>
                </div>
            </x-slot:actions>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Product Name</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Unit</th>
                            <th class="pb-3 text-center text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">BOM Links</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Network Qty</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Worst Branch Health</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="prod in filtered" :key="prod.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="prod.name"></td>
                                <td class="py-3.5 pr-4 text-xs uppercase tracking-wider text-theme-muted" x-text="prod.unit"></td>
                                <td class="py-3.5 pr-4 text-center">
                                    <span
                                        x-show="prod.used_in > 0"
                                        class="inline-flex items-center rounded-full bg-gold-soft px-2.5 py-0.5 text-xs font-semibold text-gold-accent tabular-nums"
                                        x-text="prod.used_in + ' service' + (prod.used_in > 1 ? 's' : '')"
                                    ></span>
                                    <span x-show="prod.used_in === 0" class="text-xs text-theme-muted">Unlinked</span>
                                </td>
                                <td class="py-3.5 pr-4 text-right tabular-nums text-theme-secondary" x-text="prod.total_qty + ' ' + prod.unit"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="sig-low-stock-badge inline-flex items-center gap-2 border px-2.5 py-1 text-xs font-semibold tracking-wide"
                                        :class="prod.lowest_status === 'out'
                                            ? 'border-rose-500/40 bg-rose-500/12 text-rose-700 dark:text-rose-300'
                                            : (prod.lowest_status === 'low'
                                                ? 'border-amber-500/45 bg-amber-500/14 text-amber-900 dark:text-amber-300'
                                                : 'border-emerald-500/35 bg-emerald-500/10 text-emerald-800 dark:text-emerald-300')"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full shrink-0"
                                            :class="prod.lowest_status === 'out' ? 'bg-rose-500' : (prod.lowest_status === 'low' ? 'bg-amber-500' : 'bg-emerald-500')"
                                        ></span>
                                        <span x-text="prod.lowest_status === 'out' ? 'Out of Stock' : (prod.lowest_status === 'low' ? 'Low Stock' : 'Healthy Stock')"></span>
                                        <span class="font-mono text-[11px] opacity-80" x-text="'· ' + prod.lowest_branch"></span>
                                    </span>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="prod.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="prod.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            x-on:click="prod.is_active = !prod.is_active"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="prod.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                            role="switch"
                                            :aria-checked="prod.is_active.toString()"
                                        >
                                            <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="prod.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click="openEdit(prod)"
                                            class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0" x-cloak>
                            <td colspan="7" class="py-8 text-center text-xs text-theme-muted">
                                No products match your filter criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        {{-- Add / Edit Product Modal --}}
        <x-ui.modal
            name="product-editor"
            title="Master Product Definition"
            subtitle="Track salon consumables uniformly across all Tagum branches"
        >
            <form x-on:submit.prevent="saveProduct()" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Product Name</label>
                    <input type="text" x-model="form.name" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="e.g. Hair Color Tube">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Unit of Measure</label>
                    <select x-model="form.unit" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                        <option value="bottle">bottle</option>
                        <option value="tube">tube</option>
                        <option value="jar">jar</option>
                        <option value="pack">pack</option>
                        <option value="roll">roll</option>
                        <option value="piece">piece</option>
                        <option value="ml">ml</option>
                    </select>
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Product active for BOM mapping and branch restocking</span>
                </label>
                <div class="flex justify-end gap-2.5 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'product-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        <span x-text="editingId ? 'Save Changes' : 'Save Product'"></span>
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
