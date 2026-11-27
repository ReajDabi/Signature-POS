<x-layouts.app title="Branch Audit Log" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $auditEntries = [
            [
                'id' => 1048,
                'timestamp' => '2026-11-26 11:38',
                'date' => '2026-11-26',
                'user_name' => 'Ana Reyes',
                'action' => 'transaction.voided',
                'subject' => 'Transaction #40',
                'old_value' => ['status' => 'completed', 'total' => '450.00'],
                'new_value' => ['status' => 'voided', 'reason' => 'Duplicate entry'],
                'ip_address' => '192.168.10.24',
            ],
            [
                'id' => 1046,
                'timestamp' => '2026-11-25 18:00',
                'date' => '2026-11-25',
                'user_name' => 'Tagum City Center Manager',
                'action' => 'commission.marked_paid',
                'subject' => 'Commission #219',
                'old_value' => ['status' => 'unpaid', 'amount' => '30.00'],
                'new_value' => ['status' => 'paid', 'amount' => '30.00'],
                'ip_address' => '192.168.10.11',
            ],
            [
                'id' => 1044,
                'timestamp' => '2026-11-25 14:10',
                'date' => '2026-11-25',
                'user_name' => 'Tagum City Center Manager',
                'action' => 'inventory.restocked',
                'subject' => 'Hair Serum 100ml',
                'old_value' => ['qty_on_hand' => '6.00'],
                'new_value' => ['qty_on_hand' => '18.00'],
                'ip_address' => '192.168.10.11',
            ],
            [
                'id' => 1042,
                'timestamp' => '2026-11-23 11:09',
                'date' => '2026-11-23',
                'user_name' => 'Tagum City Center Manager',
                'action' => 'import.completed',
                'subject' => 'Import #7',
                'old_value' => null,
                'new_value' => ['filename' => 'tcc-nov-restock.csv', 'imported' => 14],
                'ip_address' => '192.168.10.11',
            ],
        ];
    @endphp

    <div
        x-data="{
            entries: @js($auditEntries),
            search: '',
            actionFilter: 'all',
            dateFrom: '2026-11-20',
            dateTo: '2026-11-26',
            selectedEntry: null,
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.entries.filter((e) => {
                    const matchQ = !q || e.user_name.toLowerCase().includes(q) || e.action.toLowerCase().includes(q) || e.subject.toLowerCase().includes(q);
                    const matchAction = this.actionFilter === 'all' || e.action === this.actionFilter;
                    const matchFrom = !this.dateFrom || e.date >= this.dateFrom;
                    const matchTo = !this.dateTo || e.date <= this.dateTo;
                    return matchQ && matchAction && matchFrom && matchTo;
                });
            },
            actionBadgeClass(action) {
                if (action.includes('voided')) return 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300';
                if (action.includes('paid') || action.includes('completed') || action.includes('restocked')) return 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300';
                return 'border-gold-500/35 bg-gold-soft text-gold-accent';
            },
            inspect(entry) {
                this.selectedEntry = entry;
                $dispatch('open-modal', 'branch-audit-inspector');
            },
            resetFilters() {
                this.search = '';
                this.actionFilter = 'all';
                this.dateFrom = '2026-11-20';
                this.dateTo = '2026-11-26';
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Compliance" title="Branch Audit Log">
            <x-slot:actions>
                <span class="inline-flex items-center gap-1.5 rounded-xl border border-theme-strong bg-gold-soft px-3 py-1.5 text-xs font-medium text-gold-accent">
                    <x-ui.icon name="lock" class="h-3.5 w-3.5" />
                    <span>Read-Only Ledger</span>
                </span>
            </x-slot:actions>
        </x-ui.section-heading>

        <x-ui.card title="Events">
            <x-slot:actions>
                <button type="button" x-on:click="resetFilters()" class="sig-btn-ghost rounded-xl px-3 py-1.5 text-xs font-medium cursor-pointer">
                    Reset
                </button>
            </x-slot:actions>

            <div class="mb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="relative sm:w-1/2 lg:w-2/5">
                    <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                    <input type="search" x-model="search" placeholder="Search staff, action, subject…" class="sig-input w-full rounded-xl py-2 pl-9 pr-3 text-xs">
                </div>
                <div class="text-xs font-medium text-theme-muted">
                    {{ now()->format('F j, Y') }}
                </div>
            </div>

            <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <select x-model="actionFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                    <option value="all">All Actions</option>
                    <option value="transaction.voided">transaction.voided</option>
                    <option value="commission.marked_paid">commission.marked_paid</option>
                    <option value="inventory.restocked">inventory.restocked</option>
                    <option value="import.completed">import.completed</option>
                </select>

                <div class="flex items-center gap-2">
                    <input type="date" x-model="dateFrom" class="sig-input w-full rounded-xl px-2.5 py-2 text-xs tabular-nums" aria-label="From date">
                    <input type="date" x-model="dateTo" class="sig-input w-full rounded-xl px-2.5 py-2 text-xs tabular-nums" aria-label="To date">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Time (PHT)</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">User</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Action</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Subject</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">IP</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="entry in filtered" :key="entry.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="entry.timestamp"></td>
                                <td class="py-3.5 pr-4 font-medium text-theme-primary whitespace-nowrap" x-text="entry.user_name"></td>
                                <td class="py-3.5 pr-4">
                                    <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 font-mono text-xs font-medium" :class="actionBadgeClass(entry.action)" x-text="entry.action"></span>
                                </td>
                                <td class="py-3.5 pr-4 text-theme-secondary" x-text="entry.subject"></td>
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-muted whitespace-nowrap" x-text="entry.ip_address"></td>
                                <td class="py-3.5 text-right">
                                    <button type="button" x-on:click="inspect(entry)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">Inspect</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="branch-audit-inspector" title="Event Diff" maxWidth="max-w-xl">
            <div x-show="selectedEntry" class="space-y-4">
                <div class="sig-elevated flex items-center justify-between rounded-xl px-4 py-3 text-xs">
                    <div>
                        <p class="font-mono font-semibold text-gold-accent" x-text="selectedEntry?.action"></p>
                        <p class="text-theme-muted" x-text="selectedEntry?.subject + ' · ' + selectedEntry?.timestamp"></p>
                    </div>
                    <span class="font-mono text-theme-muted" x-text="selectedEntry?.ip_address"></span>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-theme-muted">Before</p>
                        <pre class="sig-elevated overflow-x-auto rounded-xl p-3 font-mono text-xs text-theme-secondary" x-text="selectedEntry?.old_value ? JSON.stringify(selectedEntry.old_value, null, 2) : '—'"></pre>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-gold-accent">After</p>
                        <pre class="sig-elevated overflow-x-auto rounded-xl p-3 font-mono text-xs text-theme-primary" x-text="selectedEntry?.new_value ? JSON.stringify(selectedEntry.new_value, null, 2) : '—'"></pre>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'branch-audit-inspector')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">Close</button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
