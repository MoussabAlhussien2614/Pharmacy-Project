@component('mail::message')
# ⚠️ Low Stock Alert

The following medicine is running low in stock:

**Medicine:** {{ $medicine->name }}
**Company:** {{ $medicine->company }}
**Remaining Quantity:** {{ $medicine->quantity }}

Please restock as soon as possible.

Thanks,
{{ config('app.name') }}
@endcomponent
