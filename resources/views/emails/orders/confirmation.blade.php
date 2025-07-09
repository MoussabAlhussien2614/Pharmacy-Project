@component('mail::message')
# Thank You for Your Order!

Hello {{ $order->user->username }},

Your order has been successfully processed.

**Order Number:** {{ $order->id }}
**Total Price:** {{ $order->total_price }} $
**Status:** {{ $order->status }}

@component('mail::button', ['url' => url('/')])
View Order
@endcomponent

Thank you,
{{ config('app.name') }}
@endcomponent
