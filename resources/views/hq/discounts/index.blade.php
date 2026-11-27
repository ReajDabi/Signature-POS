<x-layouts.app title="Discounts & Promos" portal="hq">
    @php
        $initialDiscounts = [
            [
                'id' => 1,
                'label' => 'Senior citizen 20%',
                'kind' => 'discount',
                'type' => 'percent',
                'value' => '20.00',
                'scope' => 'global',
                'branch_code' => 'Global',
                'starts_at' => '',
                'ends_at' => '',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'label' => 'PWD 20%',
                'kind' => 'discount',
                'type' => 'percent',
                'value' => '20.00',
                'scope' => 'global',
                'branch_code' => 'Global',
                'starts_at' => '',
                'ends_at' => '',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'label' => 'Holiday Glam 15% Off',
                'kind' => 'promo',
                'type' => 'percent',
                'value' => '15.00',
                'scope' => 'global',
                'branch_code' => 'Global',
                'starts_at' => '2026-11-25',
                'ends_at' => '2026-12-31',
                'is_active' => true,
            ],
            [
                'id' => 4,
                'label' => 'Payday ₱150 Off',
                'kind' => 'promo',
                'type' => 'flat',
                'value' => '150.00',
                'scope' => 'global',
                'branch_code' => 'Global',
                'starts_at' => '2026-11-28',
                'ends_at' => '2026-11-30',
                'is_active' => true,
            ],
            [
                'id' => 5,
                'label' => 'Promo 100 off',
                'kind' => 'promo',
                'type' => 'flat',
                'value' => '100.00',
                'scope' => 'branch',
                'branch_code' => 'TCC',
                'starts_at' => '',
                'ends_at' => '',
                'is_active' => true,
            ],
            [
                'id' => 6,
                'label' => 'Anniversary 25% Flash',
                'kind' => 'promo',
                'type' => 'percent',
                'value' => '25.00',
                'scope' => 'global',
                'branch_code' => 'Global',
                'starts_at' => '2026-10-01',
                'ends_at' => '2026-10-05',
                'is_active' => false,
            ],
        ];
    @endphp

    <div
        x-data="{
            discounts: @js($initialDiscounts),
            tab: 'all',
            search: '',
            notice: null,
            editingId: null,
            form: {
                label: '',
                kind: 'promo',
                type: 'percent',
                value: '15.00',
                scope: 'global',
                branch_code: 'Global',
                starts_at: '',
                ends_at: '',
                is_active: true,
            },
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.discounts.filter((d) => {
                    const matchTab = this.tab === 'all'
                        || (this.tab === 'discount' && d.kind === 'discount')
                        || (this.tab === 'promo' && d.kind === 'promo')
                        || (this.tab === 'global' && d.scope === 'global')
                        || (this.tab === 'branch' && d.scope === 'branch');
                    const matchQ = !q || d.label.toLowerCase().includes(q) || d.branch_code.toLowerCase().includes(q);
                    return matchTab && matchQ;
                });
            },
            get globalActiveCount() {
                return this.discounts.filter((d) => d.scope === 'global' && d.is_active).length;
            },
            get activePromoCount() {
                return this.discounts.filter((d) => d.kind === 'promo' && d.is_active).length;
            },
            formatPeso(val) {
                return '₱ ' + Number(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            formatBenefit(d) {
                return d.type === 'percent'
                    ? Number(d.value).toFixed(0) + '% off'
                    : this.formatPeso(d.value) + ' off';
            },
            formatSchedule(d) {
                if (!d.starts_at && !d.ends_at) return 'Always valid';
                if (d.starts_at && d.ends_at) return `${d.starts_at} → ${d.ends_at}`;
                return d.starts_at ? `From ${d.starts_at}` : `Until ${d.ends_at}`;
            },
            openCreate() {
                this.editingId = null;
                this.form = {
                    label: '',
                    kind: 'promo',
                    type: 'percent',
                    value: '15.00',
                    scope: 'global',
                    branch_code: 'Global',
                    starts_at: '',
                    ends_at: '',
                    is_active: true,
                };
                $dispatch('open-modal', 'hq-discount-editor');
            },
            openEdit(d) {
                this.editingId = d.id;
                this.form = {
                    label: d.label,
                    kind: d.kind,
                    type: d.type,
                    value: d.value,
                    scope: d.scope,
                    branch_code: d.branch_code,
                    starts_at: d.starts_at,
                    ends_at: d.ends_at,
                    is_active: d.is_active,
                };
                $dispatch('open-modal', 'hq-discount-editor');
            },
            saveDiscount() {
                if (!this.form.label.trim()) return;
                const branchCode = this.form.scope === 'global'
                    ? 'Global'
                    : (this.form.branch_code === 'Global' ? 'TCC' : this.form.branch_code);

                const payload = {
                    label: this.form.label.trim(),
                    kind: this.form.kind,
                    type: this.form.type,
                    value: Number(this.form.value || 0).toFixed(2),
                    scope: this.form.scope,
                    branch_code: branchCode,
                    starts_at: this.form.starts_at || '',
                    ends_at: this.form.ends_at || '',
                    is_active: this.form.is_active,
                };

                if (this.editingId) {
                    const target = this.discounts.find((d) => d.id === this.editingId);
                    if (target) Object.assign(target, payload);
                    this.notice = `${payload.label} updated${payload.scope === 'global' ? ' across all branches' : ''}.`;
                } else {
                    this.discounts.unshift({ id: Date.now(), ...payload });
                    this.notice = `${payload.label} published${payload.scope === 'global' ? ' to all branches' : ''}.`;
                }
                $dispatch('close-modal', 'hq-discount-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Master Catalog" title="Global Discounts & Promos">
            <x-slot:actions>
                <button
                    type="button"
                    x-on:click="openCreate()"
                    class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer"
                >
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add Discount / Promo</span>
                </button>
            </x-slot:actions>
        </x-ui.section-heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Active Global Rules" valueBind="globalActiveCount" hint="Synced to all branches" icon="discounts" badge="Network-Wide" badgeTone="gold" />
            <x-ui.stat-card label="Active Promos" valueBind="activePromoCount" hint="Campaigns & seasonal offers" icon="sparkle" badge="Live" badgeTone="emerald" />
            <x-ui.stat-card label="Total Rules" valueBind="discounts.length" hint="Global & branch overrides" icon="branches" />
        </div>

        <x-ui.notice />

        <x-ui.card>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                        @foreach (['all' => 'All', 'discount' => 'Discounts', 'promo' => 'Promos', 'global' => 'Global', 'branch' => 'Branch Only'] as $key => $label)
                            <button
                                type="button"
                                x-on:click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                                class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Search discounts or promos…"
                            class="sig-input rounded-xl py-1.5 pl-9 pr-3 text-xs w-56"
                        >
                    </div>
                </div>
                <span class="text-xs text-theme-muted tabular-nums" x-text="filtered.length + ' rules'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Label</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Kind</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Benefit</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Scope</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Schedule</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="d in filtered" :key="d.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="d.label"></td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-medium capitalize"
                                        :class="d.kind === 'promo'
                                            ? 'border-gold-500/35 bg-gold-soft text-gold-accent'
                                            : 'border-theme bg-theme-elevated text-theme-secondary'"
                                        x-text="d.kind"
                                    ></span>
                                </td>
                                <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-gold-accent whitespace-nowrap" x-text="formatBenefit(d)"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium"
                                        :class="d.scope === 'global'
                                            ? 'border-gold-500/30 bg-gold-soft text-gold-accent'
                                            : 'border-theme-strong bg-theme-elevated text-theme-muted'"
                                        x-text="d.scope === 'global' ? 'All Branches' : d.branch_code"
                                    ></span>
                                </td>
                                <td class="py-3.5 pr-4 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="formatSchedule(d)"></td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="d.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="d.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            x-on:click="d.is_active = !d.is_active; notice = `${d.label} ${d.is_active ? 'activated' : 'paused'}.`"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="d.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                            role="switch"
                                            :aria-checked="d.is_active.toString()"
                                        >
                                            <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="d.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                        <button type="button" x-on:click="openEdit(d)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0" x-cloak>
                            <td colspan="7" class="py-8 text-center text-xs text-theme-muted">No matching discounts or promos found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="hq-discount-editor" title="Discount or Promo" maxWidth="max-w-lg">
            <form x-on:submit.prevent="saveDiscount()" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Label</label>
                        <input type="text" x-model="form.label" required placeholder="Holiday Glam 15% Off" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Kind</label>
                        <select x-model="form.kind" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="discount">Discount (Standing)</option>
                            <option value="promo">Promo (Campaign)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Benefit Type</label>
                        <select x-model="form.type" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="percent">Percent (%)</option>
                            <option value="flat">Flat Amount (₱)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary" x-text="form.type === 'percent' ? 'Percentage (%)' : 'Amount (₱)'"></label>
                        <input type="number" step="0.01" min="0" :max="form.type === 'percent' ? 100 : null" x-model="form.value" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Availability</label>
                        <select x-model="form.scope" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="global">All Branches (Global)</option>
                            <option value="branch">Single Branch</option>
                        </select>
                    </div>

                    <div x-show="form.scope === 'branch'" x-cloak class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch</label>
                        <select x-model="form.branch_code" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="TCC">Tagum City Center (TCC)</option>
                            <option value="APK">Apokon (APK)</option>
                            <option value="VSV">Visayan Village (VSV)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Starts On (Optional)</label>
                        <input type="date" x-model="form.starts_at" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Ends On (Optional)</label>
                        <input type="date" x-model="form.ends_at" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                    </div>
                </div>

                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active across selected scope</span>
                </label>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'hq-discount-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        <span x-text="editingId ? 'Save Changes' : 'Publish Rule'"></span>
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
