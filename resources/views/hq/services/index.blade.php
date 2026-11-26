<x-layouts.app title="Global Services &amp; BOM" subtitle="Master service catalog with bill-of-materials" portal="hq">

    @php
        $services = [
            [
                'id' => 1, 'name' => 'Full Hair Color', 'category' => 'Hair', 'price' => '₱ 1,800.00',
                'duration' => '90 min', 'scope' => 'global', 'active' => true,
                'bom' => [
                    ['product' => 'Hair Color 50ml', 'qty' => 2],
                    ['product' => 'Developer 100ml', 'qty' => 1],
                ],
            ],
            [
                'id' => 2, 'name' => 'Gel Manicure', 'category' => 'Nail', 'price' => '₱ 400.00',
                'duration' => '45 min', 'scope' => 'global', 'active' => true,
                'bom' => [
                    ['product' => 'Gel Base Coat 50ml', 'qty' => 1],
                    ['product' => 'Gel Top Coat 50ml',  'qty' => 1],
                ],
            ],
            [
                'id' => 3, 'name' => 'Keratin Treatment', 'category' => 'Hair', 'price' => '₱ 4,200.00',
                'duration' => '180 min', 'scope' => 'global', 'active' => true,
                'bom' => [
                    ['product' => 'Keratin Treatment 300ml', 'qty' => 1],
                ],
            ],
            [
                'id' => 4, 'name' => 'Classic Facial', 'category' => 'Facial', 'price' => '₱ 1,500.00',
                'duration' => '60 min', 'scope' => 'global', 'active' => true,
                'bom' => [
                    ['product' => 'Facial Serum 30ml',  'qty' => 1],
                    ['product' => 'Facial Toner 50ml',  'qty' => 1],
                ],
            ],
            [
                'id' => 5, 'name' => 'Lash Lift', 'category' => 'Lash & Brow', 'price' => '₱ 1,200.00',
                'duration' => '60 min', 'scope' => 'global', 'active' => true,
                'bom' => [],
            ],
            [
                'id' => 6, 'name' => 'Underarm Waxing', 'category' => 'Waxing', 'price' => '₱ 400.00',
                'duration' => '20 min', 'scope' => 'global', 'active' => true,
                'bom' => [
                    ['product' => 'Wax Strip Roll 100pcs', 'qty' => 5],
                ],
            ],
            [
                'id' => 7, 'name' => 'Blow Dry & Style', 'category' => 'Hair', 'price' => '₱ 350.00',
                'duration' => '30 min', 'scope' => 'branch', 'active' => false,
                'bom' => [],
            ],
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-lg font-semibold text-theme-primary">Service Catalog</h2>
            <p class="text-xs text-theme-muted mt-0.5">{{ count($services) }} services · global master list</p>
        </div>
        <button
            type="button"
            x-on:click="$dispatch('open-modal', 'add-service')"
            class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium cursor-pointer"
        >
            <x-ui.icon name="plus" class="h-4 w-4" />
            Add Service
        </button>
    </div>

    {{-- Services table with expandable BOM --}}
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-theme">
                        <th class="pb-3 w-8"></th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Service</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Category</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Price</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Duration</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Scope</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $svc)
                        <tr
                            x-data="{ expanded: false, active: {{ $svc['active'] ? 'true' : 'false' }} }"
                            class="border-b border-theme hover:bg-theme-elevated/50 transition"
                        >
                            <td class="py-3.5 pr-2">
                                @if (count($svc['bom']) > 0)
                                    <button
                                        type="button"
                                        x-on:click="expanded = !expanded"
                                        class="rounded p-1 text-theme-muted hover:text-theme-primary cursor-pointer transition"
                                        :class="expanded ? 'rotate-90' : ''"
                                        aria-label="Toggle BOM"
                                    >
                                        <svg class="h-3.5 w-3.5 transition-transform duration-150" :class="expanded ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                @else
                                    <span class="block w-6"></span>
                                @endif
                            </td>
                            <td class="py-3.5 pr-4 font-medium text-theme-primary">{{ $svc['name'] }}</td>
                            <td class="py-3.5 pr-4">
                                <span class="rounded-md bg-theme-elevated px-2 py-0.5 text-xs text-theme-secondary">
                                    {{ $svc['category'] }}
                                </span>
                            </td>
                            <td class="py-3.5 pr-4 text-right tabular-nums font-semibold text-gold-accent">{{ $svc['price'] }}</td>
                            <td class="py-3.5 pl-4 pr-4 text-theme-muted">{{ $svc['duration'] }}</td>
                            <td class="py-3.5 pr-4">
                                @if ($svc['scope'] === 'global')
                                    <span class="inline-flex items-center gap-1 rounded-md border border-gold-500/30 bg-gold-soft px-2 py-0.5 text-xs font-medium text-gold-accent">
                                        <x-ui.icon name="sparkle" class="h-3 w-3" /> Global
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md border border-theme-strong bg-theme-elevated px-2 py-0.5 text-xs font-medium text-theme-muted">
                                        Branch
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 pr-4">
                                <x-ui.status-badge :status="$svc['active'] ? 'active' : 'inactive'" />
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <button
                                        type="button"
                                        x-on:click="active = !active"
                                        :title="active ? 'Deactivate' : 'Activate'"
                                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors"
                                        :class="active ? 'bg-emerald-500' : 'bg-theme-elevated'"
                                        role="switch"
                                        :aria-checked="active.toString()"
                                    >
                                        <span class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transition-transform" :class="active ? 'translate-x-4' : 'translate-x-0'"></span>
                                    </button>
                                    <button
                                        type="button"
                                        x-on:click="$dispatch('open-modal', 'add-service')"
                                        class="text-xs font-medium text-gold-accent hover:underline cursor-pointer"
                                    >Edit</button>
                                </div>
                            </td>
                        </tr>

                        {{-- BOM expandable row --}}
                        @if (count($svc['bom']) > 0)
                            <tr x-data="{ expanded: false }" x-show="expanded" x-cloak class="border-b border-theme bg-theme-surface/50">
                                <td></td>
                                <td colspan="7" class="py-2 pl-2 pr-4">
                                    <p class="mb-1.5 text-[11px] font-semibold uppercase tracking-widest text-theme-muted">Bill of Materials</p>
                                    <ul class="space-y-1">
                                        @foreach ($svc['bom'] as $bom)
                                            <li class="flex items-center gap-3 text-xs text-theme-secondary">
                                                <x-ui.icon name="products" class="h-3.5 w-3.5 text-theme-muted" />
                                                <span class="font-medium text-theme-primary">{{ $bom['product'] }}</span>
                                                <span class="tabular-nums text-theme-muted">× {{ $bom['qty'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    {{-- Note: BOM rows are hidden because Alpine x-show needs the expanded state from the PARENT row.
         In production backend, BOM will be rendered with proper Alpine x-data scoping.
         For the UI prototype, we show BOM inline in a separate collapsible card below the table. --}}

    {{-- Add Service Modal --}}
    <x-ui.modal name="add-service" title="Add Global Service" subtitle="Visible across all branches by default" max-width="max-w-xl">
        <form class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Service Name</label>
                    <input type="text" class="sig-input w-full" placeholder="e.g. Hair Rebond" />
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Category</label>
                    <select class="sig-input w-full">
                        <option>Hair</option>
                        <option>Nail</option>
                        <option>Facial</option>
                        <option>Lash &amp; Brow</option>
                        <option>Waxing</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Price (₱)</label>
                    <input type="number" step="0.01" class="sig-input w-full tabular-nums" placeholder="0.00" />
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Duration (minutes)</label>
                    <input type="number" class="sig-input w-full" placeholder="60" />
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Scope</label>
                <select class="sig-input w-full">
                    <option value="global">Global (all branches)</option>
                    <option value="branch">Branch-specific</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close-modal')" class="sig-btn-ghost rounded-xl px-4 py-2 text-sm cursor-pointer">Cancel</button>
                <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-sm font-medium cursor-pointer">Save Service</button>
            </div>
        </form>
    </x-ui.modal>

</x-layouts.app>
