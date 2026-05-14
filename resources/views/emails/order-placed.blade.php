@extends('layouts.email')

@section('subject', 'Your order is placed — ' . $order->order_number)

@section('body')
<h1>Your order is placed, dear. Thank you.</h1>
<p style="color:#7A6E65">Order <strong style="color:#1A1614">{{ $order->order_number }}</strong></p>

<div class="divider"></div>

{{-- Order items --}}
<div class="row">
  <p class="label">Your set</p>
  @foreach($order->items as $item)
  <div class="order-item">
    <span>{{ $item->product_name_snapshot }}@if($item->qty > 1) &times;{{ $item->qty }}@endif</span>
    <span class="price">Rs.&nbsp;{{ number_format($item->lineTotalPkr) }}</span>
  </div>
  @endforeach
  <div class="totals">
    @if($order->shipping_pkr > 0)
    <div class="total-row">
      <span>Shipping</span>
      <span>Rs.&nbsp;{{ number_format($order->shipping_pkr) }}</span>
    </div>
    @endif
    @if($order->reorder_discount_pkr > 0)
    <div class="total-row" style="color:#BFA4CE">
      <span>Returning customer discount</span>
      <span>&minus;Rs.&nbsp;{{ number_format($order->reorder_discount_pkr) }}</span>
    </div>
    @endif
    <div class="total-row final">
      <span>Total</span>
      <span class="price">Rs.&nbsp;{{ number_format($order->total_pkr) }}</span>
    </div>
  </div>
</div>

<div class="divider"></div>

{{-- Payment instructions --}}
@php
  $isBridalTrio = $order->items->contains(fn($i) => $i->product_tier_snapshot === 'bridal_trio');
@endphp

@if($isBridalTrio)
<div class="notice">
  <p><strong>Bridal Trio</strong> — A 50% deposit of <strong>Rs.&nbsp;{{ number_format((int)($order->total_pkr * 0.50)) }}</strong> is required to reserve your production slot. I'll send the payment details on WhatsApp shortly.</p>
</div>
@elseif($order->requires_advance)
<div class="notice">
  <p>A <strong>30% advance of Rs.&nbsp;{{ number_format((int)($order->total_pkr * 0.30)) }}</strong> is required before production begins. I'll reach out on WhatsApp with details.</p>
</div>
@else
<p>To confirm your order, please send <strong>Rs.&nbsp;{{ number_format($order->total_pkr) }}</strong> using the method you selected. Payment details are on your confirmation page.</p>
@endif

<p style="font-size:14px;color:#7A6E65">Your order goes into production once I verify your payment — usually within 24 hours. Estimated dispatch: <strong>{{ now()->addWeekdays((int) config('nbm.lead_time_standard', 7))->format('D, d M Y') }}</strong>.</p>

<div class="cta-wrap">
  <a href="{{ route('order.confirm', $order->id) }}" class="cta">View order &amp; upload payment proof</a>
</div>

<div class="divider"></div>

<p style="font-size:13px;color:#7A6E65">If you have any questions, reply to this email or send a WhatsApp message to <strong>Nails by Mona</strong>. I read every message personally.</p>
@endsection
