@php
    $errorSession = session('error');
    $errorMessage = is_array($errorSession) ? ($errorSession['message'] ?? null) : (is_string($errorSession) ? $errorSession : null);
    $errorReference = is_array($errorSession) ? ($errorSession['reference'] ?? null) : null;
@endphp

@if (session('status'))
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-500/35 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200" role="status">
        <x-ui.icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
        <p>{{ session('status') }}</p>
    </div>
@endif

@if ($errorMessage)
    <div class="mb-5 flex items-start justify-between gap-3 rounded-xl border border-rose-500/40 bg-rose-500/12 px-4 py-3 text-sm text-rose-800 dark:text-rose-200" role="alert">
        <div class="flex items-start gap-3">
            <x-ui.icon name="alert" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" />
            <div>
                <p class="font-medium">{{ $errorMessage }}</p>
                @if ($errorReference)
                    <p class="mt-0.5 text-xs opacity-80">Reference: <span class="font-mono font-semibold">{{ $errorReference }}</span></p>
                @endif
            </div>
        </div>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="mb-5 rounded-xl border border-rose-500/40 bg-rose-500/12 px-4 py-3 text-sm text-rose-800 dark:text-rose-200" role="alert">
        <div class="flex items-start gap-3">
            <x-ui.icon name="alert" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" />
            <div class="space-y-1">
                <p class="font-medium">Please review the highlighted fields:</p>
                <ul class="list-disc pl-4 text-xs space-y-0.5">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
