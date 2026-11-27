<x-layouts.app title="CSV Imports" portal="manager" branchLabel="Tagum City Center · TCC">
    @php
        $initialBatches = [
            [
                'id' => 7,
                'filename' => 'tcc-nov-restock.csv',
                'type' => 'restock',
                'total_rows' => 14,
                'imported' => 14,
                'failed' => 0,
                'status' => 'completed',
                'created_at' => '2026-11-23 11:09',
                'errors' => [],
            ],
            [
                'id' => 6,
                'filename' => 'tcc-color-delivery.csv',
                'type' => 'restock',
                'total_rows' => 8,
                'imported' => 6,
                'failed' => 2,
                'status' => 'failed',
                'created_at' => '2026-11-19 16:40',
                'errors' => [
                    ['row' => 4, 'detail' => 'Product "Shampoo Gallon" not found in master catalog.'],
                    ['row' => 7, 'detail' => 'Quantity must be greater than zero.'],
                ],
            ],
            [
                'id' => 5,
                'filename' => 'tcc-opening-stock.csv',
                'type' => 'initial_stock',
                'total_rows' => 6,
                'imported' => 6,
                'failed' => 0,
                'status' => 'completed',
                'created_at' => '2026-11-01 08:30',
                'errors' => [],
            ],
        ];
    @endphp

    <div
        x-data="{
            batches: @js($initialBatches),
            importType: 'restock',
            selectedFilename: '',
            notice: null,
            activeBatch: null,
            handleFile(event) {
                const file = event.target.files[0];
                this.selectedFilename = file ? file.name : '';
            },
            queueImport() {
                const name = this.selectedFilename || 'tcc-stock-batch.csv';
                this.batches.unshift({
                    id: Date.now(),
                    filename: name,
                    type: this.importType,
                    total_rows: 6,
                    imported: 6,
                    failed: 0,
                    status: 'completed',
                    created_at: 'Just now',
                    errors: [],
                });
                this.selectedFilename = '';
                this.notice = `Queued ${name} for branch inventory update.`;
            },
            openErrors(batch) {
                this.activeBatch = batch;
                $dispatch('open-modal', 'import-errors');
            },
        }"
        class="space-y-8"
    >
        <section class="space-y-4">
            <x-ui.section-heading eyebrow="Inventory & Stock" title="CSV Imports" />

            <x-ui.notice />

            <x-ui.card title="Upload Stock Sheet">
                <form x-on:submit.prevent="queueImport()" class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                    <div class="lg:col-span-3">
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">Import Type</label>
                        <select x-model="importType" class="sig-input w-full rounded-xl px-3.5 py-2.5 text-sm">
                            <option value="restock">Restock Delivery</option>
                            <option value="initial_stock">Opening / Count Reset</option>
                        </select>
                    </div>

                    <div class="lg:col-span-6">
                        <label class="mb-1.5 block text-xs font-medium text-theme-secondary">CSV File (product_name, qty)</label>
                        <label class="sig-elevated flex cursor-pointer items-center justify-between gap-3 rounded-xl px-4 py-2.5 text-xs hover:border-theme-strong transition">
                            <span class="truncate text-theme-primary" x-text="selectedFilename || 'Choose a .csv file…'"></span>
                            <span class="rounded-lg bg-gold-soft px-2.5 py-1 font-medium text-gold-accent">Browse</span>
                            <input type="file" accept=".csv,text/csv" class="hidden" x-on:change="handleFile($event)">
                        </label>
                    </div>

                    <div class="lg:col-span-3 flex justify-end">
                        <button type="submit" class="sig-btn-gold inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-xs cursor-pointer">
                            <x-ui.icon name="import" class="h-4 w-4" />
                            <span>Queue Import</span>
                        </button>
                    </div>
                </form>
            </x-ui.card>
        </section>

        <section class="space-y-4">
            <x-ui.section-heading eyebrow="History" title="Import Batches" />

            <x-ui.card>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-theme">
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">File</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Type</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Imported</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Failed</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted pl-4">Status</th>
                                <th class="pb-3 text-left text-xs font-medium text-theme-muted">Uploaded</th>
                                <th class="pb-3 text-right text-xs font-medium text-theme-muted">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-theme">
                            <template x-for="batch in batches" :key="batch.id">
                                <tr class="hover:bg-theme-elevated/45 transition">
                                    <td class="py-3.5 pr-4 font-mono text-xs font-medium text-theme-primary" x-text="batch.filename"></td>
                                    <td class="py-3.5 pr-4">
                                        <span class="rounded-md bg-theme-elevated px-2.5 py-0.5 text-xs capitalize text-theme-secondary" x-text="batch.type.replace('_', ' ')"></span>
                                    </td>
                                    <td class="py-3.5 pr-4 text-right tabular-nums text-theme-primary" x-text="batch.imported + ' / ' + batch.total_rows"></td>
                                    <td class="py-3.5 pr-4 text-right tabular-nums" :class="batch.failed > 0 ? 'font-semibold text-rose-500' : 'text-theme-muted'" x-text="batch.failed"></td>
                                    <td class="py-3.5 pl-4 pr-4">
                                        <span
                                            class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium capitalize"
                                            :class="batch.status === 'completed'
                                                ? 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300'
                                                : 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300'"
                                            x-text="batch.status"
                                        ></span>
                                    </td>
                                    <td class="py-3.5 pr-4 font-mono text-xs tabular-nums text-theme-muted whitespace-nowrap" x-text="batch.created_at"></td>
                                    <td class="py-3.5 text-right">
                                        <button
                                            type="button"
                                            x-show="batch.errors.length > 0"
                                            x-on:click="openErrors(batch)"
                                            class="text-xs font-medium text-rose-500 hover:underline cursor-pointer"
                                        >
                                            View Errors
                                        </button>
                                        <span x-show="batch.errors.length === 0" class="text-xs text-theme-muted">—</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </section>

        <x-ui.modal name="import-errors" title="Row Errors" maxWidth="max-w-lg">
            <div x-show="activeBatch" class="space-y-3">
                <p class="font-mono text-xs text-theme-muted" x-text="activeBatch?.filename"></p>
                <div class="space-y-2">
                    <template x-for="err in (activeBatch?.errors || [])" :key="err.row">
                        <div class="rounded-xl border border-rose-500/35 bg-rose-500/10 px-4 py-2.5 text-xs text-rose-800 dark:text-rose-200">
                            <span class="font-mono font-semibold" x-text="'Row ' + err.row + ':'"></span>
                            <span x-text="err.detail"></span>
                        </div>
                    </template>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'import-errors')" class="sig-btn-gold rounded-xl px-4 py-2 text-xs cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</x-layouts.app>
