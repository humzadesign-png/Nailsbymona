@extends('layouts.email')

@section('subject', 'Your order has been cancelled — ' . $order->order_number)

@section('body')
<h1>Your order has been cancelled.</h1>
<p>We waited 72 hours but didn't receive payment for order <strong>{{ $order->order_number }}</strong>, so it has been automatically cancelled.</p>

<p>No hard feelings at all — life gets busy. If you'd still like a set, you're welcome to place a new order any time and we'll pick up right where we left off.</p>

<div class="divider"></div>

<div class="row">
  <p class="label">Cancelled order</p>
  <p class="value">{{ $order->order_number }}</p>
</div>
<div class="row">
  <p class="label">Items</p>
  @foreach($order->items as $item)
  <p style="color:#1A1614">{{ $item->product_name_snapshot }}</p>
  @endforeach
</div>

<div class="cta-wrap">
  <a href="{{ route('shop') }}" class="cta">Browse the collection</a>
</div>

<div class="divider"></div>

<p style="font-size:13px;color:#7A6E65">If this was a mistake or you had trouble with payment, please send a WhatsApp message to <strong>Nails by Mona</strong> and I'll sort it out personally.</p>
@endsection
