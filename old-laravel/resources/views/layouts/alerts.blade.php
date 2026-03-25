@if(session('success'))
    <div class="mb-6 bg-emerald-50 border-l-[6px] border-emerald-500 rounded-2xl p-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-[6px] border-red-500 rounded-2xl p-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 bg-red-50 border-l-[6px] border-red-500 rounded-2xl p-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-start gap-3">
            <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-red-800 tracking-wide uppercase">Validation Failed</h3>
                <ul class="mt-2 list-disc list-inside text-sm font-semibold text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
