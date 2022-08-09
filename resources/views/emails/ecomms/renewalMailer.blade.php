@component('mail::message')

{!! $message_body !!}
<br>
{{ config('app.name') }}
@endcomponent
