@component('mail::message')
Hello {{ $full_name }}<br>

Your Goverify login credentials are as follows:<br><br>
Email: <b>{{ $email }}</b><br>
Password: <b>{{ $password }}</b><br>

@component('mail::button', ['url' => 'http://127.0.0.1:8000'])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
