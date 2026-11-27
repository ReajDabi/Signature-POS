<x-layouts.app title="Services & Discounts" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $initialDiscounts = [
            ['id' => 1, 'label' => 'Senior citizen 20%', 'type' => 'percent', 'value' => '20.00', 'is_active' => true],
            ['id' => 2, 'label' => 'PWD 20%', 'type' => 'percent', 'value' => '20.00', 'is_active' => true],
            ['id' => 3, 'label' => 'Promo 100 off', 'type' => 'flat', 'value' => '100.00', 'is_active' => true],
        ];

        $initialServices = [
            ['id' => 1, 'name' => 'Haircut (Women)', 'category' => 'Hair', 'price' => '350.00', 'duration_min' => 45, 'scope' => 'global', 'is_active' => true],
            ['id' => 2, 'name' => 'Haircut (Men)', 'category' => 'Hair', 'price' => '200.00', 'duration_min' => 30, 'scope' => 'global', 'is_active' => true],
            ['id' => 3, 'name' => 'Hair Color (Full)', 'category' => 'Hair', 'price' => '1800.00', 'duration_min' => 120, 'scope' => 'global', 'is_active' => true],
            ['id' => 4, 'name' => 'Rebond', 'category' => 'Hair', 'price' => '2500.00', 'duration_min' => 180, 'scope' => 'global', 'is_active' => true],
            ['id' => 5, 'name' => 'Manicure', 'category' => 'Nail', 'price' => '250.00', 'duration_min' => 45, 'scope' => 'global', 'is_active' => true],
            ['id' => 6, 'name' => 'Pedicure', 'category' => 'Nail', 'price' => '300.00', 'duration_min' => 60, 'scope' => 'global', 'is_active' => true],
            ['id' => 7, 'name' => 'Gel Polish', 'category' => 'Nail', 'price' => '450.00', 'duration_min' => 60, 'scope' => 'global', 'is_active' => true],
            ['id' => 8, 'name' => 'Foot Spa', 'category' => 'Nail', 'price' => '500.00', 'duration_min' => 60, 'scope' => 'global', 'is_active' => true],
            ['id' => 9, 'name' => 'Bridal Makeup Trial', 'category' => 'Hair', 'price' => '1500.00', 'duration_min' => 90, 'scope' => 'branch', 'is_active' => true],
        ];
    @endphp

    <div
        x-data="{
            discounts: @js($initialDiscounts),
            services: @js($initialServices),
            scopeFilter: 'all',
            search: '',
            notice: null,
            editingDiscountId: null,
            editingServiceId: null,
            discountForm: { label: '', type: 'percent', value: '10.00', is_active: true },
            serviceForm: { name: '', category: 'Hair', price: '500.00', duration_min: 45, is_active: true },
            formatPeso(val) {
                return '₱ ' + Number(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            get filteredServices() {
                const q = this.search.trim().toLowerCase();
                return this.services.filter((s) => {
                    const matchScope = this.scopeFilter === 'all' || s.scope === this.scopeFilter;
                    const matchQ = !q || s.name.toLowerCase().includes(q) || s.category.toLowerCase().includes(q);
                    return matchScope && matchQ;
                });
            },
            openDiscountCreate() {
                this.editingDiscountId = null;
                this.discountForm = { label: '', type: 'percent', value: '10.00', is_active: true };
                $dispatch('open-modal', 'discount-editor');
            },
            openDiscountEdit(d) {
                this.editingDiscountId = d.id;
                this.discountForm = { label: d.label, type: d.type, value: d.value, is_active: d.is_active };
                $dispatch('open-modal', 'discount-editor');
            },
            saveDiscount() {
                if (!this.discountForm.label.trim()) return;
                if (this.editingDiscountId) {
                    const d = this.discounts.find((x) => x.id === this.editingDiscountId);
                    if (d) Object.assign(d, { label: this.discountForm.label.trim(), type: this.discountForm.type, value: Number(this.discountForm.value).toFixed(2), is_active: this.discountForm.is_active });
                    this.notice = `${this.discountForm.label.trim()} updated.`;
                } else {
                    this.discounts.push({ id: Date.now(), label: this.discountForm.label.trim(), type: this.discountForm.type, value: Number(this.discountForm.value).toFixed(2), is_active: this.discountForm.is_active });
                    this.notice = `${this.discountForm.label.trim()} added.`;
                }
                $dispatch('close-modal', 'discount-editor');
            },
            openServiceCreate() {
                this.editingServiceId = null;
                this.serviceForm = { name: '', category: 'Hair', price: '500.00', duration_min: 45, is_active: true };
                $dispatch('open-modal', 'branch-service-editor');
            },
            openServiceEdit(s) {
                if (s.scope !== 'branch') return;
                this.editingServiceId = s.id;
                this.serviceForm = { name: s.name, category: s.category, price: s.price, duration_min: s.duration_min, is_active: s.is_active };
                $dispatch('open-modal', 'branch-service-editor');
            },
            saveService() {
                if (!this.serviceForm.name.trim()) return;
                if (this.editingServiceId) {
                    const s = this.services.find((x) => x.id === this.editingServiceId);
                    if (s) Object.assign(s, { name: this.serviceForm.name.trim(), category: this.serviceForm.category, price: Number(this.serviceForm.price).toFixed(2), duration_min: Number(this.serviceForm.duration_min), is_active: this.serviceForm.is_active });
                    this.notice = `${this.serviceForm.name.trim()} updated.`;
                } else {
                    this.services.push({ id: Date.now(), name: this.serviceForm.name.trim(), category: this.serviceForm.category, price: Number(this.serviceForm.price).toFixed(2), duration_min: Number(this.serviceForm.duration_min), scope: 'branch', is_active: this.serviceForm.is_active });
                    this.notice = `${this.serviceForm.name.trim()} added to branch menu.`;
                }
                $dispatch('close-modal', 'branch-service-editor');
            },
        }"
        class="space-y-8"
    >
        <x-ui.notice />

        {{-- Section 1: Branch Discounts --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Pricing" title="Branch Discounts">
                <x-slot:actions>
                    <button type="button" x-on:click="openDiscountCreate()" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs cursor-pointer">
                        <x-ui.icon name="plus" class="h-4 w-4" />
                        <span>Add Discount</span>
                    </button>
                </x-slot:actions>
            </x-ui.section-heading>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <template x-for="d in discounts" :key="d.id">
                    <div class="sig-card rounded-2xl p-5 flex flex-col justify-between gap-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-theme-primary" x-text="d.label"></p>
                                <p class="mt-1 font-display text-2xl font-semibold text-gold-accent tabular-nums" x-text="d.type === 'percent' ? Number(d.value) + '% off' : formatPeso(d.value) + ' off'"></p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-lg border px-2 py-0.5 text-[11px] font-medium"
                                :class="d.is_active ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300' : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                x-text="d.is_active ? 'Active' : 'Inactive'"
                            ></span>
                        </div>
                        <div class="flex items-center justify-between border-t border-theme pt-3">
                            <button
                                type="button"
                                x-on:click="d.is_active = !d.is_active"
                                class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                :class="d.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                role="switch"
                                :aria-checked="d.is_active.toString()"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="d.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                            </button>
                            <button type="button" x-on:click="openDiscountEdit(d)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                Edit
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        {{-- Section 2: Branch Services & Global Menu --}}
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Menu" title="Services">
                <x-slot:actions>
                    <button type="button" x-on:click="openServiceCreate()" class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        <x-ui.icon name="plus" class="h-4 w-4 text-gold-accent" />
                        <span>Add Branch Service</span>
                    </button>
                </x-slot:actions>
            </x-ui.section-heading>

            <x-ui.card>
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                            <button type="button" x-on:click="scopeFilter = 'all'" :class="scopeFilter === 'all' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'" class="rounded-lg px-3 py-1 font-medium cursor-pointer transition">All</button>
                            <button type="button" x-on:click="scopeFilter = 'global'" :class="scopeFilter === 'global' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'" class="rounded-lg px-3 py-1 font-medium cursor-pointer transition">Global</button>
                            <button type="button" x-on:click="scopeFilter = 'branch'" :class="scopeFilter === 'branch' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'" class="rounded-lg px-3 py-1 font-medium cursor-pointer transition">Branch Only</button>
                        </div>
                        <div class="relative">
                            <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                            <input type="search" x-model="search" placeholder="Search services…" class="sig-input rounded-xl py-1.5 pl-9 pr-3 text-xs w-48">
                        </div>
                    </div>
                    <span class="text-xs text-theme-muted tabular-nums" x-text="filteredServices.length + ' services'"></span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-theme">
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Service</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Category</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Price</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Duration</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Status</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-theme">
                            <template x-for="svc in filteredServices" :key="svc.id">
                                <tr class="hover:bg-theme-elevated/45 transition">
                                    <td class="py-3.5 pr-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-medium text-theme-primary" x-text="svc.name"></span>
                                            <span
                                                class="inline-flex items-center rounded-md border px-2 py-0.5 text-[11px] font-medium"
                                                :class="svc.scope === 'global' ? 'border-gold-500/30 bg-gold-soft text-gold-accent' : 'border-theme-strong bg-theme-elevated text-theme-muted'"
                                                x-text="svc.scope === 'global' ? 'HQ Global' : 'TCC'"
                                            ></span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 pr-4"><span class="rounded-md bg-theme-elevated px-2.5 py-0.5 text-xs text-theme-secondary" x-text="svc.category"></span></td>
                                    <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-gold-accent" x-text="formatPeso(svc.price)"></td>
                                    <td class="py-3.5 pl-4 pr-4 tabular-nums text-theme-muted" x-text="svc.duration_min + ' min'"></td>
                                    <td class="py-3.5 pr-4">
                                        <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium" :class="svc.is_active ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300' : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'" x-text="svc.is_active ? 'Active' : 'Inactive'"></span>
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <button x-show="svc.scope === 'branch'" type="button" x-on:click="openServiceEdit(svc)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                            Edit
                                        </button>
                                        <span x-show="svc.scope === 'global'" class="text-xs text-theme-muted">HQ Managed</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </section>

        <x-ui.modal name="discount-editor" title="Branch Discount" maxWidth="max-w-md">
            <form x-on:submit.prevent="saveDiscount()" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Label</label>
                    <input type="text" x-model="discountForm.label" required placeholder="Senior citizen 20%" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Type</label>
                        <select x-model="discountForm.type" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="percent">Percent (%)</option>
                            <option value="flat">Flat (₱)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Value</label>
                        <input type="number" step="0.01" min="0" x-model="discountForm.value" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="discountForm.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active</span>
                </label>
                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'discount-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">Cancel</button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">Save Discount</button>
                </div>
            </form>
        </x-ui.modal>

        <x-ui.modal name="branch-service-editor" title="Branch Service" maxWidth="max-w-md">
            <form x-on:submit.prevent="saveService()" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Service Name</label>
                    <input type="text" x-model="serviceForm.name" required placeholder="Express Blowout" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Category</label>
                        <select x-model="serviceForm.category" class="sig-input w-full rounded-xl px-3 py-2.5 text-sm">
                            <option value="Hair">Hair</option>
                            <option value="Nail">Nail</option>
                            <option value="Facial">Facial</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Price (₱)</label>
                        <input type="number" step="0.01" min="0" x-model="serviceForm.price" required class="sig-input w-full rounded-xl px-3 py-2.5 text-sm tabular-nums">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Minutes</label>
                        <input type="number" min="5" step="5" x-model="serviceForm.duration_min" required class="sig-input w-full rounded-xl px-3 py-2.5 text-sm tabular-nums">
                    </div>
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="serviceForm.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active</span>
                </label>
                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'branch-service-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">Cancel</button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">Save Service</button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
