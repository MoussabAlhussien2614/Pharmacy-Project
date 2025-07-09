@component('mail::message')
# Email Verification Code

Hello,

Your verification code is:

# {{ $code }}

Please enter this code to complete your registration.

Thanks,
{{ config('app.name') }}
@endcomponent
