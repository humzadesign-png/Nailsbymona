<div class="space-y-2">
  @foreach ($bag as $item)
  <div class="flex justify-between items-start gap-2">
    <span class="font-sans text-body text-graphite leading-snug">
      {{ $item['name'] ?? 'Custom Set' }}
      @if (($item['qty'] ?? 1) > 1) <span class="text-stone">&times;{{ $item['qty'] }}</span> @endif
    </span>
    <span class="font-sans text-body text-ink whitespace-nowrap shrink-0">
      Rs.&nbsp;{{ number_format(($item['price_pkr'] ?? 0) * ($item['qty'] ?? 1)) }}
    </span>
  </div>
  @endforeach

  @if ($isReturning && $totals['discount'] > 0)
  <div class="flex justify-between items-center pt-2 border-t border-hairline">
    <span class="font-sans text-caption text-stone">Reorder discount (–5%)</span>
    <span class="font-sans text-caption text-lavender-ink">–Rs.&nbsp;{{ number_format($totals['discount']) }}</span>
  </div>
  @endif

  <div class="flex justify-between items-center">
    <span class="font-sans text-caption text-stone">Shipping</span>
    <span class="font-sans text-caption text-graphite">Rs.&nbsp;{{ number_format($totals['shipping']) }}</span>
  </div>

  <div class="flex justify-between items-center pt-3 border-t border-hairline">
    <span class="font-sans font-semibold text-ink">Total</span>
    <span class="font-sans font-semibold text-lavender" style="font-size:1.05rem">Rs.&nbsp;{{ number_format($totals['total']) }}</span>
  </div>

  @if ($totals['requires_advance'])
  <p class="font-sans text-caption text-stone pt-1">
    Advance required — details on the next step.
  </p>
  @endif
</div>
