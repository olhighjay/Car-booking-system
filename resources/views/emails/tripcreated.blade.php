@component('mail::message')
# Hello {{$user['name']}},

Your trip has been scheduled to start on <b>{{Carbon::parse($trip['trip_date'])->format('jS F Y')}}</b>.
<b>Time: {{date("h:i:s a", strtotime($trip['trip_start_time'])). ' '. '-'. ' ' . date("h:i:s a", strtotime($trip['trip_end_time']))}}</b>
- Trip ID: {{$trip['id']}}
- Trip Destination: {{$trip['address'].', '.$lga['name']}}
- Trip Car: {{$car['name']}}
- Trip Driver: {{$driver['name']}}

Kindly meet the Receptionist to start the trip when it is time

You can reach the Driver on this number: <b><em>{{$driver['phone_number']}}</em></b>

@component('mail::button', ['url' => 'http://localhost:8000/mytrips'])
View Your Trips
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
