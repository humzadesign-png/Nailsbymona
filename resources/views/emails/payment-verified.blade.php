@extends('layouts.email')

@section('subject', 'Payment confirmed — ' . $order->order_number)

@section('body')
<h1>Your payment is confirmed. Thank you!</h1>
<p style="color:#7A6E65">Order <strong style="color:#1A1614">{{ $order->order_number }}</strong></p>

<div class="divider"></div>

<div class="notice">
  <p>✓ &nbsp;Payment received and verified. Your order is now confirmed.</p>
</div>

<div class="row" style="margin-top:20px">
  <p class="label">Your set</p>
  @foreach($order->items as $item)
  <div class="order-item">
    <span>{{ $item->product_name_snapshot }}@if($item->qty > 1) &times;{{ $item->qty }}@endif</span>
    <span class="price">Rs.&nbsp;{{ number_format($item->lineTotalPkr) }}</span>
  </div>
  @endforeach
  <div class="totals">
    <div class="total-row final">
      <span>Total paid</span>
      <span class="price">Rs.&nbsp;{{ number_format($order->total_pkr) }}</span>
    </div>
  </div>
</div>

<div class="divider"></div>

<p>Your set will now go into production. Estimated dispatch: <strong>{{ now()->addWeekdays((int) config('nbm.lead_time_standard', 7))->format('D, d M Y') }}</strong>.</p>
<p style="font-size:14px;color:#7A6E65">I'll send another update when your order is being made, and again when it ships with your tracking number.</p>

<div class="cta-wrap">
  <a href="{{ route('order.track', $order->id) }}" class="cta">Track your order</a>
</div>

<div class="divider"></div>

<p style="font-size:13px;color:#7A6E65">Questions? Reply to this email or WhatsApp <strong>Nails by Mona</strong> with your order number.</p>
@endsection
