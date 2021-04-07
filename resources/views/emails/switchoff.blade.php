@component('mail::message')
# Hello {{$user['name']}},

Your trip has ended.
The trip lasted for {{$time}}.


Kindly check the trip options on your trip table to drop trip report.


@component('mail::button', ['url' => 'http://localhost:8000/mytrips'])
View Your Trips
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
