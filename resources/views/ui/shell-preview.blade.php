@php
    $portalLabel = match ($portal) {
        'manager' => 'Branch Manager Shell',
        'cashier' => 'Branch Cashier POS Shell',
        default => 'HQ Super Admin Shell',
    };

    $demoInventory = [
        ['product' => 'Rebond Cream', 'unit' => 'jar', 'qty' => '2.00', 'threshold' => '2.00', 'status' => 'low'],
        ['product' => 'Hair Color Tube', 'unit' => 'tube', 'qty' => '0.00', 'threshold' => '10.00', 'status' => 'out'],
        ['product' => 'Hair Serum 100ml', 'unit' => 'bottle', 'qty' => '24.00', 'threshold' => '5.00', 'status' => 'ok'],
    ];
@endphp

<x-layouts.app
    title="UI Foundation & Theme System"
    subtitle="{{ $portalLabel }} · Dark Glam & Champagne Ivory component verification"
    :portal="$portal"
>
    <div class="space-y-8">
        {{-- Section 1: KPI Cards under Labeled Heading --}}
        <section class="space-y-4">
            <x-ui.section-heading
                eyebrow="Design System · UI Step 1"
                title="Typography, Cards & Theme Tokens"
                description="Cormorant Garamond display numerals, Inter UI text, and synchronized Light/Dark switching."
            >
                <x-slot:actions>
                    <button
                        type="button"
                        x-data
                        x-on:click="$dispatch('open-modal', 'foundation-modal')"
                        class="sig-btn-gold inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs cursor-pointer"
                    >
                        <x-ui.icon name="plus" class="h-4 w-4" />
                        <span>Preview Modal</span>
                    </button>
                    <a href="{{ route('login') }}" class="sig-btn-ghost inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-medium">
                        <span>Back to Login</span>
                    </a>
                </x-slot:actions>
            </x-ui.section-heading>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <x-ui.stat-card
                    label="Today's Gross Sales"
                    value="₱ 28,450.00"
                    hint="Cash-only · No VAT v1"
                    icon="cash"
                    badge="Tagum Network"
                />
                <x-ui.stat-card
                    label="Active Branches"
                    value="3"
                    hint="TCC · APK · VSV"
                    icon="branches"
                    badge="All Online"
                    badgeTone="emerald"
                />
                <x-ui.stat-card
                    label="Low Stock Alerts"
                    value="4"
                    hint="At or below threshold"
                    icon="inventory"
                    badge="Restock Due"
                    badgeTone="amber"
                />
                <x-ui.stat-card
                    label="Pending Payouts"
                    value="₱ 4,120.50"
                    hint="Flat % per line item"
                    icon="technicians"
                    badge="4 Technicians"
                />
            </div>
        </section>

        {{-- Section 2: Low-Stock 9px Radius Badges & Statuses --}}
        <section class="space-y-4">
            <x-ui.section-heading
                eyebrow="Inventory & Governance Badges"
                title="9px Low-Stock Boxes & Status Pills"
                description="Strict 9px box radius for stock alerts alongside transaction and payout state badges."
            />

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <x-ui.card title="Low-Stock Alert Boxes (9px Radius)" subtitle="Rendered via <x-ui.low-stock-badge />">
                    <div class="space-y-3">
                        @foreach ($demoInventory as $row)
                            <div class="sig-elevated flex flex-wrap items-center justify-between gap-3 rounded-xl px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-theme-primary">{{ $row['product'] }}</p>
                                    <p class="text-xs text-theme-muted">Threshold: {{ $row['threshold'] }} {{ $row['unit'] }}</p>
                                </div>
                                <x-ui.low-stock-badge :status="$row['status']" :qty="$row['qty']" :unit="$row['unit']" />
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="Operational Statuses & Error Reference Banner" subtitle="Consistent across HQ, Branch Manager, and Cashier">
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <x-ui.status-badge status="active" />
                            <x-ui.status-badge status="completed" />
                            <x-ui.status-badge status="paid" />
                            <x-ui.status-badge status="unpaid" />
                            <x-ui.status-badge status="voided" />
                            <x-ui.status-badge status="inactive" />
                        </div>

                        <div class="rounded-xl border border-rose-500/40 bg-rose-500/12 px-4 py-3 text-sm text-rose-800 dark:text-rose-200">
                            <div class="flex items-start gap-3">
                                <x-ui.icon name="alert" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" />
                                <div>
                                    <p class="font-medium">Sample BusinessException Banner</p>
                                    <p class="mt-0.5 text-xs opacity-85">
                                        Insufficient Rebond Cream stock for requested quantity.
                                        Reference: <span class="font-mono font-semibold">8K3M9P2Q</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </div>

    <x-ui.modal name="foundation-modal" title="Signature Modal Shell" subtitle="Press Escape or click outside to close">
        <div class="space-y-4 text-sm text-theme-secondary">
            <p>
                This shared Alpine.js modal container adapts automatically between
                <span class="font-semibold text-theme-primary">Dark Glam</span> and
                <span class="font-semibold text-theme-primary">Champagne Light Mode</span>.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button
                    type="button"
                    x-data
                    x-on:click="$dispatch('close-modal', 'foundation-modal')"
                    class="sig-btn-ghost rounded-xl px-4 py-2 text-xs font-medium cursor-pointer"
                >
                    Close
                </button>
                <button
                    type="button"
                    x-data
                    x-on:click="$dispatch('close-modal', 'foundation-modal')"
                    class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer"
                >
                    Looks Good
                </button>
            </div>
        </div>
    </x-ui.modal>
</x-layouts.app>
