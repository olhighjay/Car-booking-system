@component('mail::message')
# Hello {{$admin['name']}},

{{$user['name']}} has just requested for an emergency trip starting on <b>{{Carbon::parse($trip['trip_date'])->format('jS F Y')}}</b>.
<b>Time: {{date("h:i:s a", strtotime($trip['trip_start_time'])). ' '. '-'. ' ' . date("h:i:s a", strtotime($trip['trip_end_time']))}}</b>



Kindly attend to the request as fast as you can

@component('mail::button', ['url' => 'http://localhost:8000/approvetrip/'.$trip['id']])
Approve/Cancel
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
