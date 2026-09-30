@props(['iconOnly' => false])

@if($iconOnly)
    <img src="{{ asset('images/logo-pengayoman.svg') }}" alt="Logo Pengayoman" {{ $attributes->merge(['class' => 'h-10 w-auto rounded-lg shadow-sm']) }} />
@else
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
        <img src="{{ asset('images/logo-pengayoman.svg') }}" alt="Logo Pengayoman" class="h-10 w-auto rounded-lg shadow-sm" />
        <span class="font-bold text-2xl tracking-tight text-white drop-shadow">
            Inv<span class="text-amber-400 font-extrabold">BHP</span>
        </span>
    </div>
@endif
