<footer class="footer">
  <div class=" container-fluid ">
    <nav>
      <ul>
        <li>
         <b> SYSTEMTECH CAR APP </b>
        </li> &nbsp; &nbsp;
        <li>
          @auth
              <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
              </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else 
                <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>
              
            @endauth
        </li>
        {{-- <li>
          <a href="http://blog.creative-tim.com" target="_blank">
            {{__(" Blog")}}
          </a>
        </li>
        <li>
          <a href="https://www.updivision.com" target="_blank">
            {{__(" Updivision")}}</a>
        </li> --}}
      </ul>
    </nav>
    <div class="copyright" id="copyright">
      &copy;
      <script>
        document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
      </script>, {{__(" Designed")}} & {{__("  Developed by")}}
      {{-- <a href="https://www.invisionapp.com" target="_blank">{{__(" Ogunsemowo Ifeoluwa")}}</a>{{__(" . Coded by")}} --}}
      <a href="https://www.linkedin.com/in/ifeoluwaolajide" target="_blank">{{__(" Ogunsemowo Ifeoluwa ")}}</a>
      {{-- & <a href="https://www.updivision.com" target="_blank">{{__(" Updivision")}}</a> --}}
    </div>
  </div>
</footer>