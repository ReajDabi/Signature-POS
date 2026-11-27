<x-layouts.app title="Branch Staff" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $initialStaff = [
            ['id' => 2, 'name' => 'Tagum City Center Manager', 'email' => 'manager.center@signature.test', 'role' => 'branch_manager', 'is_active' => true, 'is_self' => true],
            ['id' => 3, 'name' => 'Ana Reyes', 'email' => 'cashier.center@signature.test', 'role' => 'branch_cashier', 'is_active' => true, 'is_self' => false],
            ['id' => 8, 'name' => 'Elena Bautista', 'email' => 'elena.tcc@signature.test', 'role' => 'branch_cashier', 'is_active' => false, 'is_self' => false],
        ];
    @endphp

    <div
        x-data="{
            staff: @js($initialStaff),
            search: '',
            roleFilter: 'all',
            notice: null,
            editingId: null,
            form: { name: '', email: '', password: '', is_active: true },
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.staff.filter((u) => {
                    const matchQ = !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q);
                    const matchRole = this.roleFilter === 'all' || u.role === this.roleFilter;
                    return matchQ && matchRole;
                });
            },
            initials(name) {
                return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p[0].toUpperCase()).join('');
            },
            openCreate() {
                this.editingId = null;
                this.form = { name: '', email: '', password: '', is_active: true };
                $dispatch('open-modal', 'staff-editor');
            },
            openEdit(member) {
                this.editingId = member.id;
                this.form = { name: member.name, email: member.email, password: '', is_active: member.is_active };
                $dispatch('open-modal', 'staff-editor');
            },
            saveStaff() {
                if (!this.form.name.trim() || !this.form.email.trim()) return;
                if (this.editingId) {
                    const m = this.staff.find((u) => u.id === this.editingId);
                    if (m) Object.assign(m, { name: this.form.name.trim(), email: this.form.email.trim(), is_active: m.is_self ? true : this.form.is_active });
                    this.notice = `${this.form.name.trim()} updated.`;
                } else {
                    this.staff.push({ id: Date.now(), name: this.form.name.trim(), email: this.form.email.trim(), role: 'branch_cashier', is_active: this.form.is_active, is_self: false });
                    this.notice = `${this.form.name.trim()} added as Branch Cashier.`;
                }
                $dispatch('close-modal', 'staff-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Team & Pricing" title="Branch Staff">
            <x-slot:actions>
                <button type="button" x-on:click="openCreate()" class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer">
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add Cashier</span>
                </button>
            </x-slot:actions>
        </x-ui.section-heading>

        <x-ui.notice />

        <x-ui.card>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input type="search" x-model="search" placeholder="Search staff…" class="sig-input rounded-xl py-2 pl-9 pr-3 text-xs w-52">
                    </div>
                    <select x-model="roleFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Roles</option>
                        <option value="branch_manager">Branch Manager</option>
                        <option value="branch_cashier">Branch Cashier</option>
                    </select>
                </div>
                <span class="text-xs text-theme-muted tabular-nums" x-text="filtered.length + ' staff'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Staff Member</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Email</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Role</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="member in filtered" :key="member.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-theme-strong bg-gold-soft text-xs font-semibold text-gold-accent" x-text="initials(member.name)"></span>
                                        <span class="font-medium text-theme-primary" x-text="member.name"></span>
                                    </div>
                                </td>
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-secondary" x-text="member.email"></td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-medium"
                                        :class="member.role === 'branch_manager'
                                            ? 'border-sky-500/35 bg-sky-500/12 text-sky-700 dark:text-sky-300'
                                            : 'border-theme-strong bg-theme-elevated text-theme-secondary'"
                                        x-text="member.role === 'branch_manager' ? 'Branch Manager' : 'Branch Cashier'"
                                    ></span>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="member.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="member.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            :disabled="member.is_self"
                                            x-on:click="if (!member.is_self) member.is_active = !member.is_active"
                                            class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="[member.is_active ? 'bg-emerald-500' : 'bg-theme-elevated', member.is_self ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"
                                            role="switch"
                                            :aria-checked="member.is_active.toString()"
                                        >
                                            <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="member.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                        <button type="button" x-on:click="openEdit(member)" class="text-xs font-medium text-gold-accent hover:underline cursor-pointer">
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="staff-editor" title="Branch Cashier Account" maxWidth="max-w-md">
            <form x-on:submit.prevent="saveStaff()" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Full Name</label>
                    <input type="text" x-model="form.name" required placeholder="Ana Reyes" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Email</label>
                    <input type="email" x-model="form.email" required placeholder="cashier@signature.test" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Password</label>
                    <input type="password" x-model="form.password" :required="!editingId" placeholder="••••••••" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active</span>
                </label>
                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'staff-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">Cancel</button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">Save Account</button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
