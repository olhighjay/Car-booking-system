@component('mail::message')
# Hello {{$user['name']}},

Your trip with the ID <b>{{$trip['id']}}</b> has been denied by the admin

Kindly contact the admin for more information


@component('mail::button', ['url' => 'http://localhost:8000/mytrips'])
View Your Trips
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
