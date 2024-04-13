<x-mail::message>
You can verify your email using the following code:

# {{ $magicCode->code }}

This code will expire within 10 minutes.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
