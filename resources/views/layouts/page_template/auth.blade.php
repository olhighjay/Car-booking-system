
{{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form> --}}

@auth   
    @if(Auth::user()->roles->first()->id === 1)
        @include('layouts.navbars.sidebar')
    @elseif(Auth::user()->roles->first()->id === 2)
        @include('layouts.navbars.users.sidebar-admin')
    @elseif(Auth::user()->roles->first()->id === 3)
        @include('layouts.navbars.users.sidebar-accountant')
    @elseif(Auth::user()->roles->first()->id === 5 || Auth::user()->roles->first()->id === 4)
        @include('layouts.navbars.users.sidebar-staff')
    @endif
@endauth

{{-- @guest
    @include('layouts.navbars.sidebar') 
@endguest --}}

{{-- @if(Auth::user()->roles->first()->id === 5)
    {{'Hey are you okay at all??'}}
@endif --}}
{{-- @include('layouts.navbars.sidebar') --}}
<div class="main-panel">
    @include('alerts.message')
    @include('layouts.navbars.navs.auth')
    @yield('content')
    <br> <br> <br> <br> <hr>
    @include('layouts.footer')
</div>