@php
  $steps = [
    1 => ['label' => 'Your sizing',  'route' => 'order.start'],
    2 => ['label' => 'Your details', 'route' => 'order.details'],
    3 => ['label' => 'Payment',      'route' => 'order.payment'],
  ];
@endphp

<nav aria-label="Order progress" class="flex items-center justify-center gap-0 max-w-sm mx-auto">
  @foreach ($steps as $num => $step)

    {{-- Node --}}
    @php
      $isDone    = $num < $currentStep;
      $isCurrent = $num === $currentStep;
    @endphp

    <div class="flex flex-col items-center relative">
      {{-- Circle --}}
      @if ($isDone)
        <div class="w-7 h-7 rounded-full bg-lavender flex items-center justify-center shrink-0">
          <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="40 144 96 200 224 72"/>
          </svg>
        </div>
      @elseif ($isCurrent)
        <div class="w-7 h-7 rounded-full bg-lavender ring-4 ring-lavender/20 flex items-center justify-center shrink-0">
          <span class="font-sans text-white font-semibold" style="font-size:0.65rem">{{ $num }}</span>
        </div>
      @else
        <div class="w-7 h-7 rounded-full border-2 border-ash flex items-center justify-center shrink-0">
          <span class="font-sans text-stone" style="font-size:0.65rem">{{ $num }}</span>
        </div>
      @endif

      {{-- Label --}}
      <span class="font-sans mt-1.5 whitespace-nowrap"
            style="font-size:0.7rem"
            @class([
              'text-lavender-ink font-semibold' => $isCurrent,
              'text-lavender'                   => $isDone,
              'text-stone'                      => ! $isDone && ! $isCurrent,
            ])>
        {{ $step['label'] }}
      </span>
    </div>

    {{-- Connector line (not after last step) --}}
    @if ($num < count($steps))
      <div class="h-0.5 w-12 md:w-20 shrink-0 mb-5 {{ $isDone ? 'bg-lavender' : 'bg-ash' }}"></div>
    @endif

  @endforeach
</nav>
