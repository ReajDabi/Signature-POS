@props([
    'title' => 'Dashboard',
    'subtitle' => null,
    'portal' => 'hq',
    'branchLabel' => null,
    'userName' => null,
    'userRoleLabel' => null,
])

@php
    $portalMeta = match ($portal) {
        'manager' => [
            'scopeBadge' => $branchLabel ?? 'Tagum City Center · TCC',
            'roleLabel' => $userRoleLabel ?? 'Branch Manager',
            'userName' => $userName ?? 'Tagum City Center Manager',
            'groups' => [
                [
                    'label' => 'Branch Pulse',
                    'items' => [
                        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'manager.dashboard', 'active' => 'manager.dashboard'],
                        ['label' => 'Transactions', 'icon' => 'transactions', 'route' => 'manager.transactions.index', 'active' => 'manager.transactions.*'],
                    ],
                ],
                [
                    'label' => 'Inventory & Stock',
                    'items' => [
                        ['label' => 'Branch Inventory', 'icon' => 'inventory', 'route' => 'manager.inventory.index', 'active' => 'manager.inventory.*'],
                        ['label' => 'CSV Imports', 'icon' => 'import', 'route' => 'manager.imports.index', 'active' => 'manager.imports.*'],
                    ],
                ],
                [
                    'label' => 'Team & Pricing',
                    'items' => [
                        ['label' => 'Technicians & Commissions', 'icon' => 'technicians', 'route' => 'manager.technicians.index', 'active' => 'manager.technicians.*'],
                        ['label' => 'Services & Discounts', 'icon' => 'discounts', 'route' => 'manager.catalog.index', 'active' => 'manager.catalog.*'],
                        ['label' => 'Branch Staff', 'icon' => 'users', 'route' => 'manager.staff.index', 'active' => 'manager.staff.*'],
                    ],
                ],
                [
                    'label' => 'Compliance',
                    'items' => [
                        ['label' => 'Branch Audit Log', 'icon' => 'audit', 'route' => 'manager.audit.index', 'active' => 'manager.audit.*'],
                    ],
                ],
            ],
        ],
        'cashier' => [
            'scopeBadge' => $branchLabel ?? 'Tagum City Center · TCC',
            'roleLabel' => $userRoleLabel ?? 'Branch Cashier',
            'userName' => $userName ?? 'Tagum City Center Cashier',
            'groups' => [
                [
                    'label' => 'Point of Sale',
                    'items' => [
                        ['label' => 'POS Terminal', 'icon' => 'pos', 'route' => 'cashier.pos', 'active' => 'cashier.pos'],
                        ['label' => 'Shift Transactions', 'icon' => 'transactions', 'route' => 'cashier.transactions.index', 'active' => 'cashier.transactions.*'],
                    ],
                ],
            ],
        ],
        default => [
            'scopeBadge' => $branchLabel ?? 'HQ Global Scope',
            'roleLabel' => $userRoleLabel ?? 'HQ Super Admin',
            'userName' => $userName ?? 'HQ Admin',
            'groups' => [
                [
                    'label' => 'Overview',
                    'items' => [
                        ['label' => 'Executive Dashboard', 'icon' => 'dashboard', 'route' => 'hq.dashboard', 'active' => 'hq.dashboard'],
                        ['label' => 'Cross-Branch Reports', 'icon' => 'reports', 'route' => 'hq.reports.index', 'active' => 'hq.reports.*'],
                    ],
                ],
                [
                    'label' => 'Branch Governance',
                    'items' => [
                        ['label' => 'Branches', 'icon' => 'branches', 'route' => 'hq.branches.index', 'active' => 'hq.branches.*'],
                        ['label' => 'Users & Roles', 'icon' => 'users', 'route' => 'hq.users.index', 'active' => 'hq.users.*'],
                    ],
                ],
                [
                    'label' => 'Master Catalog',
                    'items' => [
                        ['label' => 'Global Services & BOM', 'icon' => 'services', 'route' => 'hq.services.index', 'active' => 'hq.services.*'],
                        ['label' => 'Global Products', 'icon' => 'products', 'route' => 'hq.products.index', 'active' => 'hq.products.*'],
                    ],
                ],
                [
                    'label' => 'Compliance',
                    'items' => [
                        ['label' => 'Audit Trail', 'icon' => 'audit', 'route' => 'hq.audit.index', 'active' => 'hq.audit.*'],
                    ],
                ],
            ],
        ],
    };

    $initials = collect(explode(' ', $portalMeta['userName']))
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<x-layouts.base :title="$title">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        {{-- Mobile drawer backdrop --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-on:click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/65 backdrop-blur-xs lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Sidebar navigation --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="sig-sidebar fixed inset-y-0 left-0 z-50 flex w-64 shrink-0 flex-col border-r transition-transform duration-200 lg:static"
        >
            {{-- Brand header --}}
            <div class="flex items-center justify-between gap-3 border-b border-theme px-5 py-5">
                @php
                    $homeUrl = match (true) {
                        Route::has($portal.'.dashboard') => route($portal.'.dashboard'),
                        $portal === 'cashier' && Route::has('cashier.pos') => route('cashier.pos'),
                        Route::has('ui.foundation') => route('ui.foundation', ['portal' => $portal]),
                        default => url('/'),
                    };
                @endphp
                <a href="{{ $homeUrl }}" class="flex items-center gap-3 min-w-0">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-theme-strong bg-gold-soft font-display text-xl font-bold text-gold-accent">
                        S
                    </span>
                    <div class="min-w-0">
                        <p class="truncate font-display text-lg font-semibold tracking-tight text-theme-primary leading-none">
                            Signature
                        </p>
                        <p class="mt-1 truncate text-[10px] font-medium uppercase tracking-[0.18em] text-gold-accent">
                            Styled in the Cloud
                        </p>
                    </div>
                </a>

                <button
                    type="button"
                    x-on:click="sidebarOpen = false"
                    class="rounded-lg p-1.5 text-theme-muted hover:bg-theme-elevated hover:text-theme-primary lg:hidden cursor-pointer"
                    aria-label="Close navigation"
                >
                    <x-ui.icon name="close" class="h-5 w-5" />
                </button>
            </div>

            {{-- Active branch / scope pill (mobile drawer only; desktop displays in header) --}}
            <div class="px-4 pt-4 sm:hidden">
                <div class="sig-elevated flex items-center justify-between gap-2 rounded-xl px-3.5 py-2.5">
                    <p class="truncate text-xs font-semibold text-theme-primary">
                        {{ $portalMeta['scopeBadge'] }}
                    </p>
                    <span class="h-2 w-2 shrink-0 rounded-full bg-emerald-500" title="Online"></span>
                </div>
            </div>

            {{-- Labeled navigation groups --}}
            <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6" aria-label="Main navigation">
                @foreach ($portalMeta['groups'] as $group)
                    <div class="space-y-1.5">
                        <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-theme-muted">
                            {{ $group['label'] }}
                        </p>
                        <ul class="space-y-1">
                            @foreach ($group['items'] as $index => $item)
                                @php
                                    $routeExists = Route::has($item['route']);
                                    $href = $routeExists ? route($item['route']) : '#';
                                    $isActive = $routeExists
                                        ? request()->routeIs($item['active'])
                                        : ($loop->parent->first && $index === 0 && request()->routeIs('ui.foundation'));
                                @endphp
                                <li>
                                    <a
                                        href="{{ $href }}"
                                        @class([
                                            'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                            'bg-gold-soft text-gold-accent border border-theme-strong' => $isActive,
                                            'text-theme-secondary hover:bg-theme-elevated hover:text-theme-primary border border-transparent' => ! $isActive,
                                            'opacity-65' => ! $routeExists && ! $isActive,
                                        ])
                                    >
                                        <x-ui.icon :name="$item['icon']" class="h-4 w-4 shrink-0" />
                                        <span class="truncate">{{ $item['label'] }}</span>
                                        @if (! $routeExists && ! $isActive)
                                            <span class="ml-auto text-[10px] uppercase tracking-wider text-theme-muted">Soon</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>

            {{-- User & theme footer --}}
            <div class="border-t border-theme p-4 space-y-3">
                <div class="flex items-center gap-3 rounded-xl px-2 py-1.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-theme-strong bg-gold-soft text-xs font-semibold text-gold-accent">
                        {{ $initials ?: 'SP' }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-theme-primary">{{ $portalMeta['userName'] }}</p>
                        <p class="truncate text-[11px] text-theme-muted">{{ $portalMeta['roleLabel'] }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-ui.theme-toggle class="flex-1 sm:hidden" />

                    <form method="POST" action="{{ route('logout') }}" class="flex-1 sm:flex-initial sm:w-full">
                        @csrf
                        <button
                            type="submit"
                            class="sig-btn-ghost inline-flex w-full items-center justify-center gap-1.5 rounded-xl px-3 py-2 text-xs font-medium text-theme-secondary hover:text-rose-500 cursor-pointer"
                            title="Sign out"
                        >
                            <x-ui.icon name="logout" class="h-4 w-4" />
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main workspace column --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-theme bg-theme-canvas/85 backdrop-blur-md">
                <div class="flex items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3 min-w-0">
                        <button
                            type="button"
                            x-on:click="sidebarOpen = true"
                            class="sig-btn-ghost rounded-xl p-2 lg:hidden cursor-pointer"
                            aria-label="Open navigation"
                        >
                            <x-ui.icon name="menu" class="h-5 w-5" />
                        </button>

                        <div class="min-w-0">
                            <h1 class="truncate font-display text-xl font-semibold tracking-tight text-theme-primary sm:text-2xl leading-none">
                                {{ $title }}
                            </h1>
                            @if ($subtitle)
                                <p class="mt-1 truncate text-xs text-theme-secondary">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        @if (Route::has('ui.foundation') && request()->routeIs('ui.foundation'))
                            <div class="hidden xl:flex items-center gap-1 rounded-xl border border-theme bg-theme-surface p-1 text-xs">
                                <span class="px-2 text-[10px] font-semibold uppercase tracking-wider text-theme-muted">Preview</span>
                                @foreach (['hq' => 'HQ', 'manager' => 'Manager', 'cashier' => 'Cashier'] as $key => $label)
                                    <a
                                        href="{{ route('ui.foundation', ['portal' => $key]) }}"
                                        @class([
                                            'rounded-lg px-2.5 py-1 font-medium transition',
                                            'bg-gold-soft text-gold-accent border border-theme-strong' => $portal === $key,
                                            'text-theme-secondary hover:text-theme-primary' => $portal !== $key,
                                        ])
                                    >
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="hidden sm:flex items-center gap-2 rounded-xl border border-theme bg-theme-surface px-3 py-1.5 text-xs text-theme-secondary">
                            <span class="h-1.5 w-1.5 rounded-full bg-gold-400"></span>
                            <span>{{ $portalMeta['scopeBadge'] }}</span>
                        </div>

                        <x-ui.theme-toggle :compact="true" class="hidden sm:inline-flex" />
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <x-ui.alert />
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.app>
