<div class="top-bar-boxed top-bar-boxed--top-menu h-[70px] md:h-[65px] z-[51] border-b border-white/[0.08] mt-12 md:mt-0 -mx-3 sm:-mx-8 md:-mx-0 px-3 md:border-b-0 relative md:fixed md:inset-x-0 md:top-0 sm:px-8 md:px-10 md:pt-10 md:bg-gradient-to-b md:from-slate-100 md:to-transparent dark:md:from-darkmode-700">
    <div class="h-full flex items-center">
        <a href="{{url('/')}}" class="logo -intro-x hidden md:flex xl:w-[180px] block">
            <img alt="Pensudev" class="logo__image w-6" src="{{asset('dist/images/logo.png')}}" style="width: 28px">
            <span class="logo__text text-white text-lg ml-3" style="font-size: 14px"> Pensudev</span>
        </a>

        <nav aria-label="breadcrumb" class="-intro-x h-[45px] mr-auto">
            <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="{{url('/home')}}">GCC</a></li>
            </ol>
        </nav>

        <div class="intro-x dropdown w-8 h-8">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                <img alt="Pensudev" src="{{asset('dist/images/avatar-1.jpg')}}">
            </div>
            <div class="dropdown-menu w-56">
                <ul class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                    <li class="p-2">
                        <div class="font-medium">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">{{Auth::user()->rol}}</div>
                    </li>
                    <li  class="breadcrumb-item">
                        <hr class="dropdown-divider border-white/[0.08]">
                    </li>
                    <li  class="breadcrumb-item">
                        <a href="{{route('configuraciones')}}" class="dropdown-item hover:bg-white/5"> <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Configuraciones </a>
                    </li>
                    <li  class="breadcrumb-item">
                        <hr class="dropdown-divider border-white/[0.08]">
                    </li>
                    <li  class="breadcrumb-item">
                        <a class="dropdown-item hover:bg-white/5" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                            <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Salir
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
