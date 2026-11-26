<x-layouts.app title="Users & Roles" portal="hq">
    @php
        $initialUsers = [
            ['id' => 1, 'name' => 'HQ Admin', 'email' => 'hq@signature.test', 'role' => 'hq_admin', 'branch_code' => 'HQ', 'branch_name' => 'HQ Global', 'is_active' => true],
            ['id' => 2, 'name' => 'Tagum City Center Manager', 'email' => 'manager.center@signature.test', 'role' => 'branch_manager', 'branch_code' => 'TCC', 'branch_name' => 'Tagum City Center', 'is_active' => true],
            ['id' => 3, 'name' => 'Tagum City Center Cashier', 'email' => 'cashier.center@signature.test', 'role' => 'branch_cashier', 'branch_code' => 'TCC', 'branch_name' => 'Tagum City Center', 'is_active' => true],
            ['id' => 4, 'name' => 'Apokon Manager', 'email' => 'manager.apokon@signature.test', 'role' => 'branch_manager', 'branch_code' => 'APK', 'branch_name' => 'Apokon', 'is_active' => true],
            ['id' => 5, 'name' => 'Apokon Cashier', 'email' => 'cashier.apokon@signature.test', 'role' => 'branch_cashier', 'branch_code' => 'APK', 'branch_name' => 'Apokon', 'is_active' => true],
            ['id' => 6, 'name' => 'Visayan Village Manager', 'email' => 'manager.visayan@signature.test', 'role' => 'branch_manager', 'branch_code' => 'VSV', 'branch_name' => 'Visayan Village', 'is_active' => true],
            ['id' => 7, 'name' => 'Visayan Village Cashier', 'email' => 'cashier.visayan@signature.test', 'role' => 'branch_cashier', 'branch_code' => 'VSV', 'branch_name' => 'Visayan Village', 'is_active' => true],
            ['id' => 8, 'name' => 'Grace Santos', 'email' => 'grace.santos@signature.test', 'role' => 'branch_cashier', 'branch_code' => 'TCC', 'branch_name' => 'Tagum City Center', 'is_active' => false],
        ];
    @endphp

    <div
        x-data="{
            users: @js($initialUsers),
            search: '',
            roleFilter: 'all',
            branchFilter: 'all',
            notice: null,
            editingId: null,
            form: { name: '', email: '', role: 'branch_cashier', branch_code: 'TCC', password: '', is_active: true },
            branchNames: { HQ: 'HQ Global', TCC: 'Tagum City Center', APK: 'Apokon', VSV: 'Visayan Village' },
            roleLabel(role) {
                return { hq_admin: 'HQ Super Admin', branch_manager: 'Branch Manager', branch_cashier: 'Branch Cashier' }[role] || role;
            },
            initials(name) {
                return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p[0].toUpperCase()).join('');
            },
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.users.filter((u) => {
                    const matchQ = !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q);
                    const matchRole = this.roleFilter === 'all' || u.role === this.roleFilter;
                    const matchBranch = this.branchFilter === 'all' || u.branch_code === this.branchFilter;
                    return matchQ && matchRole && matchBranch;
                });
            },
            openCreate() {
                this.editingId = null;
                this.form = { name: '', email: '', role: 'branch_cashier', branch_code: 'TCC', password: '', is_active: true };
                $dispatch('open-modal', 'user-editor');
            },
            openEdit(user) {
                this.editingId = user.id;
                this.form = { name: user.name, email: user.email, role: user.role, branch_code: user.branch_code, password: '', is_active: user.is_active };
                $dispatch('open-modal', 'user-editor');
            },
            saveUser() {
                if (!this.form.name.trim() || !this.form.email.trim()) {
                    return;
                }
                const branchCode = this.form.role === 'hq_admin' ? 'HQ' : (this.form.branch_code === 'HQ' ? 'TCC' : this.form.branch_code);
                const branchName = this.branchNames[branchCode] || branchCode;
                if (this.editingId) {
                    const target = this.users.find((u) => u.id === this.editingId);
                    if (target) {
                        Object.assign(target, {
                            name: this.form.name.trim(),
                            email: this.form.email.trim(),
                            role: this.form.role,
                            branch_code: branchCode,
                            branch_name: branchName,
                            is_active: this.form.is_active,
                        });
                    }
                    this.notice = `${this.form.name.trim()} updated.`;
                } else {
                    this.users.push({
                        id: Date.now(),
                        name: this.form.name.trim(),
                        email: this.form.email.trim(),
                        role: this.form.role,
                        branch_code: branchCode,
                        branch_name: branchName,
                        is_active: this.form.is_active,
                    });
                    this.notice = `${this.form.name.trim()} added.`;
                }
                $dispatch('close-modal', 'user-editor');
            },
        }"
        class="space-y-6"
    >
        <x-ui.section-heading eyebrow="Access Control" title="Team & Roles">
            <x-slot:actions>
                <button
                    type="button"
                    x-on:click="openCreate()"
                    class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer"
                >
                    <x-ui.icon name="plus" class="h-4 w-4" />
                    <span>Add User</span>
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

        <x-ui.card title="User Accounts">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Search users…"
                            class="sig-input rounded-xl py-2 pl-9 pr-3 text-xs w-44"
                        >
                    </div>
                    <select x-model="roleFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Roles</option>
                        <option value="hq_admin">HQ Super Admin</option>
                        <option value="branch_manager">Branch Manager</option>
                        <option value="branch_cashier">Branch Cashier</option>
                    </select>
                    <select x-model="branchFilter" class="sig-input rounded-xl px-3 py-2 text-xs">
                        <option value="all">All Branches</option>
                        <option value="HQ">HQ Global</option>
                        <option value="TCC">TCC</option>
                        <option value="APK">APK</option>
                        <option value="VSV">VSV</option>
                    </select>
                </div>
            </x-slot:actions>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-theme">
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">User</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Email</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Role</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Branch</th>
                            <th class="pb-3 text-left text-xs font-medium text-theme-muted">Status</th>
                            <th class="pb-3 text-right text-xs font-medium text-theme-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        <template x-for="user in filtered" :key="user.id">
                            <tr class="hover:bg-theme-elevated/45 transition">
                                <td class="py-3.5 pr-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-theme-strong bg-gold-soft text-xs font-semibold text-gold-accent"
                                            x-text="initials(user.name)"
                                        ></span>
                                        <span class="font-medium text-theme-primary" x-text="user.name"></span>
                                    </div>
                                </td>
                                <td class="py-3.5 pr-4 font-mono text-xs text-theme-muted" x-text="user.email"></td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="{
                                            'border-gold-500/35 bg-gold-soft text-gold-accent': user.role === 'hq_admin',
                                            'border-sky-500/35 bg-sky-500/10 text-sky-700 dark:text-sky-300': user.role === 'branch_manager',
                                            'border-theme-strong bg-theme-elevated text-theme-secondary': user.role === 'branch_cashier',
                                        }"
                                        x-text="roleLabel(user.role)"
                                    ></span>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span class="rounded-md bg-gold-soft px-2 py-0.5 font-mono text-xs font-semibold text-gold-accent" x-text="user.branch_code"></span>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium"
                                        :class="user.is_active
                                            ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                            : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                        x-text="user.is_active ? 'Active' : 'Inactive'"
                                    ></span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <button
                                            type="button"
                                            x-on:click="user.is_active = !user.is_active"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                            :class="user.is_active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                            role="switch"
                                            :aria-checked="user.is_active.toString()"
                                        >
                                            <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="user.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click="openEdit(user)"
                                            class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0" x-cloak>
                            <td colspan="6" class="py-8 text-center text-xs text-theme-muted">
                                No matching users found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.modal name="user-editor" title="User Account" maxWidth="max-w-xl">
            <form x-on:submit.prevent="saveUser()" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Full Name</label>
                        <input type="text" x-model="form.name" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="Full Name">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Email</label>
                        <input type="email" x-model="form.email" required class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="user@signature.test">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Role</label>
                        <select x-model="form.role" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="hq_admin">HQ Super Admin</option>
                            <option value="branch_manager">Branch Manager</option>
                            <option value="branch_cashier">Branch Cashier</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch</label>
                        <select
                            x-model="form.branch_code"
                            :disabled="form.role === 'hq_admin'"
                            class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm disabled:opacity-55"
                        >
                            <option value="HQ" x-show="form.role === 'hq_admin'">All Branches (HQ)</option>
                            <option value="TCC">Tagum City Center (TCC)</option>
                            <option value="APK">Apokon (APK)</option>
                            <option value="VSV">Visayan Village (VSV)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary" x-text="editingId ? 'New Password (Optional)' : 'Password'"></label>
                    <input type="password" x-model="form.password" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm" placeholder="Min. 8 characters">
                </div>
                <label class="inline-flex items-center gap-2.5 text-xs text-theme-secondary cursor-pointer select-none">
                    <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-theme accent-[#d9b77a]">
                    <span>Active</span>
                </label>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'user-editor')" class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        <span x-text="editingId ? 'Save Changes' : 'Create User'"></span>
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-layouts.app>
