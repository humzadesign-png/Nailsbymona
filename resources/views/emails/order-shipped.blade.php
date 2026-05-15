@extends('layouts.email')

@section('subject', 'Your order is on its way — ' . $order->order_number)

@section('body')
<h1>Your set is on its way!</h1>
<p style="color:#7A6E65">Order <strong style="color:#1A1614">{{ $order->order_number }}</strong></p>

<div class="divider"></div>

<p>Your custom set has been packed and handed to the courier. Here are your shipping details:</p>

<div class="row">
  <div class="order-item" style="margin-bottom:8px">
    <span class="label" style="text-transform:none;letter-spacing:0;font-size:14px">Courier</span>
    <span class="value">{{ $order->courier?->label() ?? '—' }}</span>
  </div>
  <div class="order-item">
    <span class="label" style="text-transform:none;letter-spacing:0;font-size:14px">Tracking number</span>
    <span class="value" style="font-weight:600;color:#1A1614">{{ $order->tracking_number ?? '—' }}</span>
  </div>
</div>

<div class="divider"></div>

<div class="notice" style="background:#F4EFE8;border-left:3px solid #BFA4CE;padding:14px 18px;border-radius:8px;margin:0 0 20px">
  <p style="margin:0;font-size:14px;color:#3D3530">
    <strong>Estimated delivery: 2–4 working days.</strong><br>
    <span style="color:#7A6E65">Delivery times may vary due to courier workload, public holidays, or weather conditions in your area.</span>
  </p>
</div>

<div class="row">
  <p class="label">Your set</p>
  @foreach($order->items as $item)
  <div class="order-item">
    <span>{{ $item->product_name_snapshot }}@if($item->qty > 1) &times;{{ $item->qty }}@endif</span>
    <span class="price">Rs.&nbsp;{{ number_format($item->lineTotalPkr) }}</span>
  </div>
  @endforeach
  <div class="order-item" style="margin-top:10px;padding-top:10px;border-top:1px solid #E0D9CE">
    <span style="font-size:13px;color:#7A6E65">Shipping</span>
    <span class="price" style="font-size:13px;color:#7A6E65">Rs.&nbsp;{{ number_format($order->shipping_pkr) }}</span>
  </div>
  <div class="totals">
    <div class="total-row final">
      <span>Total paid</span>
      <span class="price">Rs.&nbsp;{{ number_format($order->total_pkr) }}</span>
    </div>
  </div>
</div>

<div class="cta-wrap">
  <a href="{{ route('order.track', $order->id) }}" class="cta">Track your order</a>
</div>

<div class="divider"></div>

<p style="font-size:13px;color:#7A6E65">If you don't receive your order within 5 working days, please get in touch. Reply to this email or WhatsApp <strong>Nails by Mona</strong> with your order number.</p>
@endsection
