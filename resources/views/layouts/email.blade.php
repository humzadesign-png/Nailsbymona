<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('subject', 'Nails by Mona')</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background-color: #F4EFE8;
      font-family: 'DM Sans', Arial, Helvetica, sans-serif;
      font-size: 15px;
      color: #3D3530;
      -webkit-text-size-adjust: 100%;
    }
    .wrapper { padding: 32px 16px 48px; }
    .card {
      max-width: 520px;
      margin: 0 auto;
      background: #FBF8F2;
      border-radius: 16px;
      overflow: hidden;
    }
    .card-header {
      background-color: #BFA4CE;
      padding: 28px 40px;
      text-align: center;
    }
    .logo {
      display: block;
      height: 40px;
      width: auto;
      margin: 0 auto;
    }
    .card-body { padding: 32px 40px; }
    h1 {
      font-family: Georgia, 'Times New Roman', serif;
      font-weight: 300;
      font-size: 24px;
      color: #1A1614;
      line-height: 1.3;
      margin-bottom: 12px;
    }
    .divider { height: 1px; background: #E0D9CE; margin: 24px 0; }
    p { color: #3D3530; font-size: 15px; line-height: 1.65; margin-bottom: 12px; }
    p:last-child { margin-bottom: 0; }
    .label {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #7A6E65;
      margin-bottom: 4px;
    }
    .value {
      font-size: 15px;
      font-weight: 600;
      color: #1A1614;
      margin-bottom: 0;
    }
    .row { margin-bottom: 16px; }
    .price { color: #BFA4CE; font-weight: 600; }
    .cta-wrap { text-align: center; margin: 28px 0 4px; }
    .cta {
      display: inline-block;
      background: #BFA4CE;
      color: #ffffff !important;
      padding: 14px 32px;
      border-radius: 100px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.02em;
    }
    .cta:hover { background: #9B7FB4; }
    .notice {
      background: #F2EBF8;
      border-left: 3px solid #BFA4CE;
      border-radius: 0 8px 8px 0;
      padding: 14px 18px;
      margin: 16px 0;
    }
    .notice p { font-size: 14px; color: #5B4570; margin: 0; }
    .warning {
      background: #FEF3EE;
      border-left: 3px solid #A33A2E;
      border-radius: 0 8px 8px 0;
      padding: 14px 18px;
      margin: 16px 0;
    }
    .warning p { font-size: 14px; color: #A33A2E; margin: 0; }
    .card-footer {
      padding: 20px 40px;
      border-top: 1px solid #E0D9CE;
      text-align: center;
    }
    .card-footer p {
      font-size: 12px;
      color: #7A6E65;
      margin: 0;
    }
    .order-item { padding: 8px 0; border-bottom: 1px solid #E0D9CE; }
    .order-item:last-child { border-bottom: none; }
    .totals { padding-top: 12px; }
    .total-row { padding: 7px 0; font-size: 14px; }
    .total-row.final { font-weight: 600; font-size: 15px; color: #1A1614; padding-top: 12px; border-top: 1px solid #E0D9CE; margin-top: 8px; }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    <div class="card-header">
      <img src="{{ config('app.url') }}/logo-white.svg" alt="Nails by Mona" class="logo" width="160" height="40">
    </div>
    <div class="card-body">
      @yield('body')
    </div>
    <div class="card-footer">
      <p>Handmade in Mirpur, Azad Kashmir &middot; Shipped across Pakistan</p>
      <p style="margin-top:4px">Questions? WhatsApp <strong>Nails by Mona</strong> — not a personal number.</p>
    </div>
  </div>
</div>
</body>
</html>
