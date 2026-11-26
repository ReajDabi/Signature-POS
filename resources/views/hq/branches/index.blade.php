<x-layouts.app title="Branches" portal="hq">
    @php
        $initialBranches = [
            [
                'id' => 1,
                'name' => 'Tagum City Center',
                'code' => 'TCC',
                'address' => 'National Highway, Tagum City',
                'manager' => 'Ana Reyes',
                'technicians' => 4,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Apokon',
                'code' => 'APK',
                'address' => 'Apokon Road, Tagum City',
                'manager' => 'Belen Cruz',
                'technicians' => 4,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Visayan Village',
                'code' => 'VSV',
                'address' => 'Visayan Village, Tagum City',
                'manager' => 'Carmen Diaz',
                'technicians' => 4,
                'is_active' => true,
            ],
        ];
    @endphp

    <div
        x-data="{
            branches: @js($initialBranches),
            search: '',
            statusFilter: 'all',
            notice: null,
            editingId: null,
            form: { name: '', code: '', address: '', is_active: true },
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.branches.filter((b) => {
                    const matchQuery = !q || b.name.toLowerCase().includes(q) || b.code.toLowerCase().includes(q) || (b.address || '').toLowerCase().includes(q);
                    const matchStatus = this.statusFilter === 'all' || (this.statusFilter === 'active' ? b.is_active : !b.is_active);
                    return matchQuery && matchStatus;
                });
            },
            openCreate() {
                this.editingId = null;
                this.form = { name: '', code: '', address: '', is_active: true };
                $dispatch('open-modal', 'branch-editor');
            },
            openEdit(branch) {
                this.editingId = branch.id;
                this.form = { name: branch.name, code: branch.code, address: branch.address, is_active: branch.is_active };
                $dispatch('open-modal', 'branch-editor');
            },
            toggleStatus(branch) {
                branch.is_active = !branch.is_active;
                this.notice = `${branch.name} (${branch.code}) ${branch.is_active ? 'activated' : 'deactivated'}.`;
            },
            saveBranch() {
                if (!this.form.name.trim() || !this.form.code.trim()) {
                    return;
                }
                const cleanCode = this.form.code.trim().toUpperCase();
                if (this.editingId) {
                    const target = this.branches.find((b) => b.id === this.editingId);
                    if (target) {
                        target.name = this.form.name.trim();
                        target.code = cleanCode;
                        target.address = this.form.address.trim();
                        target.is_active = this.form.is_active;
                    }
                    this.notice = `${this.form.name.trim()} updated.`;
                } else {
                    this.branches.push({
                        id: Date.now(),
                        name: this.form.name.trim(),
                        code: cleanCode,
                        address: this.form.address.trim(),
                        manager: 'Unassigned',
                        technicians: 0,
                        is_active: this.form.is_active,
                    });
                    this.notice = `${this.form.name.trim()} added.`;
                }
                $dispatch('close-modal', 'branch-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Governance" title="Salon Branches">
            <x-slot:actions>
                <button
                    type="button"
                    x-on:click="openCreate()"
                    class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer"
                >
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add Branch</span>
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
            <button type="button" x-on:click="notice = null" class="text-theme-muted hover:text-theme-primary cursor-pointer" aria-label="Dismiss">
                <x-ui.icon name="close" class="h-4 w-4" />
            </button>
        </div>

        <x-ui.card title="All Branches">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Search branches…"
                            class="sig-input rounded-xl py-2 pl-9 pr-3 text-xs w-52"
                        >
                    </div>
                    <select x-model="statusFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </x-slot:actions>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Branch</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Code</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Address</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Manager</th>
                            <th class="pb-3 text-center text-xs font-medium text-theme-muted">Team</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="branch in filtered" :key="branch.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4 font-medium text-theme-primary" x-text="branch.name"></td>
                                <td class="py-3.5 pr-4">
                                    <span class="rounded-md bg-gold-soft px-2.5 py-0.5 font-mono text-xs font-semibold text-gold-accent" x-text="branch.code"></span>
                                </td>
                                <td class="py-3.5 pr-4 text-theme-secondary max-w-[220px] truncate" x-text="branch.address || '—'"></td>
                                <td class="py-3.5 pr-4 text-theme-secondary" x-text="branch.manager"></td>
                                <td class="py-3.5 pr-4 text-center tabular-nums text-theme-secondary" x-text="branch.technicians"></td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="branch.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="branch.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            x-on:click="toggleStatus(branch)"
                                            :title="branch.is_active ? 'Deactivate' : 'Activate'"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="branch.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                            role="switch"
                                            :aria-checked="branch.is_active.toString()"
                                        >
                                            <span
                                                class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"
                                                :class="branch.is_active ? 'translate-x-4' : 'translate-x-0'"
                                            ></span>
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click="openEdit(branch)"
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
                                No matching branches found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="branch-editor" title="Branch Details" maxWidth="max-w-xl">
            <form x-on:submit.prevent="saveBranch()" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch Name</label>
                        <input type="text" x-model="form.name" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="Magugpo South">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch Code</label>
                        <input type="text" x-model="form.code" required maxlength="12" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm font-mono uppercase" placeholder="MGS">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Address</label>
                    <input type="text" x-model="form.address" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="Street, Barangay, Tagum City">
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active</span>
                </label>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'branch-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        <span x-text="editingId ? 'Save Changes' : 'Add Branch'"></span>
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
