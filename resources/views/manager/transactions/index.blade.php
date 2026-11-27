<x-layouts.app title="Transactions" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $initialTransactions = [
            [
                'id' => 41,
                'ref' => 'TX-TCC-041',
                'timestamp' => '2026-11-26 11:42',
                'date' => '2026-11-26',
                'cashier' => 'Ana Reyes',
                'discount_label' => null,
                'subtotal' => '2850.00',
                'discount' => '0.00',
                'total' => '2850.00',
                'cash_tendered' => '3000.00',
                'status' => 'completed',
                'void_reason' => null,
                'items' => [
                    ['service' => 'Rebond', 'technician' => 'Maria Santos', 'qty' => 1, 'unit_price' => '2500.00', 'line_total' => '2500.00', 'commission' => '375.00'],
                    ['service' => 'Haircut (Women)', 'technician' => 'Jun Reyes', 'qty' => 1, 'unit_price' => '350.00', 'line_total' => '350.00', 'commission' => '42.00'],
                ],
            ],
            [
                'id' => 40,
                'ref' => 'TX-TCC-040',
                'timestamp' => '2026-11-26 11:15',
                'date' => '2026-11-26',
                'cashier' => 'Ana Reyes',
                'discount_label' => null,
                'subtotal' => '450.00',
                'discount' => '0.00',
                'total' => '450.00',
                'cash_tendered' => '500.00',
                'status' => 'voided',
                'void_reason' => 'Duplicate entry',
                'items' => [
                    ['service' => 'Gel Polish', 'technician' => 'Ana Dela Cruz', 'qty' => 1, 'unit_price' => '450.00', 'line_total' => '450.00', 'commission' => '45.00'],
                ],
            ],
            [
                'id' => 39,
                'ref' => 'TX-TCC-039',
                'timestamp' => '2026-11-26 10:34',
                'date' => '2026-11-26',
                'cashier' => 'Ana Reyes',
                'discount_label' => 'Senior citizen 20%',
                'subtotal' => '350.00',
                'discount' => '70.00',
                'total' => '280.00',
                'cash_tendered' => '300.00',
                'status' => 'completed',
                'void_reason' => null,
                'items' => [
                    ['service' => 'Haircut (Women)', 'technician' => 'Jun Reyes', 'qty' => 1, 'unit_price' => '350.00', 'line_total' => '350.00', 'commission' => '33.60'],
                ],
            ],
            [
                'id' => 38,
                'ref' => 'TX-TCC-038',
                'timestamp' => '2026-11-26 09:50',
                'date' => '2026-11-26',
                'cashier' => 'Ana Reyes',
                'discount_label' => 'Promo 100 off',
                'subtotal' => '1800.00',
                'discount' => '100.00',
                'total' => '1700.00',
                'cash_tendered' => '2000.00',
                'status' => 'completed',
                'void_reason' => null,
                'items' => [
                    ['service' => 'Hair Color (Full)', 'technician' => 'Maria Santos', 'qty' => 1, 'unit_price' => '1800.00', 'line_total' => '1800.00', 'commission' => '255.00'],
                ],
            ],
            [
                'id' => 37,
                'ref' => 'TX-TCC-037',
                'timestamp' => '2026-11-25 17:18',
                'date' => '2026-11-25',
                'cashier' => 'Ana Reyes',
                'discount_label' => null,
                'subtotal' => '800.00',
                'discount' => '0.00',
                'total' => '800.00',
                'cash_tendered' => '1000.00',
                'status' => 'completed',
                'void_reason' => null,
                'items' => [
                    ['service' => 'Foot Spa', 'technician' => 'Liza Mendoza', 'qty' => 1, 'unit_price' => '500.00', 'line_total' => '500.00', 'commission' => '50.00'],
                    ['service' => 'Pedicure', 'technician' => 'Ana Dela Cruz', 'qty' => 1, 'unit_price' => '300.00', 'line_total' => '300.00', 'commission' => '30.00'],
                ],
            ],
        ];
    @endphp

    <div
        x-data="{
            transactions: @js($initialTransactions),
            search: '',
            statusFilter: 'all',
            dateFilter: '',
            notice: null,
            selectedTx: null,
            voidTarget: null,
            voidReason: '',
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.transactions.filter((tx) => {
                    const services = tx.items.map((i) => i.service).join(' ').toLowerCase();
                    const matchQ = !q || tx.ref.toLowerCase().includes(q) || tx.cashier.toLowerCase().includes(q) || services.includes(q);
                    const matchStatus = this.statusFilter === 'all' || tx.status === this.statusFilter;
                    const matchDate = !this.dateFilter || tx.date === this.dateFilter;
                    return matchQ && matchStatus && matchDate;
                });
            },
            formatPeso(val) {
                return '₱ ' + Number(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            inspect(tx) {
                this.selectedTx = tx;
                $dispatch('open-modal', 'tx-inspector');
            },
            openVoid(tx) {
                this.voidTarget = tx;
                this.voidReason = '';
                $dispatch('open-modal', 'tx-void');
            },
            confirmVoid() {
                if (!this.voidTarget || !this.voidReason.trim()) {
                    return;
                }
                this.voidTarget.status = 'voided';
                this.voidTarget.void_reason = this.voidReason.trim();
                this.notice = `${this.voidTarget.ref} marked as voided.`;
                $dispatch('close-modal', 'tx-void');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Branch Pulse" title="Branch Transactions" />

        <x-ui.notice />

        <x-ui.card>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Search ref, cashier, service…"
                            class="sig-input rounded-xl py-2 pl-9 pr-3 text-xs w-56"
                        >
                    </div>
                    <select x-model="statusFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Statuses</option>
                        <option value="completed">Completed</option>
                        <option value="voided">Voided</option>
                    </select>
                    <input type="date" x-model="dateFilter" class="sig-input rounded-xl px-3 py-2 text-xs tabular-nums" aria-label="Filter date">
                </div>
                <span class="text-xs text-theme-muted tabular-nums" x-text="filtered.length + ' transactions'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Ref</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Time (PHT)</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Cashier</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Services</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Discount</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Total</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="tx in filtered" :key="tx.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-primary whitespace-nowrap" x-text="tx.ref"></td>
                                <td class="py-3.5 pr-4 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="tx.timestamp"></td>
                                <td class="py-3.5 pr-4 text-theme-secondary whitespace-nowrap" x-text="tx.cashier"></td>
                                <td class="py-3.5 pr-4 max-w-[220px] truncate text-theme-primary" x-text="tx.items.map((i) => i.service).join(', ')"></td>
                                <td class="py-3.5 pr-4 text-right tabular-nums text-theme-muted whitespace-nowrap" x-text="Number(tx.discount) > 0 ? '-' + formatPeso(tx.discount) : '—'"></td>
                                <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-gold-accent whitespace-nowrap" x-text="formatPeso(tx.total)"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium capitalize"
                                        :class="tx.status === 'completed'
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="tx.status"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-3">
                                        <button type="button" x-on:click="inspect(tx)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                            Inspect
                                        </button>
                                        <button
                                            type="button"
                                            x-show="tx.status === 'completed'"
                                            x-on:click="openVoid(tx)"
                                            class="text-xs font-medium text-rose-500 hover:underline cursor-pointer"
                                        >
                                            Void
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0" x-cloak>
                            <td colspan="8" class="py-8 text-center text-xs text-theme-muted">
                                No matching transactions found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="tx-inspector" title="Transaction Receipt" maxWidth="max-w-xl">
            <div x-show="selectedTx" class="space-y-4">
                <div class="sig-elevated flex flex-wrap items-center justify-between gap-2 rounded-xl px-4 py-3 text-xs">
                    <div>
                        <p class="font-mono font-semibold text-theme-primary" x-text="selectedTx?.ref"></p>
                        <p class="text-theme-muted" x-text="selectedTx?.timestamp + ' · Cashier: ' + selectedTx?.cashier"></p>
                    </div>
                    <span
                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium capitalize"
                        :class="selectedTx?.status === 'completed'
                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                        x-text="selectedTx?.status"
                    ></span>
                </div>

                <div class="divide-y divide-theme border-y border-theme">
                    <template x-for="(item, idx) in (selectedTx?.items || [])" :key="idx">
                        <div class="flex items-center justify-between gap-3 py-2.5 text-xs">
                            <div>
                                <p class="font-medium text-theme-primary" x-text="item.qty + '× ' + item.service"></p>
                                <p class="text-theme-muted" x-text="'Tech: ' + item.technician + ' · Commission: ' + formatPeso(item.commission)"></p>
                            </div>
                            <span class="font-semibold tabular-nums text-theme-primary" x-text="formatPeso(item.line_total)"></span>
                        </div>
                    </template>
                </div>

                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between text-theme-secondary">
                        <span>Subtotal</span>
                        <span class="tabular-nums" x-text="formatPeso(selectedTx?.subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-theme-secondary" x-show="Number(selectedTx?.discount) > 0">
                        <span x-text="selectedTx?.discount_label || 'Discount'"></span>
                        <span class="tabular-nums text-emerald-500" x-text="'-' + formatPeso(selectedTx?.discount)"></span>
                    </div>
                    <div class="flex justify-between border-t border-theme pt-2 text-sm font-semibold text-theme-primary">
                        <span>Cash Total</span>
                        <span class="font-display text-lg tabular-nums text-gold-accent" x-text="formatPeso(selectedTx?.total)"></span>
                    </div>
                </div>

                <div x-show="selectedTx?.void_reason" x-cloak class="rounded-xl border border-rose-500/35 bg-rose-500/10 px-4 py-2.5 text-xs text-rose-700 dark:text-rose-300">
                    Void Reason: <span class="font-medium" x-text="selectedTx?.void_reason"></span>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tx-inspector')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </x-ui.modal>

        <x-ui.modal name="tx-void" title="Void Transaction" maxWidth="max-w-md">
            <form x-on:submit.prevent="confirmVoid()" class="space-y-4">
                <div class="sig-elevated rounded-xl px-4 py-3 text-xs space-y-1">
                    <p class="font-mono font-semibold text-theme-primary" x-text="voidTarget?.ref"></p>
                    <p class="text-theme-muted" x-text="'Amount: ' + formatPeso(voidTarget?.total) + ' · Reverses stock & commissions.'"></p>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Void Reason</label>
                    <textarea
                        rows="3"
                        x-model="voidReason"
                        required
                        placeholder="Duplicate entry, wrong service selected…"
                        class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm"
                    ></textarea>
                </div>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tx-void')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500 cursor-pointer transition">
                        Confirm Void
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
