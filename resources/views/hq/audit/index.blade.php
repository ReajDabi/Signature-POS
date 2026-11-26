<x-layouts.app
    title="Audit Trail"
    subtitle="Immutable cross-branch audit log · Write-once governance record"
    portal="hq"
>
    @php
        $auditEntries = [
            [
                'id' => 1048,
                'timestamp' => '2026-11-26 11:38:14',
                'date' => '2026-11-26',
                'branch_code' => 'TCC',
                'branch_name' => 'Tagum City Center',
                'user_name' => 'Tagum City Center Manager',
                'role' => 'branch_manager',
                'action' => 'transaction.voided',
                'subject_type' => 'Transaction',
                'subject_id' => 40,
                'summary' => 'Voided TX-APK-261126-027; restored BOM stock & reversed commissions',
                'old_value' => ['status' => 'completed', 'total' => '750.00', 'commissions_status' => 'unpaid'],
                'new_value' => ['status' => 'voided', 'void_reason' => 'Duplicate entry by cashier', 'stock_restored' => true],
                'ip_address' => '192.168.10.24',
            ],
            [
                'id' => 1047,
                'timestamp' => '2026-11-26 10:52:03',
                'date' => '2026-11-26',
                'branch_code' => 'HQ',
                'branch_name' => 'HQ Global Scope',
                'user_name' => 'HQ Admin',
                'role' => 'hq_admin',
                'action' => 'service.bom_synced',
                'subject_type' => 'Service',
                'subject_id' => 3,
                'summary' => 'Updated BOM recipe on Hair Color (Full)',
                'old_value' => ['Hair Color Tube' => '1.00'],
                'new_value' => ['Hair Color Tube' => '1.00', 'Hair Serum 100ml' => '0.10'],
                'ip_address' => '10.0.4.12',
            ],
            [
                'id' => 1046,
                'timestamp' => '2026-11-26 09:40:21',
                'date' => '2026-11-26',
                'branch_code' => 'APK',
                'branch_name' => 'Apokon',
                'user_name' => 'Apokon Manager',
                'role' => 'branch_manager',
                'action' => 'inventory.restocked',
                'subject_type' => 'BranchInventory',
                'subject_id' => 11,
                'summary' => 'Restocked Hair Color Tube (+20.00 tube)',
                'old_value' => ['qty_on_hand' => '8.00'],
                'new_value' => ['qty_on_hand' => '28.00', 'reason' => 'Supplier delivery #PO-882'],
                'ip_address' => '192.168.20.14',
            ],
            [
                'id' => 1045,
                'timestamp' => '2026-11-25 18:12:49',
                'date' => '2026-11-25',
                'branch_code' => 'HQ',
                'branch_name' => 'HQ Global Scope',
                'user_name' => 'HQ Admin',
                'role' => 'hq_admin',
                'action' => 'user.deactivated',
                'subject_type' => 'User',
                'subject_id' => 14,
                'summary' => 'Deactivated Grace Santos (cashier account)',
                'old_value' => ['is_active' => true, 'branch_id' => 1],
                'new_value' => ['is_active' => false, 'reason' => 'Resigned staff offboarding'],
                'ip_address' => '10.0.4.12',
            ],
            [
                'id' => 1044,
                'timestamp' => '2026-11-25 16:05:11',
                'date' => '2026-11-25',
                'branch_code' => 'VSV',
                'branch_name' => 'Visayan Village',
                'user_name' => 'Visayan Village Manager',
                'role' => 'branch_manager',
                'action' => 'commission.marked_paid',
                'subject_type' => 'Commission',
                'subject_id' => 219,
                'summary' => 'Marked 12 commission items paid for Jun Reyes',
                'old_value' => ['status' => 'unpaid', 'amount' => '1840.00'],
                'new_value' => ['status' => 'paid', 'paid_at' => '2026-11-25 16:05:11'],
                'ip_address' => '192.168.30.18',
            ],
            [
                'id' => 1043,
                'timestamp' => '2026-11-24 14:22:08',
                'date' => '2026-11-24',
                'branch_code' => 'HQ',
                'branch_name' => 'HQ Global Scope',
                'user_name' => 'HQ Admin',
                'role' => 'hq_admin',
                'action' => 'service.updated',
                'subject_type' => 'Service',
                'subject_id' => 4,
                'summary' => 'Updated global price for Rebond',
                'old_value' => ['price' => '2300.00', 'duration_min' => 180],
                'new_value' => ['price' => '2500.00', 'duration_min' => 180],
                'ip_address' => '10.0.4.12',
            ],
            [
                'id' => 1042,
                'timestamp' => '2026-11-23 11:09:44',
                'date' => '2026-11-23',
                'branch_code' => 'TCC',
                'branch_name' => 'Tagum City Center',
                'user_name' => 'Tagum City Center Manager',
                'role' => 'branch_manager',
                'action' => 'import.completed',
                'subject_type' => 'ImportBatch',
                'subject_id' => 7,
                'summary' => 'Imported 14 inventory rows via CSV batch',
                'old_value' => null,
                'new_value' => ['filename' => 'tcc-nov-restock.csv', 'imported' => 14, 'failed' => 0],
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
                    const matchQ = !q
                        || e.user_name.toLowerCase().includes(q)
                        || e.action.toLowerCase().includes(q)
                        || e.summary.toLowerCase().includes(q)
                        || e.subject_type.toLowerCase().includes(q)
                        || e.ip_address.includes(q);
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
        <x-ui.section-heading
            eyebrow="Compliance & Forensics"
            title="Network Audit Trail"
            description="Recorded atomically inside service transactions via AuditLogger::record(). Update and delete operations are blocked."
        >
            <x-slot:actions>
                <span class="inline-flex items-center gap-2 rounded-xl border border-theme-strong bg-gold-soft px-3.5 py-2 text-xs font-medium text-gold-accent">
                    <x-ui.icon name="lock" class="h-3.5 w-3.5" />
                    <span>Append-Only · ImmutableRecordException Guarded</span>
                </span>
            </x-slot:actions>
        </x-ui.section-heading>

        {{-- Filters Card --}}
        <x-ui.card title="Audit Filters" subtitle="Narrow events by branch, action category, or Asia/Manila date window">
            <x-slot:actions>
                <button type="button" x-on:click="resetFilters()" class="sig-btn-ghost rounded-xl px-3 py-1.5 text-xs font-medium cursor-pointer">
                    Reset Filters
                </button>
            </x-slot:actions>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div class="relative lg:col-span-2">
                    <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                    <input
                        type="search"
                        x-model="search"
                        placeholder="Search user, subject, summary, IP…"
                        class="sig-input w-full rounded-xl py-2.5 pl-9 pr-3 text-xs"
                    >
                </div>

                <select x-model="branchFilter" class="sig-input rounded-xl px-3 py-2.5 text-xs">
                    <option value="all">All Branches</option>
                    <option value="HQ">HQ Global Scope</option>
                    <option value="TCC">TCC · Tagum City Center</option>
                    <option value="APK">APK · Apokon</option>
                    <option value="VSV">VSV · Visayan Village</option>
                </select>

                <select x-model="actionFilter" class="sig-input rounded-xl px-3 py-2.5 text-xs">
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
                    <span class="text-xs text-theme-muted">to</span>
                    <input type="date" x-model="dateTo" class="sig-input w-full rounded-xl px-2.5 py-2 text-xs tabular-nums" aria-label="To date">
                </div>
            </div>
        </x-ui.card>

        {{-- Audit Events Table --}}
        <x-ui.card title="Event Ledger" subtitle="Showing immutable events matching your filters">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Timestamp (PHT)</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Scope</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actor</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Action</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Subject</th>
                            <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Change Summary</th>
                            <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Diff</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="entry in filtered" :key="entry.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-3 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="entry.timestamp"></td>
                                <td class="py-3.5 pr-3">
                                    <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-xs font-semibold text-gold-accent" x-text="entry.branch_code"></span>
                                </td>
                                <td class="py-3.5 pr-3">
                                    <p class="font-medium text-theme-primary" x-text="entry.user_name"></p>
                                    <p class="font-mono text-[11px] text-theme-muted" x-text="entry.ip_address"></p>
                                </td>
                                <td class="py-3.5 pr-3">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 font-mono text-xs font-medium whitespace-nowrap"
                                        :class="actionBadgeClass(entry.action)"
                                        x-text="entry.action"
                                    ></span>
                                </td>
                                <td class="py-3.5 pr-3 font-mono text-xs text-theme-secondary whitespace-nowrap" x-text="entry.subject_type + ' #' + entry.subject_id"></td>
                                <td class="py-3.5 pr-3 text-xs text-theme-secondary max-w-xs" x-text="entry.summary"></td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <button
                                        type="button"
                                        x-on:click="inspect(entry)"
                                        class="sig-btn-ghost rounded-lg px-3 py-1 text-xs font-medium text-gold-accent cursor-pointer"
                                    >
                                        Inspect JSON
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0" x-cloak>
                            <td colspan="7" class="py-8 text-center text-xs text-theme-muted">
                                No audit records match your selected filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        {{-- Old/New Value Diff Modal --}}
        <x-ui.modal
            name="audit-inspector"
            title="Audit Payload Inspector"
            subtitle="Read-only before/after snapshot stored in audit_logs"
            maxWidth="max-w-2xl"
        >
            <div x-show="selectedEntry" class="space-y-4">
                <div class="sig-elevated rounded-xl px-4 py-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <div>
                        <span class="font-mono font-semibold text-gold-accent" x-text="selectedEntry?.action"></span>
                        <span class="mx-2 text-theme-muted">·</span>
                        <span class="text-theme-primary font-medium" x-text="selectedEntry?.subject_type + ' #' + selectedEntry?.subject_id"></span>
                    </div>
                    <span class="font-mono text-theme-muted tabular-nums" x-text="selectedEntry?.timestamp + ' · ' + selectedEntry?.ip_address"></span>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-rose-600 dark:text-rose-400">
                            Old Value (old_value)
                        </p>
                        <pre class="sig-elevated overflow-x-auto rounded-xl p-3.5 font-mono text-xs text-theme-secondary" x-text="selectedEntry?.old_value ? JSON.stringify(selectedEntry.old_value, null, 2) : 'null (record creation)'"></pre>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-emerald-600 dark:text-emerald-400">
                            New Value (new_value)
                        </p>
                        <pre class="sig-elevated overflow-x-auto rounded-xl p-3.5 font-mono text-xs text-theme-primary" x-text="selectedEntry?.new_value ? JSON.stringify(selectedEntry.new_value, null, 2) : 'null'"></pre>
                    </div>
                </div>

                <div class="flex justify-end border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'audit-inspector')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Close Inspector
                    </button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
