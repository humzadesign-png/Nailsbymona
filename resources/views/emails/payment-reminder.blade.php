@extends('layouts.email')

@section('subject',
  $reminderNumber === 1
    ? 'A gentle reminder — order ' . $order->order_number
    : 'Last reminder — order ' . $order->order_number
)

@section('body')
@if($reminderNumber === 1)
<h1>Just a gentle nudge.</h1>
<p>We haven't received your payment yet for order <strong>{{ $order->order_number }}</strong>. No rush — just a reminder that your set is waiting for you.</p>
@else
<h1>Your order will be cancelled soon.</h1>
<div class="warning">
  <p><strong>If payment isn't received within the next 24 hours, your order ({{ $order->order_number }}) will be automatically cancelled.</strong> You're always welcome to place a new order.</p>
</div>
@endif

<div class="divider"></div>

{{-- Payment amount --}}
<div class="row">
  <p class="label">Amount due</p>
  @php
    $isBridalTrio = $order->items->contains(fn($i) => $i->product_tier_snapshot === 'bridal_trio');
    if ($isBridalTrio) {
      $amountDue = (int)($order->total_pkr * 0.50);
      $amountNote = '50% Bridal Trio deposit';
    } elseif ($order->requires_advance) {
      $amountDue = (int)($order->total_pkr * 0.30);
      $amountNote = '30% advance required';
    } else {
      $amountDue = $order->total_pkr;
      $amountNote = 'Full payment';
    }
  @endphp
  <p class="value price">Rs.&nbsp;{{ number_format($amountDue) }}</p>
  <p style="font-size:13px;color:#7A6E65">{{ $amountNote }}</p>
</div>

{{-- Payment details --}}
<div class="row">
  <p class="label">Send to</p>
  @if($order->payment_method->value === 'jazzcash')
  <p class="value">JazzCash: {{ config('nbm.jazzcash_number') }}</p>
  <p style="color:#7A6E65">Account name: {{ config('nbm.jazzcash_name') }}</p>
  @elseif($order->payment_method->value === 'easypaisa')
  <p class="value">EasyPaisa: {{ config('nbm.easypaisa_number') }}</p>
  <p style="color:#7A6E65">Account name: {{ config('nbm.easypaisa_name') }}</p>
  @else
  <p class="value">{{ config('nbm.bank_name') }}</p>
  <p style="color:#7A6E65">IBAN: {{ config('nbm.bank_iban') }}</p>
  <p style="color:#7A6E65">Account name: {{ config('nbm.bank_account_name') }}</p>
  @endif
</div>

<div class="cta-wrap">
  <a href="{{ route('order.confirm', $order->id) }}" class="cta">Upload payment proof</a>
</div>

<div class="divider"></div>

<p style="font-size:13px;color:#7A6E65">Once you upload your payment screenshot, I'll verify it within 24 hours and your order goes straight into production.</p>
@endsection
