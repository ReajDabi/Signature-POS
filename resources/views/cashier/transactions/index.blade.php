<x-layouts.app title="Shift Transactions" portal="cashier" branchLabel="Tagum City Center · TCC" userName="Ana Reyes">
    @php
        $shiftTransactions = [
            [
                'id' => 41,
                'ref' => 'TX-TCC-041',
                'time' => '11:42 AM',
                'services' => 'Rebond, Haircut (Women)',
                'technicians' => 'Maria Santos, Jun Reyes',
                'subtotal' => '₱ 2,850.00',
                'discount' => '—',
                'total' => '₱ 2,850.00',
                'tendered' => '₱ 3,000.00',
                'change' => '₱ 150.00',
                'status' => 'completed',
                'items' => [
                    ['name' => 'Rebond', 'qty' => 1, 'tech' => 'Maria Santos', 'line' => '₱ 2,500.00'],
                    ['name' => 'Haircut (Women)', 'qty' => 1, 'tech' => 'Jun Reyes', 'line' => '₱ 350.00'],
                ],
            ],
            [
                'id' => 40,
                'ref' => 'TX-TCC-040',
                'time' => '11:15 AM',
                'services' => 'Gel Polish',
                'technicians' => 'Ana Dela Cruz',
                'subtotal' => '₱ 450.00',
                'discount' => '—',
                'total' => '₱ 450.00',
                'tendered' => '₱ 500.00',
                'change' => '₱ 50.00',
                'status' => 'voided',
                'items' => [
                    ['name' => 'Gel Polish', 'qty' => 1, 'tech' => 'Ana Dela Cruz', 'line' => '₱ 450.00'],
                ],
            ],
            [
                'id' => 39,
                'ref' => 'TX-TCC-039',
                'time' => '10:34 AM',
                'services' => 'Haircut (Women)',
                'technicians' => 'Jun Reyes',
                'subtotal' => '₱ 350.00',
                'discount' => '-₱ 70.00 (Senior 20%)',
                'total' => '₱ 280.00',
                'tendered' => '₱ 300.00',
                'change' => '₱ 20.00',
                'status' => 'completed',
                'items' => [
                    ['name' => 'Haircut (Women)', 'qty' => 1, 'tech' => 'Jun Reyes', 'line' => '₱ 350.00'],
                ],
            ],
            [
                'id' => 38,
                'ref' => 'TX-TCC-038',
                'time' => '09:50 AM',
                'services' => 'Hair Color (Full)',
                'technicians' => 'Maria Santos',
                'subtotal' => '₱ 1,800.00',
                'discount' => '-₱ 100.00 (Promo)',
                'total' => '₱ 1,700.00',
                'tendered' => '₱ 2,000.00',
                'change' => '₱ 300.00',
                'status' => 'completed',
                'items' => [
                    ['name' => 'Hair Color (Full)', 'qty' => 1, 'tech' => 'Maria Santos', 'line' => '₱ 1,800.00'],
                ],
            ],
        ];
    @endphp

    <div
        x-data="{
            rows: @js($shiftTransactions),
            search: '',
            statusFilter: 'all',
            selected: null,
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.rows.filter((r) => {
                    const matchQ = !q || r.ref.toLowerCase().includes(q) || r.services.toLowerCase().includes(q) || r.technicians.toLowerCase().includes(q);
                    const matchStatus = this.statusFilter === 'all' || r.status === this.statusFilter;
                    return matchQ && matchStatus;
                });
            },
            openReceipt(row) {
                this.selected = row;
                $dispatch('open-modal', 'shift-receipt');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Point of Sale" title="Shift Transactions">
            <x-slot:actions>
                <a href="{{ route('cashier.pos') }}" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs">
                    <x-ui.icon name="pos" class="h-4 w-4" />
                    <span>Open POS</span>
                </a>
            </x-slot:actions>
        </x-ui.section-heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Shift Cash Collected" value="₱ 4,830.00" hint="3 completed tickets" icon="cash" valueTone="gold" />
            <x-ui.stat-card label="Discounts Applied" value="₱ 170.00" hint="Senior & Promo" icon="discounts" />
            <x-ui.stat-card label="Voided Tickets" value="1" hint="TX-TCC-040" icon="alert" badge="Manager Void" badgeTone="rose" />
        </div>

        <x-ui.card>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input type="search" x-model="search" placeholder="Search ref, service, tech…" class="sig-input rounded-xl py-2 pl-9 pr-3 text-xs w-56">
                    </div>
                    <select x-model="statusFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Statuses</option>
                        <option value="completed">Completed</option>
                        <option value="voided">Voided</option>
                    </select>
                </div>
                <span class="text-xs text-theme-muted tabular-nums" x-text="filtered.length + ' tickets'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Ref</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Time</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Services</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Technicians</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Total</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="row in filtered" :key="row.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-primary whitespace-nowrap" x-text="row.ref"></td>
                                <td class="py-3.5 pr-4 text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="row.time"></td>
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="row.services"></td>
                                <td class="py-3.5 pr-4 text-xs text-theme-secondary" x-text="row.technicians"></td>
                                <td class="py-3.5 pr-4 text-right font-semibold tabular-nums text-gold-accent whitespace-nowrap" x-text="row.total"></td>
                                <td class="py-3.5 pl-4 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium capitalize"
                                        :class="row.status === 'completed'
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="row.status"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <button type="button" x-on:click="openReceipt(row)" class="inline-flex items-center gap-1 text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                        <x-ui.icon name="printer" class="h-3.5 w-3.5" />
                                        <span>Receipt</span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="shift-receipt" title="Transaction Receipt" maxWidth="max-w-md">
            <div x-show="selected" class="space-y-4">
                <div class="text-center border-b border-dashed border-theme pb-3 space-y-0.5">
                    <p class="font-display text-lg font-semibold text-theme-primary">Signature Salon & Nail Spa</p>
                    <p class="text-xs text-theme-muted">Tagum City Center (TCC)</p>
                    <p class="font-mono text-xs text-gold-accent pt-1" x-text="selected?.ref"></p>
                    <p class="text-[11px] text-theme-muted" x-text="selected?.time + ' · Cashier: Ana Reyes'"></p>
                </div>

                <div class="space-y-2 text-xs">
                    <template x-for="(line, i) in (selected?.items || [])" :key="i">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-medium text-theme-primary" x-text="line.qty + '× ' + line.name"></p>
                                <p class="text-[11px] text-theme-muted" x-text="'Tech: ' + line.tech"></p>
                            </div>
                            <span class="font-semibold tabular-nums text-theme-primary" x-text="line.line"></span>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-theme pt-3 space-y-1 text-xs">
                    <div class="flex justify-between text-theme-secondary">
                        <span>Subtotal</span>
                        <span class="tabular-nums" x-text="selected?.subtotal"></span>
                    </div>
                    <div class="flex justify-between text-theme-secondary" x-show="selected?.discount !== '—'">
                        <span>Discount</span>
                        <span class="tabular-nums text-emerald-500" x-text="selected?.discount"></span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold text-theme-primary pt-1">
                        <span>Total</span>
                        <span class="font-display text-base tabular-nums text-gold-accent" x-text="selected?.total"></span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="window.print()" class="sig-btn-ghost inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        <x-ui.icon name="printer" class="h-4 w-4 text-gold-accent" />
                        <span>Print</span>
                    </button>
                    <button type="button" x-on:click="$dispatch('close-modal', 'shift-receipt')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
