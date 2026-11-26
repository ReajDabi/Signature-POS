<x-layouts.app title="Branches" subtitle="Manage all salon branches" portal="hq">

    @php
        $branches = [
            ['id' => 1, 'name' => 'Tagum City Center',  'code' => 'TCC', 'address' => 'National Hwy, Tagum City, Davao del Norte', 'status' => 'active',   'manager' => 'Ana Reyes',    'technicians' => 8],
            ['id' => 2, 'name' => 'Apokon',              'code' => 'APK', 'address' => 'Apokon Road, Tagum City, Davao del Norte',   'status' => 'active',   'manager' => 'Belen Cruz',   'technicians' => 6],
            ['id' => 3, 'name' => 'Visayan Village',     'code' => 'VSV', 'address' => 'Visayan Village, Tagum City',               'status' => 'active',   'manager' => 'Carmen Diaz',  'technicians' => 5],
        ];
    @endphp

    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-lg font-semibold text-theme-primary">All Branches</h2>
            <p class="text-xs text-theme-muted mt-0.5">{{ count($branches) }} branches registered</p>
        </div>
        <button
            type="button"
            x-on:click="$dispatch('open-modal', 'add-branch')"
            class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium cursor-pointer"
        >
            <x-ui.icon name="plus" class="h-4 w-4" />
            Add Branch
        </button>
    </div>

    {{-- Branches table --}}
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-theme">
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Branch</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Code</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Address</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Manager</th>
                        <th class="pb-3 text-center text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Technicians</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-theme">
                    @foreach ($branches as $branch)
                        <tr class="hover:bg-theme-elevated/50 transition" x-data="{ active: {{ $branch['status'] === 'active' ? 'true' : 'false' }} }">
                            <td class="py-3.5 pr-4 font-medium text-theme-primary">{{ $branch['name'] }}</td>
                            <td class="py-3.5 pr-4">
                                <span class="rounded-md bg-gold-soft px-2.5 py-0.5 font-mono text-xs font-semibold text-gold-accent">
                                    {{ $branch['code'] }}
                                </span>
                            </td>
                            <td class="py-3.5 pr-4 text-theme-secondary max-w-[220px]">{{ $branch['address'] }}</td>
                            <td class="py-3.5 pr-4 text-theme-secondary">{{ $branch['manager'] }}</td>
                            <td class="py-3.5 pr-4 text-center tabular-nums text-theme-secondary">{{ $branch['technicians'] }}</td>
                            <td class="py-3.5 pr-4">
                                <x-ui.status-badge :status="$branch['status']" />
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex items-center gap-3">
                                    {{-- Active toggle --}}
                                    <button
                                        type="button"
                                        x-on:click="active = !active"
                                        :title="active ? 'Deactivate branch' : 'Activate branch'"
                                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:ring-2"
                                        :class="active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                        role="switch"
                                        :aria-checked="active.toString()"
                                    >
                                        <span
                                            class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"
                                            :class="active ? 'translate-x-4' : 'translate-x-0'"
                                        ></span>
                                    </button>
                                    <button
                                        type="button"
                                        x-on:click="$dispatch('open-modal', 'add-branch')"
                                        class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                    >
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    {{-- Add Branch Modal --}}
    <x-ui.modal name="add-branch" title="Add New Branch" subtitle="Register a new salon branch under HQ" max-width="max-w-xl">
        <form class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch Name</label>
                    <input type="text" class="sig-input w-full" placeholder="e.g. Magugpo" />
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch Code</label>
                    <input type="text" class="sig-input w-full uppercase" placeholder="e.g. MGP" maxlength="4" />
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Address</label>
                <input type="text" class="sig-input w-full" placeholder="Street, City, Province" />
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Branch Manager</label>
                <input type="text" class="sig-input w-full" placeholder="Full name" />
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close-modal')" class="sig-btn-ghost rounded-xl px-4 py-2 text-sm cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-sm font-medium cursor-pointer">
                    Save Branch
                </button>
            </div>
        </form>
    </x-ui.modal>

</x-layouts.app>
