{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Car App</title>
</head>
<body>
    <h1>it works very well</h1>
    <p>just contact us buddy!.. This is just <b>{{$topic}}</b> tho</p>
</body>
</html> --}}

@component('mail::message')
# A Heading

I am a very great and great person  and yes I know it very much

- A list 
- Goes 
- Here 
@component('mail::button', ['url'=>'http://localhost:8000/emails'])
    visit our website
@endcomponent
@endcomponent