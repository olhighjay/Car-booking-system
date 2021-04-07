@component('mail::message')
# Hello {{$user['name']}},

Your Emergrncy trip has been aborted.


Kindly contact the admin for more details.


@component('mail::button', ['url' => 'http://localhost:8000/mytrips'])
View Your Trips
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
