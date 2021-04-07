@component('mail::message')
# Hello {{$user['name']}},

Your trip has started.
The estimated duration of your trip is: {{$time}}


Ensure to take good care of the vehicle and avoid returning late.
We wish you safe trip.


@component('mail::button', ['url' => 'http://localhost:8000/mytrips'])
View Your Trips
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
