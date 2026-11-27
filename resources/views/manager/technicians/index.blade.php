<x-layouts.app title="Technicians & Commissions" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $initialTechs = [
            ['id' => 1, 'name' => 'Maria Santos', 'commission_rate' => '15.00', 'completed_items' => 24, 'is_active' => true],
            ['id' => 2, 'name' => 'Jun Reyes', 'commission_rate' => '12.00', 'completed_items' => 19, 'is_active' => true],
            ['id' => 3, 'name' => 'Ana Dela Cruz', 'commission_rate' => '10.00', 'completed_items' => 16, 'is_active' => true],
            ['id' => 4, 'name' => 'Liza Mendoza', 'commission_rate' => '10.00', 'completed_items' => 12, 'is_active' => true],
            ['id' => 5, 'name' => 'Grace Navarro', 'commission_rate' => '10.00', 'completed_items' => 4, 'is_active' => false],
        ];

        $initialCommissions = [
            ['id' => 224, 'tx_ref' => 'TX-TCC-041', 'service' => 'Rebond', 'technician' => 'Maria Santos', 'rate' => '15.00', 'amount' => '375.00', 'is_paid' => false, 'paid_at' => null],
            ['id' => 223, 'tx_ref' => 'TX-TCC-041', 'service' => 'Haircut (Women)', 'technician' => 'Jun Reyes', 'rate' => '12.00', 'amount' => '42.00', 'is_paid' => false, 'paid_at' => null],
            ['id' => 222, 'tx_ref' => 'TX-TCC-039', 'service' => 'Haircut (Women)', 'technician' => 'Jun Reyes', 'rate' => '12.00', 'amount' => '33.60', 'is_paid' => false, 'paid_at' => null],
            ['id' => 221, 'tx_ref' => 'TX-TCC-038', 'service' => 'Hair Color (Full)', 'technician' => 'Maria Santos', 'rate' => '15.00', 'amount' => '255.00', 'is_paid' => false, 'paid_at' => null],
            ['id' => 220, 'tx_ref' => 'TX-TCC-037', 'service' => 'Foot Spa', 'technician' => 'Liza Mendoza', 'rate' => '10.00', 'amount' => '50.00', 'is_paid' => false, 'paid_at' => null],
            ['id' => 219, 'tx_ref' => 'TX-TCC-037', 'service' => 'Pedicure', 'technician' => 'Ana Dela Cruz', 'rate' => '10.00', 'amount' => '30.00', 'is_paid' => true, 'paid_at' => '2026-11-25 18:00'],
        ];
    @endphp

    <div
        x-data="{
            technicians: @js($initialTechs),
            commissions: @js($initialCommissions),
            tab: 'commissions',
            techFilter: 'all',
            payoutFilter: 'unpaid',
            search: '',
            notice: null,
            editingId: null,
            form: { name: '', commission_rate: '10.00', is_active: true },
            formatPeso(val) {
                return '₱ ' + Number(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            unpaidTotalFor(techName) {
                return this.commissions
                    .filter((c) => c.technician === techName && !c.is_paid)
                    .reduce((sum, c) => sum + Number(c.amount), 0);
            },
            get totalUnpaid() {
                return this.formatPeso(this.commissions.filter((c) => !c.is_paid).reduce((s, c) => s + Number(c.amount), 0));
            },
            get totalPaid() {
                return this.formatPeso(this.commissions.filter((c) => c.is_paid).reduce((s, c) => s + Number(c.amount), 0));
            },
            get activeTechCount() {
                return this.technicians.filter((t) => t.is_active).length;
            },
            get filteredCommissions() {
                const q = this.search.trim().toLowerCase();
                return this.commissions.filter((c) => {
                    const matchTech = this.techFilter === 'all' || c.technician === this.techFilter;
                    const matchStatus = this.payoutFilter === 'all' || (this.payoutFilter === 'paid' ? c.is_paid : !c.is_paid);
                    const matchQ = !q || c.tx_ref.toLowerCase().includes(q) || c.service.toLowerCase().includes(q) || c.technician.toLowerCase().includes(q);
                    return matchTech && matchStatus && matchQ;
                });
            },
            get filteredTechs() {
                const q = this.search.trim().toLowerCase();
                return this.technicians.filter((t) => !q || t.name.toLowerCase().includes(q));
            },
            markPaid(row) {
                row.is_paid = true;
                row.paid_at = 'Just now';
                this.notice = `Marked ${this.formatPeso(row.amount)} paid to ${row.technician}.`;
            },
            markAllVisiblePaid() {
                let count = 0;
                this.filteredCommissions.forEach((row) => {
                    if (!row.is_paid) {
                        row.is_paid = true;
                        row.paid_at = 'Just now';
                        count++;
                    }
                });
                if (count > 0) {
                    this.notice = `Marked ${count} commission entries as paid.`;
                }
            },
            openCreate() {
                this.editingId = null;
                this.form = { name: '', commission_rate: '10.00', is_active: true };
                $dispatch('open-modal', 'tech-editor');
            },
            openEdit(tech) {
                this.editingId = tech.id;
                this.form = { name: tech.name, commission_rate: tech.commission_rate, is_active: tech.is_active };
                $dispatch('open-modal', 'tech-editor');
            },
            saveTech() {
                if (!this.form.name.trim()) return;
                if (this.editingId) {
                    const t = this.technicians.find((x) => x.id === this.editingId);
                    if (t) {
                        Object.assign(t, {
                            name: this.form.name.trim(),
                            commission_rate: Number(this.form.commission_rate).toFixed(2),
                            is_active: this.form.is_active,
                        });
                    }
                    this.notice = `${this.form.name.trim()} updated.`;
                } else {
                    this.technicians.push({
                        id: Date.now(),
                        name: this.form.name.trim(),
                        commission_rate: Number(this.form.commission_rate).toFixed(2),
                        completed_items: 0,
                        is_active: this.form.is_active,
                    });
                    this.notice = `${this.form.name.trim()} added to branch team.`;
                }
                $dispatch('close-modal', 'tech-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Team & Pricing" title="Technicians & Commissions">
            <x-slot:actions>
                <button type="button" x-on:click="openCreate()" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer">
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add Technician</span>
                </button>
            </x-slot:actions>
        </x-ui.section-heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Active Technicians" valueBind="activeTechCount" icon="technicians" />
            <x-ui.stat-card label="Unpaid Payouts" valueBind="totalUnpaid" valueTone="gold" icon="cash" badge="Pending" badgeTone="amber" />
            <x-ui.stat-card label="Settled Payouts" valueBind="totalPaid" icon="check" badge="Paid" badgeTone="emerald" />
        </div>

        <x-ui.notice />

        <x-ui.card>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                        <button
                            type="button"
                            x-on:click="tab = 'commissions'"
                            :class="tab === 'commissions' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                            class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                        >
                            Commission Ledger
                        </button>
                        <button
                            type="button"
                            x-on:click="tab = 'roster'"
                            :class="tab === 'roster' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                            class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                        >
                            Roster & Rates
                        </button>
                    </div>

                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input type="search" x-model="search" placeholder="Search…" class="sig-input rounded-xl py-1.5 pl-9 pr-3 text-xs w-48">
                    </div>

                    <select x-show="tab === 'commissions'" x-model="techFilter" class="sig-input rounded-xl px-3 py-1.5 text-xs">
                        <option value="all">All Technicians</option>
                        <template x-for="t in technicians" :key="t.id">
                            <option :value="t.name" x-text="t.name"></option>
                        </template>
                    </select>

                    <select x-show="tab === 'commissions'" x-model="payoutFilter" class="sig-input rounded-xl px-3 py-1.5 text-xs">
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="all">All</option>
                    </select>
                </div>

                <button
                    type="button"
                    x-show="tab === 'commissions' && filteredCommissions.some((c) => !c.is_paid)"
                    x-on:click="markAllVisiblePaid()"
                    class="sig-btn-ghost rounded-xl px-3 py-1.5 text-xs font-medium text-gold-accent cursor-pointer"
                >
                    Mark Visible Paid
                </button>
            </div>

            <div x-show="tab === 'commissions'" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Ref</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Service</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Technician</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Rate</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Earned</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="row in filteredCommissions" :key="row.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-muted whitespace-nowrap" x-text="row.tx_ref"></td>
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="row.service"></td>
                                <td class="py-3.5 pr-4 text-theme-secondary" x-text="row.technician"></td>
                                <td class="py-3.5 pr-4 text-right font-mono text-xs tabular-nums text-theme-muted" x-text="row.rate + '%'"></td>
                                <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-gold-accent" x-text="formatPeso(row.amount)"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="row.is_paid
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-amber-500/40 bg-amber-500/12 text-amber-800 dark:text-amber-300'"
                                        x-text="row.is_paid ? 'Paid' : 'Unpaid'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <button
                                        type="button"
                                        x-show="!row.is_paid"
                                        x-on:click="markPaid(row)"
                                        class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                    >
                                        Mark Paid
                                    </button>
                                    <span x-show="row.is_paid" class="text-xs text-theme-muted tabular-nums" x-text="row.paid_at"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredCommissions.length === 0" x-cloak>
                            <td colspan="7" class="py-8 text-center text-xs text-theme-muted">No matching commissions.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div x-show="tab === 'roster'" x-cloak class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Technician</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Rate</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Services</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Unpaid</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="tech in filteredTechs" :key="tech.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="tech.name"></td>
                                <td class="py-3.5 pr-4 text-right font-mono text-xs tabular-nums text-theme-secondary" x-text="tech.commission_rate + '%'"></td>
                                <td class="py-3.5 pr-4 text-right tabular-nums text-theme-muted" x-text="tech.completed_items"></td>
                                <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-gold-accent" x-text="formatPeso(unpaidTotalFor(tech.name))"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="tech.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="tech.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            x-on:click="tech.is_active = !tech.is_active"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="tech.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                            role="switch"
                                            :aria-checked="tech.is_active.toString()"
                                        >
                                            <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="tech.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                        <button type="button" x-on:click="openEdit(tech)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">Edit</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="tech-editor" title="Technician Profile" maxWidth="max-w-md">
            <form x-on:submit.prevent="saveTech()" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Full Name</label>
                    <input type="text" x-model="form.name" required placeholder="Maria Santos" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Flat Commission Rate (%)</label>
                    <input type="number" step="0.50" min="0" max="100" x-model="form.commission_rate" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm tabular-nums">
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active</span>
                </label>
                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tech-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Save Technician
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
