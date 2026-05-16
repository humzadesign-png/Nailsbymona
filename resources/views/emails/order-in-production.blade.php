@extends('layouts.email')

@section('subject', 'Your order is being made — ' . $order->order_number)

@section('body')
<h1>Your set is being crafted now.</h1>
<p style="color:#7A6E65">Order <strong style="color:#1A1614">{{ $order->order_number }}</strong></p>

<div class="divider"></div>

<p>Your nails are now in production. I'm working on your custom set using your sizing measurements.</p>

<div class="row">
  <p class="label">Your set</p>
  @foreach($order->items as $item)
  <div class="order-item" style="display:table;width:100%;border-bottom:1px solid #E0D9CE;padding:8px 0;">
    <span style="display:table-cell;">{{ $item->product_name_snapshot }}@if($item->qty > 1) &times;{{ $item->qty }}@endif</span>
    <span class="price" style="display:table-cell;text-align:right;white-space:nowrap;padding-left:12px;">Rs.&nbsp;{{ number_format($item->lineTotalPkr) }}</span>
  </div>
  @endforeach
</div>

<div class="divider"></div>

<p>Estimated dispatch: <strong>{{ now()->addDays(4)->format('D, d M Y') }}</strong>.</p>
<p style="font-size:14px;color:#7A6E65">Once your set is packed and handed to the courier, you'll receive a shipping confirmation with your tracking number.</p>

<div class="cta-wrap">
  <a href="{{ route('order.track', $order->id) }}" class="cta">Track your order</a>
</div>

<div class="divider"></div>

<p style="font-size:13px;color:#7A6E65">Questions? Reply to this email or WhatsApp <strong>Nails by Mona</strong> with your order number.</p>
@endsection
