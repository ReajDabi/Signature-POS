<x-layouts.app title="Users &amp; Roles" subtitle="HQ and branch user accounts" portal="hq">

    @php
        $users = [
            ['name' => 'Rino Admin',     'email' => 'admin@signaturesalon.ph',   'role' => 'hq_admin',  'branch' => 'HQ Global',          'active' => true],
            ['name' => 'Ana Reyes',      'email' => 'ana.reyes@signaturesalon.ph','role' => 'manager',   'branch' => 'Tagum City Center',  'active' => true],
            ['name' => 'Belen Cruz',     'email' => 'belen.cruz@signaturesalon.ph','role' => 'manager',  'branch' => 'Apokon',             'active' => true],
            ['name' => 'Carmen Diaz',    'email' => 'carmen.diaz@signaturesalon.ph','role' => 'manager', 'branch' => 'Visayan Village',    'active' => true],
            ['name' => 'Maria Lim',      'email' => 'maria.lim@signaturesalon.ph', 'role' => 'cashier',  'branch' => 'Tagum City Center',  'active' => true],
            ['name' => 'Rosa Tan',       'email' => 'rosa.tan@signaturesalon.ph',  'role' => 'cashier',  'branch' => 'Apokon',             'active' => true],
            ['name' => 'Liza Morales',   'email' => 'liza.morales@signaturesalon.ph','role' => 'cashier','branch' => 'Visayan Village',    'active' => true],
            ['name' => 'Grace Santos',   'email' => 'grace.santos@signaturesalon.ph','role' => 'cashier','branch' => 'Tagum City Center',  'active' => false],
        ];

        $roleLabels = [
            'hq_admin' => ['label' => 'HQ Super Admin', 'classes' => 'bg-gold-soft text-gold-accent border-gold-500/30'],
            'manager'  => ['label' => 'Branch Manager',  'classes' => 'bg-blue-500/10 text-blue-700 dark:text-blue-300 border-blue-500/30'],
            'cashier'  => ['label' => 'Branch Cashier',  'classes' => 'bg-theme-elevated text-theme-secondary border-theme-strong'],
        ];
    @endphp

    {{-- Header & filters --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-lg font-semibold text-theme-primary">User Accounts</h2>
            <p class="text-xs text-theme-muted mt-0.5">{{ count($users) }} accounts · all portals</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Role filter --}}
            <select class="sig-input text-xs" x-data x-on:change="">
                <option value="">All Roles</option>
                <option value="hq_admin">HQ Super Admin</option>
                <option value="manager">Branch Manager</option>
                <option value="cashier">Branch Cashier</option>
            </select>
            {{-- Branch filter --}}
            <select class="sig-input text-xs">
                <option value="">All Branches</option>
                <option>Tagum City Center</option>
                <option>Apokon</option>
                <option>Visayan Village</option>
            </select>
            <button
                type="button"
                x-on:click="$dispatch('open-modal', 'add-user')"
                class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium cursor-pointer"
            >
                <x-ui.icon name="plus" class="h-4 w-4" />
                Add User
            </button>
        </div>
    </div>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-theme">
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Name</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Email</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Role</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Branch</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-theme">
                    @foreach ($users as $user)
                        @php $roleMeta = $roleLabels[$user['role']] ?? ['label' => $user['role'], 'classes' => '']; @endphp
                        <tr class="hover:bg-theme-elevated/50 transition" x-data="{ active: {{ $user['active'] ? 'true' : 'false' }} }">
                            <td class="py-3.5 pr-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-theme-strong bg-gold-soft text-xs font-semibold text-gold-accent">
                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                    </span>
                                    <span class="font-medium text-theme-primary">{{ $user['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 pr-4 text-theme-muted text-xs">{{ $user['email'] }}</td>
                            <td class="py-3.5 pr-4">
                                <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium {{ $roleMeta['classes'] }}">
                                    {{ $roleMeta['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 pr-4 text-theme-secondary">{{ $user['branch'] }}</td>
                            <td class="py-3.5 pr-4">
                                <x-ui.status-badge :status="$user['active'] ? 'active' : 'inactive'" />
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <button
                                        type="button"
                                        x-on:click="active = !active"
                                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                        :class="active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                        role="switch"
                                        :aria-checked="active.toString()"
                                    >
                                        <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="active ? 'translate-x-4' : 'translate-x-0'"></span>
                                    </button>
                                    <button
                                        type="button"
                                        x-on:click="$dispatch('open-modal', 'add-user')"
                                        class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                    >Edit</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    {{-- Add User Modal --}}
    <x-ui.modal name="add-user" title="Add User Account" subtitle="Create a new HQ or branch user">
        <form class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Full Name</label>
                    <input type="text" class="sig-input w-full" placeholder="First Last" />
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Email Address</label>
                    <input type="email" class="sig-input w-full" placeholder="user@signaturesalon.ph" />
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Role</label>
                    <select class="sig-input w-full">
                        <option value="hq_admin">HQ Super Admin</option>
                        <option value="manager">Branch Manager</option>
                        <option value="cashier">Branch Cashier</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch</label>
                    <select class="sig-input w-full">
                        <option value="">HQ Global</option>
                        <option>Tagum City Center</option>
                        <option>Apokon</option>
                        <option>Visayan Village</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Temporary Password</label>
                <input type="password" class="sig-input w-full" placeholder="Min. 8 characters" />
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close-modal')" class="sig-btn-ghost rounded-xl px-4 py-2 text-sm cursor-pointer">Cancel</button>
                <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-sm font-medium cursor-pointer">Create User</button>
            </div>
        </form>
    </x-ui.modal>

</x-layouts.app>
