<x-layouts.app title="Audit Trail" portal="hq">
    @php
        $auditEntries = [
            [
                'id' => 1048,
                'timestamp' => '2026-11-26 11:38',
                'date' => '2026-11-26',
                'branch_code' => 'TCC',
                'user_name' => 'Ana Reyes',
                'action' => 'transaction.voided',
                'subject' => 'Transaction #40',
                'old_value' => ['status' => 'completed', 'total' => '750.00'],
                'new_value' => ['status' => 'voided', 'reason' => 'Duplicate entry'],
                'ip_address' => '192.168.10.24',
            ],
            [
                'id' => 1047,
                'timestamp' => '2026-11-26 10:52',
                'date' => '2026-11-26',
                'branch_code' => 'HQ',
                'user_name' => 'HQ Admin',
                'action' => 'service.bom_synced',
                'subject' => 'Service #3',
                'old_value' => ['Hair Color Tube' => '1.00'],
                'new_value' => ['Hair Color Tube' => '1.00', 'Hair Serum 100ml' => '0.10'],
                'ip_address' => '10.0.4.12',
            ],
            [
                'id' => 1046,
                'timestamp' => '2026-11-26 09:40',
                'date' => '2026-11-26',
                'branch_code' => 'APK',
                'user_name' => 'Belen Cruz',
                'action' => 'inventory.restocked',
                'subject' => 'Inventory #11',
                'old_value' => ['qty_on_hand' => '8.00'],
                'new_value' => ['qty_on_hand' => '28.00'],
                'ip_address' => '192.168.20.14',
            ],
            [
                'id' => 1045,
                'timestamp' => '2026-11-25 18:12',
                'date' => '2026-11-25',
                'branch_code' => 'HQ',
                'user_name' => 'HQ Admin',
                'action' => 'user.deactivated',
                'subject' => 'User #14',
                'old_value' => ['is_active' => true],
                'new_value' => ['is_active' => false],
                'ip_address' => '10.0.4.12',
            ],
            [
                'id' => 1044,
                'timestamp' => '2026-11-25 16:05',
                'date' => '2026-11-25',
                'branch_code' => 'VSV',
                'user_name' => 'Carmen Diaz',
                'action' => 'commission.marked_paid',
                'subject' => 'Commission #219',
                'old_value' => ['status' => 'unpaid', 'amount' => '1840.00'],
                'new_value' => ['status' => 'paid', 'amount' => '1840.00'],
                'ip_address' => '192.168.30.18',
            ],
            [
                'id' => 1043,
                'timestamp' => '2026-11-24 14:22',
                'date' => '2026-11-24',
                'branch_code' => 'HQ',
                'user_name' => 'HQ Admin',
                'action' => 'service.updated',
                'subject' => 'Service #4',
                'old_value' => ['price' => '2300.00'],
                'new_value' => ['price' => '2500.00'],
                'ip_address' => '10.0.4.12',
            ],
            [
                'id' => 1042,
                'timestamp' => '2026-11-23 11:09',
                'date' => '2026-11-23',
                'branch_code' => 'TCC',
                'user_name' => 'Ana Reyes',
                'action' => 'import.completed',
                'subject' => 'Import #7',
                'old_value' => null,
                'new_value' => ['filename' => 'tcc-nov-restock.csv', 'imported' => 14],
                'ip_address' => '192.168.10.24',
            ],
        ];
    @endphp

    <div
        x-data="{
            entries: @js($auditEntries),
            search: '',
            branchFilter: 'all',
            actionFilter: 'all',
            dateFrom: '2026-11-20',
            dateTo: '2026-11-26',
            selectedEntry: null,
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.entries.filter((e) => {
                    const matchQ = !q || e.user_name.toLowerCase().includes(q) || e.action.toLowerCase().includes(q) || e.subject.toLowerCase().includes(q) || e.ip_address.includes(q);
                    const matchBranch = this.branchFilter === 'all' || e.branch_code === this.branchFilter;
                    const matchAction = this.actionFilter === 'all' || e.action === this.actionFilter;
                    const matchFrom = !this.dateFrom || e.date >= this.dateFrom;
                    const matchTo = !this.dateTo || e.date <= this.dateTo;
                    return matchQ && matchBranch && matchAction && matchFrom && matchTo;
                });
            },
            actionBadgeClass(action) {
                if (action.includes('voided') || action.includes('deactivated')) {
                    return 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300';
                }
                if (action.includes('paid') || action.includes('completed') || action.includes('restocked')) {
                    return 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300';
                }
                return 'border-gold-500/35 bg-gold-soft text-gold-accent';
            },
            inspect(entry) {
                this.selectedEntry = entry;
                $dispatch('open-modal', 'audit-inspector');
            },
            resetFilters() {
                this.search = '';
                this.branchFilter = 'all';
                this.actionFilter = 'all';
                this.dateFrom = '2026-11-20';
                this.dateTo = '2026-11-26';
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Compliance" title="Audit Log">
            <x-slot:actions>
                <span class="inline-flex items-center gap-1.5 rounded-xl border border-theme-strong bg-gold-soft px-3 py-1.5 text-xs font-medium text-gold-accent">
                    <x-ui.icon name="lock" class="h-3.5 w-3.5" />
                    <span>Read-Only Ledger</span>
                </span>
            </x-slot:actions>
        </x-ui.section-heading>

        {{-- Unified Filter + Table Card --}}
        <x-ui.card title="Events">
            <x-slot:actions>
                <button type="button" x-on:click="resetFilters()" class="sig-btn-ghost rounded-xl px-3 py-1.5 text-xs font-medium cursor-pointer">
                    Reset
                </button>
            </x-slot:actions>

            {{-- Row 1: Search (left) + Date (right) --}}
            <div class="mb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="relative sm:w-1/2 lg:w-2/5">
                    <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                    <input
                        type="search"
                        x-model="search"
                        placeholder="Search user, action, subject…"
                        class="sig-input w-full rounded-xl py-2 pl-9 pr-3 text-xs"
                    >
                </div>
                <div class="text-sm text-gray-500">
                    {{ now()->format('F j, Y') }}
                </div>
            </div>

            {{-- Row 2: Branches, Actions, Date range --}}
            <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <select x-model="branchFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                    <option value="all">All Branches</option>
                    <option value="HQ">HQ</option>
                    <option value="TCC">TCC</option>
                    <option value="APK">APK</option>
                    <option value="VSV">VSV</option>
                </select>

                <select x-model="actionFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                    <option value="all">All Actions</option>
                    <option value="transaction.voided">transaction.voided</option>
                    <option value="service.bom_synced">service.bom_synced</option>
                    <option value="service.updated">service.updated</option>
                    <option value="inventory.restocked">inventory.restocked</option>
                    <option value="commission.marked_paid">commission.marked_paid</option>
                    <option value="user.deactivated">user.deactivated</option>
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
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Branch</th>
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
                                <td class="py-3.5 pr-3 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="entry.timestamp"></td>
                                <td class="py-3.5 pr-3">
                                    <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-xs font-semibold text-gold-accent" x-text="entry.branch_code"></span>
                                </td>
                                <td class="py-3.5 pr-3 font-medium text-theme-primary whitespace-nowrap" x-text="entry.user_name"></td>
                                <td class="py-3.5 pr-3">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 font-mono text-xs font-medium whitespace-nowrap"
                                        :class="actionBadgeClass(entry.action)"
                                        x-text="entry.action"
                                    ></span>
                                </td>
                                <td class="py-3.5 pr-3 font-mono text-xs text-theme-secondary whitespace-nowrap" x-text="entry.subject"></td>
                                <td class="py-3.5 pr-3 font-mono text-xs text-theme-muted whitespace-nowrap" x-text="entry.ip_address"></td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <button
                                        type="button"
                                        x-on:click="inspect(entry)"
                                        class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                    >
                                        Inspect
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0" x-cloak>
                            <td colspan="7" class="py-8 text-center text-xs text-theme-muted">
                                No matching events found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="audit-inspector" title="Change Details" maxWidth="max-w-2xl">
            <div x-show="selectedEntry" class="space-y-4">
                <div class="sig-elevated rounded-xl px-4 py-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <div>
                        <span class="font-mono font-semibold text-gold-accent" x-text="selectedEntry?.action"></span>
                        <span class="mx-2 text-theme-muted">·</span>
                        <span class="text-theme-primary font-medium" x-text="selectedEntry?.subject"></span>
                    </div>
                    <span class="font-mono text-theme-muted tabular-nums" x-text="selectedEntry?.timestamp"></span>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <p class="text-xs font-medium text-rose-600 dark:text-rose-400">Before</p>
                        <pre class="sig-elevated overflow-x-auto rounded-xl p-3.5 font-mono text-xs text-theme-secondary" x-text="selectedEntry?.old_value ? JSON.stringify(selectedEntry.old_value, null, 2) : 'None'"></pre>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">After</p>
                        <pre class="sig-elevated overflow-x-auto rounded-xl p-3.5 font-mono text-xs text-theme-primary" x-text="selectedEntry?.new_value ? JSON.stringify(selectedEntry.new_value, null, 2) : 'None'"></pre>
                    </div>
                </div>

                <div class="flex justify-end border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'audit-inspector')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
