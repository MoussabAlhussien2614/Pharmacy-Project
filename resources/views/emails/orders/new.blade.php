@component('mail::message')
# New Order 🚀

A new order has been placed by: {{ $order->user->username }}

**Order Number:** {{ $order->id }}

**Total Amount:** {{ $order->total_price }} $

@component('mail::button', ['url' => url('/admin/orders/'.$order->id)])
View Order
@endcomponent

Thank you,
{{ config('app.name') }}
@endcomponent
