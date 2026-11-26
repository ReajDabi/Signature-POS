<x-layouts.app
    title="Global Services & BOM"
    subtitle="Master salon service catalog and automatic Bill-of-Materials ingredient deduction"
    portal="hq"
>
    @php
        $initialServices = [
            [
                'id' => 1,
                'name' => 'Haircut (Women)',
                'category' => 'Hair',
                'price' => '350.00',
                'duration_min' => 45,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [],
            ],
            [
                'id' => 2,
                'name' => 'Haircut (Men)',
                'category' => 'Hair',
                'price' => '200.00',
                'duration_min' => 30,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [],
            ],
            [
                'id' => 3,
                'name' => 'Hair Color (Full)',
                'category' => 'Hair',
                'price' => '1800.00',
                'duration_min' => 120,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [
                    ['product' => 'Hair Color Tube', 'unit' => 'tube', 'qty_consumed' => '1.00'],
                    ['product' => 'Hair Serum 100ml', 'unit' => 'bottle', 'qty_consumed' => '0.10'],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Rebond',
                'category' => 'Hair',
                'price' => '2500.00',
                'duration_min' => 180,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [
                    ['product' => 'Rebond Cream', 'unit' => 'jar', 'qty_consumed' => '1.00'],
                    ['product' => 'Hair Serum 100ml', 'unit' => 'bottle', 'qty_consumed' => '0.15'],
                ],
            ],
            [
                'id' => 5,
                'name' => 'Manicure',
                'category' => 'Nail',
                'price' => '250.00',
                'duration_min' => 45,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [
                    ['product' => 'Cuticle Oil', 'unit' => 'bottle', 'qty_consumed' => '0.05'],
                    ['product' => 'Nail Polish Remover', 'unit' => 'bottle', 'qty_consumed' => '0.05'],
                ],
            ],
            [
                'id' => 6,
                'name' => 'Pedicure',
                'category' => 'Nail',
                'price' => '300.00',
                'duration_min' => 60,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [
                    ['product' => 'Cuticle Oil', 'unit' => 'bottle', 'qty_consumed' => '0.05'],
                ],
            ],
            [
                'id' => 7,
                'name' => 'Gel Polish',
                'category' => 'Nail',
                'price' => '450.00',
                'duration_min' => 60,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [
                    ['product' => 'Gel Polish', 'unit' => 'bottle', 'qty_consumed' => '0.10'],
                ],
            ],
            [
                'id' => 8,
                'name' => 'Foot Spa',
                'category' => 'Nail',
                'price' => '500.00',
                'duration_min' => 60,
                'scope' => 'global',
                'branch_code' => 'Global',
                'is_active' => true,
                'bom' => [],
            ],
            [
                'id' => 9,
                'name' => 'Bridal Makeup Trial',
                'category' => 'Hair',
                'price' => '1500.00',
                'duration_min' => 90,
                'scope' => 'branch',
                'branch_code' => 'TCC',
                'is_active' => false,
                'bom' => [],
            ],
        ];

        $productOptions = ['Hair Serum 100ml', 'Hair Color Tube', 'Cuticle Oil', 'Nail Polish Remover', 'Gel Polish', 'Rebond Cream'];
    @endphp

    <div
        x-data="{
            services: @js($initialServices),
            productOptions: @js($productOptions),
            search: '',
            category: 'All',
            notice: null,
            editingId: null,
            bomServiceId: null,
            newBom: { product: 'Hair Color Tube', unit: 'tube', qty_consumed: '1.00' },
            form: { name: '', category: 'Hair', price: '500.00', duration_min: 45, scope: 'global', branch_code: 'Global', is_active: true },
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.services.filter((s) => {
                    const matchCat = this.category === 'All' || (this.category === 'Branch' ? s.scope === 'branch' : s.category === this.category);
                    const matchQ = !q || s.name.toLowerCase().includes(q) || s.category.toLowerCase().includes(q);
                    return matchCat && matchQ;
                });
            },
            get currentBomService() {
                return this.services.find((s) => s.id === this.bomServiceId) || null;
            },
            formatPeso(val) {
                return '₱ ' + Number(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            openCreate() {
                this.editingId = null;
                this.form = { name: '', category: 'Hair', price: '500.00', duration_min: 45, scope: 'global', branch_code: 'Global', is_active: true };
                $dispatch('open-modal', 'service-editor');
            },
            openEdit(svc) {
                this.editingId = svc.id;
                this.form = { name: svc.name, category: svc.category, price: svc.price, duration_min: svc.duration_min, scope: svc.scope, branch_code: svc.branch_code, is_active: svc.is_active };
                $dispatch('open-modal', 'service-editor');
            },
            openBom(svc) {
                this.bomServiceId = svc.id;
                $dispatch('open-modal', 'bom-editor');
            },
            addBomRow() {
                const target = this.currentBomService;
                if (!target || Number(this.newBom.qty_consumed) <= 0) {
                    return;
                }
                const existing = target.bom.find((item) => item.product === this.newBom.product);
                if (existing) {
                    existing.qty_consumed = Number(this.newBom.qty_consumed).toFixed(2);
                } else {
                    target.bom.push({
                        product: this.newBom.product,
                        unit: this.newBom.unit,
                        qty_consumed: Number(this.newBom.qty_consumed).toFixed(2),
                    });
                }
                this.notice = `Updated BOM recipe for ${target.name} in UI preview.`;
            },
            removeBomRow(idx) {
                const target = this.currentBomService;
                if (target) {
                    target.bom.splice(idx, 1);
                }
            },
            saveService() {
                if (!this.form.name.trim()) {
                    return;
                }
                const branchCode = this.form.scope === 'global' ? 'Global' : (this.form.branch_code === 'Global' ? 'TCC' : this.form.branch_code);
                if (this.editingId) {
                    const svc = this.services.find((s) => s.id === this.editingId);
                    if (svc) {
                        Object.assign(svc, {
                            name: this.form.name.trim(),
                            category: this.form.category,
                            price: Number(this.form.price).toFixed(2),
                            duration_min: Number(this.form.duration_min),
                            scope: this.form.scope,
                            branch_code: branchCode,
                            is_active: this.form.is_active,
                        });
                    }
                    this.notice = `Updated service '${this.form.name.trim()}' in UI preview.`;
                } else {
                    this.services.push({
                        id: Date.now(),
                        name: this.form.name.trim(),
                        category: this.form.category,
                        price: Number(this.form.price).toFixed(2),
                        duration_min: Number(this.form.duration_min),
                        scope: this.form.scope,
                        branch_code: branchCode,
                        is_active: this.form.is_active,
                        bom: [],
                    });
                    this.notice = `Added service '${this.form.name.trim()}' in UI preview.`;
                }
                $dispatch('close-modal', 'service-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading
            eyebrow="Master Catalog · BOM Recipes"
            title="Global Services & Bill of Materials"
            description="Global services (branch_id = null) sync to all branches; click a chevron or BOM action to inspect ingredients deducted on checkout."
        >
            <x-slot:actions>
                <button
                    type="button"
                    x-on:click="openCreate()"
                    class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer"
                >
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add Service</span>
                </button>
            </x-slot:actions>
        </x-ui.section-heading>

        {{-- Notice toast --}}
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

        <x-ui.card title="Master Service Directory" subtitle="Expand a service row to view automatic product deductions per unit sold">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                        @foreach (['All', 'Hair', 'Nail', 'Branch'] as $tab)
                            <button
                                type="button"
                                x-on:click="category = '{{ $tab }}'"
                                :class="category === '{{ $tab }}' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                                class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                            >
                                {{ $tab === 'Branch' ? 'Branch Scoped' : $tab }}
                            </button>
                        @endforeach
                    </div>

                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Search service…"
                            class="sig-input rounded-xl py-1.5 pl-9 pr-3 text-xs w-48"
                        >
                    </div>
                </div>
            </x-slot:actions>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 w-8"></th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Service</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Category</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Price</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Duration</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">BOM Recipe</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Scope</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actions</th>
                        </tr>
                    </thead>

                    <template x-for="svc in filtered" :key="svc.id">
                        <tbody x-data="{ expanded: false }" class="border-b border-theme">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-2">
                                    <button
                                        type="button"
                                        x-show="svc.bom.length > 0"
                                        x-on:click="expanded = !expanded"
                                        class="rounded p-1 text-theme-muted hover:text-theme-primary cursor-pointer transition"
                                        :aria-label="'Toggle BOM for ' + svc.name"
                                    >
                                        <svg class="h-3.5 w-3.5 transition-transform duration-150" :class="expanded ? 'rotate-90 text-gold-accent' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="svc.name"></td>
                                <td class="py-3.5 pr-4">
                                    <span class="rounded-md bg-theme-elevated px-2.5 py-0.5 text-xs text-theme-secondary" x-text="svc.category"></span>
                                </td>
                                <td class="py-3.5 pr-4 text-right tabular-nums font-semibold text-gold-accent" x-text="formatPeso(svc.price)"></td>
                                <td class="py-3.5 pl-4 pr-4 tabular-nums text-theme-muted" x-text="svc.duration_min + ' min'"></td>
                                <td class="py-3.5 pr-4">
                                    <button
                                        type="button"
                                        x-on:click="openBom(svc)"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-theme px-2.5 py-0.5 text-xs font-medium text-theme-secondary hover:border-theme-strong hover:text-gold-accent cursor-pointer transition"
                                    >
                                        <x-ui.icon name="products" class="h-3.5 w-3.5 text-gold-accent" />
                                        <span class="tabular-nums" x-text="svc.bom.length ? svc.bom.length + ' ingredient' + (svc.bom.length > 1 ? 's' : '') : 'Add BOM'"></span>
                                    </button>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-md border px-2 py-0.5 text-xs font-medium"
                                        :class="svc.scope === 'global'
                                            ? 'border-gold-500/30 bg-gold-soft text-gold-accent'
                                            : 'border-theme-strong bg-theme-elevated text-theme-muted'"
                                    >
                                        <x-ui.icon name="sparkle" class="h-3 w-3" />
                                        <span x-text="svc.scope === 'global' ? 'Global' : svc.branch_code"></span>
                                    </span>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="svc.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="svc.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            x-on:click="svc.is_active = !svc.is_active"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="svc.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                            role="switch"
                                            :aria-checked="svc.is_active.toString()"
                                        >
                                            <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="svc.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click="openEdit(svc)"
                                            class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Expandable BOM Row properly nested inside same <tbody> Alpine scope --}}
                            <tr x-show="expanded && svc.bom.length > 0" x-cloak class="bg-theme-elevated/45">
                                <td></td>
                                <td colspan="8" class="py-3 pr-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-gold-accent">
                                            Bill of Materials · Deducted per Checkout Qty
                                        </p>
                                        <button
                                            type="button"
                                            x-on:click="openBom(svc)"
                                            class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                        >
                                            Edit Recipe →
                                        </button>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="item in svc.bom" :key="item.product">
                                            <div class="sig-card inline-flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs">
                                                <x-ui.icon name="products" class="h-3.5 w-3.5 text-gold-accent" />
                                                <span class="font-medium text-theme-primary" x-text="item.product"></span>
                                                <span class="font-mono text-theme-muted tabular-nums" x-text="'× ' + item.qty_consumed + ' ' + item.unit"></span>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>

                    <tbody x-show="filtered.length === 0" x-cloak>
                        <tr>
                            <td colspan="9" class="py-8 text-center text-xs text-theme-muted">
                                No services match your search or category filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        {{-- Add / Edit Service Modal --}}
        <x-ui.modal
            name="service-editor"
            title="Master Service Details"
            subtitle="Configure service pricing, duration & catalog visibility"
            maxWidth="max-w-xl"
        >
            <form x-on:submit.prevent="saveService()" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Service Name</label>
                        <input type="text" x-model="form.name" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="e.g. Keratin Treatment">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Category</label>
                        <select x-model="form.category" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="Hair">Hair</option>
                            <option value="Nail">Nail</option>
                            <option value="Facial">Facial</option>
                            <option value="Waxing">Waxing</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Price (₱)</label>
                        <input type="number" step="0.01" min="0" x-model="form.price" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Duration (Minutes)</label>
                        <input type="number" min="5" step="5" x-model="form.duration_min" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Catalog Scope</label>
                        <select x-model="form.scope" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="global">Global (branch_id = null)</option>
                            <option value="branch">Branch-specific Override</option>
                        </select>
                    </div>
                    <div x-show="form.scope === 'branch'" x-cloak>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Target Branch</label>
                        <select x-model="form.branch_code" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="TCC">Tagum City Center (TCC)</option>
                            <option value="APK">Apokon (APK)</option>
                            <option value="VSV">Visayan Village (VSV)</option>
                        </select>
                    </div>
                </div>

                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Service visible in POS terminal catalog</span>
                </label>

                <div class="flex justify-end gap-2.5 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'service-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        <span x-text="editingId ? 'Save Changes' : 'Save Service'"></span>
                    </button>
                </div>
            </form>
        </x-ui.modal>

        {{-- BOM Recipe Modal --}}
        <x-ui.modal
            name="bom-editor"
            title="Bill of Materials Recipe"
            subtitle="Configure exact decimal product deductions (service_products.qty_consumed)"
            maxWidth="max-w-xl"
        >
            <div x-show="currentBomService" class="space-y-4">
                <div class="sig-elevated rounded-xl px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-theme-muted">Selected Service</p>
                        <p class="text-sm font-semibold text-theme-primary" x-text="currentBomService?.name"></p>
                    </div>
                    <span class="font-display text-lg font-semibold text-gold-accent tabular-nums" x-text="formatPeso(currentBomService?.price)"></span>
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Linked Ingredients</p>
                    <template x-if="currentBomService && currentBomService.bom.length === 0">
                        <p class="rounded-xl border border-dashed border-theme px-4 py-3 text-xs text-theme-muted">
                            No ingredients mapped yet. Add a product consumption line below.
                        </p>
                    </template>
                    <template x-for="(row, idx) in (currentBomService?.bom || [])" :key="row.product">
                        <div class="sig-elevated flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs">
                            <span class="font-medium text-theme-primary" x-text="row.product"></span>
                            <div class="flex items-center gap-3">
                                <span class="font-mono tabular-nums text-gold-accent" x-text="row.qty_consumed + ' ' + row.unit"></span>
                                <button type="button" x-on:click="removeBomRow(idx)" class="text-rose-500 hover:underline cursor-pointer">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                <form x-on:submit.prevent="addBomRow()" class="border-t border-theme pt-4 space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-theme-secondary">Add / Update Ingredient Line</p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <select x-model="newBom.product" class="sig-input rounded-xl px-3 py-2 text-xs sm:col-span-2">
                            <template x-for="opt in productOptions" :key="opt">
                                <option :value="opt" x-text="opt"></option>
                            </template>
                        </select>
                        <input type="number" step="0.01" min="0.01" x-model="newBom.qty_consumed" class="sig-input rounded-xl px-3 py-2 text-xs tabular-nums" placeholder="Qty">
                    </div>
                    <div class="flex justify-end gap-2.5 pt-2">
                        <button type="button" x-on:click="$dispatch('close-modal', 'bom-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                            Done
                        </button>
                        <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                            Add Ingredient
                        </button>
                    </div>
                </form>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
