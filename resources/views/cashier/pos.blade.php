<x-layouts.app title="POS Terminal" portal="cashier" branchLabel="Tagum City Center · TCC" userName="Ana Reyes">
    @php
        $services = [
            ['id' => 1, 'name' => 'Haircut (Women)', 'category' => 'Hair', 'price' => 350, 'duration_min' => 45, 'stock_note' => null, 'stock_status' => 'ok'],
            ['id' => 2, 'name' => 'Haircut (Men)', 'category' => 'Hair', 'price' => 200, 'duration_min' => 30, 'stock_note' => null, 'stock_status' => 'ok'],
            ['id' => 3, 'name' => 'Hair Color (Full)', 'category' => 'Hair', 'price' => 1800, 'duration_min' => 120, 'stock_note' => '7 tubes left', 'stock_status' => 'low'],
            ['id' => 4, 'name' => 'Rebond', 'category' => 'Hair', 'price' => 2500, 'duration_min' => 180, 'stock_note' => '2 jars left', 'stock_status' => 'low'],
            ['id' => 5, 'name' => 'Manicure', 'category' => 'Nail', 'price' => 250, 'duration_min' => 45, 'stock_note' => null, 'stock_status' => 'ok'],
            ['id' => 6, 'name' => 'Pedicure', 'category' => 'Nail', 'price' => 300, 'duration_min' => 60, 'stock_note' => null, 'stock_status' => 'ok'],
            ['id' => 7, 'name' => 'Gel Polish', 'category' => 'Nail', 'price' => 450, 'duration_min' => 60, 'stock_note' => null, 'stock_status' => 'ok'],
            ['id' => 8, 'name' => 'Foot Spa', 'category' => 'Nail', 'price' => 500, 'duration_min' => 60, 'stock_note' => null, 'stock_status' => 'ok'],
            ['id' => 9, 'name' => 'Bridal Makeup Trial', 'category' => 'Branch', 'price' => 1500, 'duration_min' => 90, 'stock_note' => null, 'stock_status' => 'ok'],
        ];

        $technicians = [
            ['id' => 1, 'name' => 'Maria Santos', 'rate' => 15],
            ['id' => 2, 'name' => 'Jun Reyes', 'rate' => 12],
            ['id' => 3, 'name' => 'Ana Dela Cruz', 'rate' => 10],
            ['id' => 4, 'name' => 'Liza Mendoza', 'rate' => 10],
        ];

        $discounts = [
            ['id' => 0, 'label' => 'No Discount', 'type' => 'none', 'value' => 0],
            ['id' => 1, 'label' => 'Senior citizen 20%', 'type' => 'percent', 'value' => 20],
            ['id' => 2, 'label' => 'PWD 20%', 'type' => 'percent', 'value' => 20],
            ['id' => 3, 'label' => 'Promo 100 off', 'type' => 'flat', 'value' => 100],
        ];
    @endphp

    <div
        x-data="{
            services: @js($services),
            technicians: @js($technicians),
            discounts: @js($discounts),
            category: 'All',
            search: '',
            cart: [
                { uid: 1, service_id: 3, name: 'Hair Color (Full)', price: 1800, qty: 1, technician_id: 1 },
            ],
            discountId: 0,
            cashTendered: 2000,
            idempotencyKey: 'POS-' + Math.random().toString(36).slice(2, 10).toUpperCase(),
            submitting: false,
            notice: null,
            receipt: null,
            get filteredServices() {
                const q = this.search.trim().toLowerCase();
                return this.services.filter((s) => {
                    const matchCat = this.category === 'All' || s.category === this.category;
                    const matchQ = !q || s.name.toLowerCase().includes(q) || s.category.toLowerCase().includes(q);
                    return matchCat && matchQ;
                });
            },
            get subtotalCents() {
                return this.cart.reduce((sum, item) => sum + Math.round(item.price * 100) * item.qty, 0);
            },
            get selectedDiscount() {
                return this.discounts.find((d) => d.id === Number(this.discountId)) || this.discounts[0];
            },
            get discountCents() {
                const d = this.selectedDiscount;
                if (!d || d.type === 'none' || this.subtotalCents === 0) return 0;
                if (d.type === 'percent') return Math.round((this.subtotalCents * d.value) / 100);
                return Math.min(this.subtotalCents, Math.round(d.value * 100));
            },
            get totalCents() {
                return Math.max(0, this.subtotalCents - this.discountCents);
            },
            get changeCents() {
                const tenderedCents = Math.round(Number(this.cashTendered || 0) * 100);
                return tenderedCents - this.totalCents;
            },
            get canCheckout() {
                return this.cart.length > 0 && this.changeCents >= 0 && !this.submitting;
            },
            formatCents(cents) {
                return '₱ ' + (cents / 100).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            techName(id) {
                return this.technicians.find((t) => t.id === Number(id))?.name || 'Unassigned';
            },
            addToCart(svc) {
                const existing = this.cart.find((item) => item.service_id === svc.id);
                if (existing) {
                    existing.qty += 1;
                } else {
                    this.cart.push({
                        uid: Date.now() + Math.random(),
                        service_id: svc.id,
                        name: svc.name,
                        price: svc.price,
                        qty: 1,
                        technician_id: this.technicians[0].id,
                    });
                }
            },
            stepQty(idx, delta) {
                const item = this.cart[idx];
                if (!item) return;
                item.qty += delta;
                if (item.qty <= 0) {
                    this.cart.splice(idx, 1);
                }
            },
            removeLine(idx) {
                this.cart.splice(idx, 1);
            },
            clearTicket() {
                this.cart = [];
                this.discountId = 0;
                this.cashTendered = 0;
            },
            setExactCash() {
                this.cashTendered = (this.totalCents / 100).toFixed(2);
            },
            completeSale() {
                if (!this.canCheckout) return;
                this.submitting = true;
                const ref = 'TX-TCC-' + String(Math.floor(100 + Math.random() * 900));
                const tenderedCents = Math.round(Number(this.cashTendered || 0) * 100);

                this.receipt = {
                    ref,
                    timestamp: new Date().toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }),
                    cashier: 'Ana Reyes',
                    discountLabel: this.selectedDiscount.type !== 'none' ? this.selectedDiscount.label : null,
                    subtotal: this.formatCents(this.subtotalCents),
                    discount: this.formatCents(this.discountCents),
                    hasDiscount: this.discountCents > 0,
                    total: this.formatCents(this.totalCents),
                    tendered: this.formatCents(tenderedCents),
                    change: this.formatCents(Math.max(0, this.changeCents)),
                    items: this.cart.map((item) => ({
                        name: item.name,
                        qty: item.qty,
                        technician: this.techName(item.technician_id),
                        lineTotal: this.formatCents(Math.round(item.price * 100) * item.qty),
                    })),
                };

                this.notice = `Completed ${ref} · Cash collected ${this.receipt.total}`;
                this.clearTicket();
                this.idempotencyKey = 'POS-' + Math.random().toString(36).slice(2, 10).toUpperCase();
                this.submitting = false;
                $dispatch('open-modal', 'pos-receipt');
            },
        }"
        class="space-y-5"
    >
        <x-ui.notice />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-12 xl:items-start">
            {{-- Left: Service Menu --}}
            <div class="space-y-4 xl:col-span-7">
                <x-ui.card padding="p-4 sm:p-5">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div class="inline-flex rounded-xl border border-theme bg-theme-elevated p-0.5 text-xs">
                            @foreach (['All', 'Hair', 'Nail', 'Branch'] as $tab)
                                <button
                                    type="button"
                                    x-on:click="category = '{{ $tab }}'"
                                    :class="category === '{{ $tab }}' ? 'bg-gold-soft text-gold-accent border border-theme-strong' : 'text-theme-secondary border border-transparent'"
                                    class="rounded-lg px-3 py-1 font-medium cursor-pointer transition"
                                >
                                    {{ $tab === 'Branch' ? 'Specials' : $tab }}
                                </button>
                            @endforeach
                        </div>

                        <div class="relative w-full sm:w-56">
                            <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-theme-muted" />
                            <input
                                type="search"
                                x-model="search"
                                placeholder="Search services…"
                                class="sig-input w-full rounded-xl py-1.5 pl-9 pr-3 text-xs"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <template x-for="svc in filteredServices" :key="svc.id">
                            <button
                                type="button"
                                x-on:click="addToCart(svc)"
                                class="sig-elevated group flex flex-col justify-between gap-3 rounded-2xl p-4 text-left hover:border-theme-strong hover:bg-gold-soft/40 cursor-pointer transition"
                            >
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="rounded-md bg-theme-surface px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-theme-muted border border-theme" x-text="svc.category"></span>
                                        <span class="text-[11px] text-theme-muted tabular-nums" x-text="svc.duration_min + ' min'"></span>
                                    </div>
                                    <p class="font-medium text-sm text-theme-primary group-hover:text-gold-accent transition" x-text="svc.name"></p>
                                </div>

                                <div class="flex items-end justify-between gap-2 pt-1">
                                    <span class="font-display text-lg font-semibold text-gold-accent tabular-nums" x-text="'₱ ' + svc.price.toLocaleString('en-PH', { minimumFractionDigits: 2 })"></span>
                                    <span
                                        x-show="svc.stock_note"
                                        class="sig-low-stock-badge inline-flex items-center gap-1 border border-amber-500/45 bg-amber-500/14 px-2 py-0.5 text-[10px] font-semibold text-amber-900 dark:text-amber-300"
                                        x-text="svc.stock_note"
                                    ></span>
                                </div>
                            </button>
                        </template>

                        <div x-show="filteredServices.length === 0" x-cloak class="col-span-full py-10 text-center text-xs text-theme-muted">
                            No matching services found.
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Right: Order Ticket & Cash Checkout --}}
            <div class="xl:col-span-5 xl:sticky xl:top-24">
                <x-ui.card title="Current Ticket">
                    <x-slot:actions>
                        <button
                            type="button"
                            x-show="cart.length > 0"
                            x-on:click="clearTicket()"
                            class="text-xs font-medium text-rose-500 hover:underline cursor-pointer"
                        >
                            Clear
                        </button>
                    </x-slot:actions>

                    <div class="space-y-4">
                        <div x-show="cart.length === 0" x-cloak class="rounded-xl border border-dashed border-theme py-10 text-center text-xs text-theme-muted">
                            Select a service from the menu to start a ticket.
                        </div>

                        <div x-show="cart.length > 0" class="divide-y divide-theme max-h-72 overflow-y-auto pr-1">
                            <template x-for="(item, idx) in cart" :key="item.uid">
                                <div class="py-3 space-y-2 first:pt-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-theme-primary" x-text="item.name"></p>
                                            <p class="text-xs text-theme-muted tabular-nums" x-text="'₱ ' + item.price.toLocaleString('en-PH', { minimumFractionDigits: 2 }) + ' each'"></p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="font-semibold text-sm tabular-nums text-theme-primary" x-text="formatCents(Math.round(item.price * 100) * item.qty)"></span>
                                            <button type="button" x-on:click="removeLine(idx)" class="text-theme-muted hover:text-rose-500 cursor-pointer p-1" aria-label="Remove item">
                                                <x-ui.icon name="trash" class="h-3.5 w-3.5" />
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        <select x-model="item.technician_id" class="sig-input rounded-lg px-2.5 py-1 text-xs flex-1" aria-label="Assign technician">
                                            <template x-for="tech in technicians" :key="tech.id">
                                                <option :value="tech.id" x-text="tech.name + ' (' + tech.rate + '%)'"></option>
                                            </template>
                                        </select>

                                        <div class="inline-flex items-center rounded-lg border border-theme bg-theme-elevated text-xs">
                                            <button type="button" x-on:click="stepQty(idx, -1)" class="px-2 py-1 text-theme-secondary hover:text-theme-primary cursor-pointer" aria-label="Decrease quantity">
                                                <x-ui.icon name="minus" class="h-3 w-3" />
                                            </button>
                                            <span class="px-2 font-mono font-semibold tabular-nums text-theme-primary" x-text="item.qty"></span>
                                            <button type="button" x-on:click="stepQty(idx, 1)" class="px-2 py-1 text-theme-secondary hover:text-theme-primary cursor-pointer" aria-label="Increase quantity">
                                                <x-ui.icon name="plus" class="h-3 w-3" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="border-t border-theme pt-4 space-y-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-theme-secondary">Discount</label>
                                <select x-model="discountId" class="sig-input w-full rounded-xl px-3 py-2 text-xs">
                                    <template x-for="d in discounts" :key="d.id">
                                        <option :value="d.id" x-text="d.label"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="sig-elevated rounded-xl p-3.5 space-y-1.5 text-xs">
                                <div class="flex justify-between text-theme-secondary">
                                    <span>Subtotal</span>
                                    <span class="tabular-nums" x-text="formatCents(subtotalCents)"></span>
                                </div>
                                <div x-show="discountCents > 0" class="flex justify-between text-emerald-600 dark:text-emerald-400">
                                    <span x-text="selectedDiscount.label"></span>
                                    <span class="tabular-nums" x-text="'-' + formatCents(discountCents)"></span>
                                </div>
                                <div class="flex items-baseline justify-between border-t border-theme pt-2">
                                    <span class="text-sm font-semibold text-theme-primary">Cash Total</span>
                                    <span class="font-display text-2xl font-semibold text-gold-accent tabular-nums" x-text="formatCents(totalCents)"></span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium text-theme-secondary">Cash Tendered (₱)</label>
                                    <button type="button" x-on:click="setExactCash()" class="text-[11px] font-medium text-gold-accent hover:underline cursor-pointer">
                                        Exact Amount
                                    </button>
                                </div>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    x-model="cashTendered"
                                    class="sig-input w-full rounded-xl px-3.5 py-2.5 text-base font-semibold tabular-nums"
                                >

                                <div class="grid grid-cols-4 gap-1.5 text-xs">
                                    @foreach ([500, 1000, 2000, 5000] as $bill)
                                        <button
                                            type="button"
                                            x-on:click="cashTendered = {{ $bill }}"
                                            class="sig-btn-ghost rounded-lg py-1.5 font-mono text-[11px] tabular-nums cursor-pointer"
                                        >
                                            ₱{{ number_format($bill) }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-between pt-1 text-xs">
                                    <span class="text-theme-muted">Change Due</span>
                                    <span
                                        class="font-display text-lg font-semibold tabular-nums"
                                        :class="changeCents >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500'"
                                        x-text="changeCents >= 0 ? formatCents(changeCents) : 'Short ' + formatCents(Math.abs(changeCents))"
                                    ></span>
                                </div>
                            </div>

                            <button
                                type="button"
                                :disabled="!canCheckout"
                                x-on:click="completeSale()"
                                :class="canCheckout ? 'sig-btn-gold cursor-pointer' : 'bg-theme-elevated text-theme-muted border border-theme cursor-not-allowed'"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold transition"
                            >
                                <x-ui.icon name="cash" class="h-4 w-4" />
                                <span>Complete Cash Sale</span>
                            </button>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>

        <x-ui.modal name="pos-receipt" title="Cash Receipt" maxWidth="max-w-md">
            <div x-show="receipt" class="space-y-4">
                <div class="text-center border-b border-dashed border-theme pb-3 space-y-0.5">
                    <p class="font-display text-lg font-semibold text-theme-primary">Signature Salon & Nail Spa</p>
                    <p class="text-xs text-theme-muted">Tagum City Center (TCC)</p>
                    <p class="font-mono text-xs text-gold-accent pt-1" x-text="receipt?.ref"></p>
                    <p class="text-[11px] text-theme-muted" x-text="receipt?.timestamp + ' · Cashier: ' + receipt?.cashier"></p>
                </div>

                <div class="space-y-2 text-xs">
                    <template x-for="(line, i) in (receipt?.items || [])" :key="i">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-medium text-theme-primary" x-text="line.qty + '× ' + line.name"></p>
                                <p class="text-[11px] text-theme-muted" x-text="'Tech: ' + line.technician"></p>
                            </div>
                            <span class="font-semibold tabular-nums text-theme-primary" x-text="line.lineTotal"></span>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-theme pt-3 space-y-1 text-xs">
                    <div class="flex justify-between text-theme-secondary">
                        <span>Subtotal</span>
                        <span class="tabular-nums" x-text="receipt?.subtotal"></span>
                    </div>
                    <div x-show="receipt?.hasDiscount" class="flex justify-between text-emerald-600 dark:text-emerald-400">
                        <span x-text="receipt?.discountLabel"></span>
                        <span class="tabular-nums" x-text="'-' + receipt?.discount"></span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold text-theme-primary pt-1">
                        <span>Total Paid (Cash)</span>
                        <span class="font-display text-base tabular-nums text-gold-accent" x-text="receipt?.total"></span>
                    </div>
                    <div class="flex justify-between text-theme-muted">
                        <span>Tendered</span>
                        <span class="tabular-nums" x-text="receipt?.tendered"></span>
                    </div>
                    <div class="flex justify-between text-theme-primary font-medium">
                        <span>Change</span>
                        <span class="tabular-nums" x-text="receipt?.change"></span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-theme pt-4">
                    <button type="button" x-on:click="window.print()" class="sig-btn-ghost inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-medium cursor-pointer">
                        <x-ui.icon name="printer" class="h-4 w-4 text-gold-accent" />
                        <span>Print</span>
                    </button>
                    <button type="button" x-on:click="$dispatch('close-modal', 'pos-receipt')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        New Ticket
                    </button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
