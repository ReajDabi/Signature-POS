<x-layouts.app title="Global Products" subtitle="Master product & ingredient catalog" portal="hq">

    @php
        $products = [
            ['id' => 1, 'name' => 'Hair Color 50ml',         'unit' => 'tube',   'active' => true,  'used_in' => 3, 'stock' => 'ok'],
            ['id' => 2, 'name' => 'Developer 100ml',          'unit' => 'bottle', 'active' => true,  'used_in' => 2, 'stock' => 'ok'],
            ['id' => 3, 'name' => 'Bleaching Powder 500g',    'unit' => 'pack',   'active' => true,  'used_in' => 1, 'stock' => 'low'],
            ['id' => 4, 'name' => 'Keratin Treatment 300ml',  'unit' => 'bottle', 'active' => true,  'used_in' => 1, 'stock' => 'low'],
            ['id' => 5, 'name' => 'Gel Base Coat 50ml',       'unit' => 'bottle', 'active' => true,  'used_in' => 2, 'stock' => 'out'],
            ['id' => 6, 'name' => 'Gel Top Coat 50ml',        'unit' => 'bottle', 'active' => true,  'used_in' => 2, 'stock' => 'ok'],
            ['id' => 7, 'name' => 'Nail Polish Remover 1L',   'unit' => 'bottle', 'active' => true,  'used_in' => 0, 'stock' => 'out'],
            ['id' => 8, 'name' => 'Facial Serum 30ml',        'unit' => 'bottle', 'active' => true,  'used_in' => 1, 'stock' => 'low'],
            ['id' => 9, 'name' => 'Facial Toner 50ml',        'unit' => 'bottle', 'active' => true,  'used_in' => 1, 'stock' => 'ok'],
            ['id' => 10,'name' => 'Wax Strip Roll 100pcs',    'unit' => 'roll',   'active' => true,  'used_in' => 1, 'stock' => 'low'],
            ['id' => 11,'name' => 'Hair Toner 200ml',         'unit' => 'bottle', 'active' => false, 'used_in' => 0, 'stock' => 'low'],
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-lg font-semibold text-theme-primary">Product Catalog</h2>
            <p class="text-xs text-theme-muted mt-0.5">{{ count($products) }} products · HQ master list</p>
        </div>
        <button
            type="button"
            x-on:click="$dispatch('open-modal', 'add-product')"
            class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium cursor-pointer"
        >
            <x-ui.icon name="plus" class="h-4 w-4" />
            Add Product
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4" x-data="{ q: '' }">
        <div class="relative max-w-sm">
            <x-ui.icon name="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
            <input
                type="search"
                x-model="q"
                class="sig-input w-full pl-9"
                placeholder="Search products…"
            />
        </div>
    </div>

    {{-- Table --}}
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-theme">
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Product Name</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Unit</th>
                        <th class="pb-3 text-center text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Used In (Services)</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted pl-4">Stock Health</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Status</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase tracking-[0.12em] text-theme-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-theme">
                    @foreach ($products as $prod)
                        <tr class="hover:bg-theme-elevated/50 transition" x-data="{ active: {{ $prod['active'] ? 'true' : 'false' }} }">
                            <td class="py-3.5 pr-4 font-medium text-theme-primary">{{ $prod['name'] }}</td>
                            <td class="py-3.5 pr-4 text-theme-muted text-xs uppercase tracking-wider">{{ $prod['unit'] }}</td>
                            <td class="py-3.5 pr-4 text-center">
                                @if ($prod['used_in'] > 0)
                                    <span class="inline-flex items-center rounded-full bg-gold-soft px-2.5 py-0.5 text-xs font-semibold text-gold-accent tabular-nums">
                                        {{ $prod['used_in'] }} service{{ $prod['used_in'] > 1 ? 's' : '' }}
                                    </span>
                                @else
                                    <span class="text-xs text-theme-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 pl-4 pr-4">
                                <x-ui.low-stock-badge :status="$prod['stock']" />
                            </td>
                            <td class="py-3.5 pr-4">
                                <x-ui.status-badge :status="$prod['active'] ? 'active' : 'inactive'" />
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
                                        x-on:click="$dispatch('open-modal', 'add-product')"
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

    {{-- Add Product Modal --}}
    <x-ui.modal name="add-product" title="Add Global Product" subtitle="Ingredient or consumable tracked across all branches">
        <form class="space-y-4">
            <div>
                <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Product Name</label>
                <input type="text" class="sig-input w-full" placeholder="e.g. Hair Color 50ml" />
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Unit of Measure</label>
                <select class="sig-input w-full">
                    <option>bottle</option>
                    <option>tube</option>
                    <option>pack</option>
                    <option>roll</option>
                    <option>piece</option>
                    <option>ml</option>
                    <option>g</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close-modal')" class="sig-btn-ghost rounded-xl px-4 py-2 text-sm cursor-pointer">Cancel</button>
                <button type="submit" class="sig-btn-gold rounded-xl px-4 py-2 text-sm font-medium cursor-pointer">Save Product</button>
            </div>
        </form>
    </x-ui.modal>

</x-layouts.app>
