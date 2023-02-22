<nav class="top-nav">
    <ul>

        <li>
            <a href="{{url('/home')}}" class="top-menu">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title"> Inicio </div>
            </a>
        </li>

        <li>
            <a href="javascript:;.html" class="top-menu">
                <div class="side-menu__icon"> <i data-lucide="codepen"></i> </div>
                <div class="side-menu__title">
                    Almacén
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-open">
                <li>
                    <a href="{{route('lotes')}}" class="top-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Lotes </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('pallets')}}" class="top-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Pallets </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('products')}}" class="top-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Productos </div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{route('get.depositos')}}" class="top-menu">
                <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                <div class="side-menu__title"> Depósitos </div>
            </a>
        </li>

        <li>
            <a href="{{url('/home')}}" class="top-menu">
                <div class="side-menu__icon"> <i data-lucide="codesandbox"></i> </div>
                <div class="side-menu__title"> Órdenes </div>
            </a>
        </li>

        <li>
            <a href="{{route('usuarios')}}" class="top-menu">
                <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                <div class="side-menu__title"> Usuarios </div>
            </a>
        </li>
    </ul>
</nav>
