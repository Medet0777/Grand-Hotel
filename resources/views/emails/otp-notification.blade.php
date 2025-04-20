<!-- resources/views/emails/otp-notification.blade.php -->
<x-mail::message>
    # Ваш одноразовый код (OTP)

    Ваш OTP код: **{{ $otp }}**

    Этот код истечет через 10 минут.

    С уважением,<br>
    {{ config('Grand-Hotel') }}
</x-mail::message>
