<nav class="top-nav">
    <ul>
        <li>
            <a href="{{url('/home')}}" class="top-menu {{Route::currentRouteName() == 'home' ? 'top-menu--active' : '' }} ">
                <div class="top-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="top-menu__title"> Inicio </div>
            </a>
        </li>
        <li>
            <a href="{{route('get.depositos')}}" class="top-menu {{Route::currentRouteName() == 'get.depositos' ? 'top-menu--active' : '' }} ">
                <div class="top-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                <div class="top-menu__title"> Ubicar Pallets </div>
            </a>
        </li>

        <li>
            <a href="javascript:;" class="top-menu {{(Route::currentRouteName() == 'pedidos' ||
                                                      Route::currentRouteName() == 'pedidos.estados' ||
                                                      Route::currentRouteName() == 'ver.pedido.en.transito') ? ' top-menu--active' : '' }} ">
                <div class="top-menu__icon"> <i data-lucide="file-plus"></i> </div>
                <div class="top-menu__title"> Pedidos <i data-lucide="chevron-down" class="menu__sub-icon "></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{route('pedidos')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Pendientes SICOI</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('pedidos.estados')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">En transito</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('pedidos.armados')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Armados</div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{route('expedicion')}}" class="top-menu {{Route::currentRouteName() == 'expedicion' ? 'top-menu--active' : '' }} ">
                <div class="top-menu__icon"> <i data-lucide="file-text"></i> </div>
                <div class="top-menu__title"> Expedición </div>
            </a>
        </li>

        <li>
            <a href="javascript:;" class="top-menu {{(Route::currentRouteName() == 'preparar.palet' ||
                                                      Route::currentRouteName() == 'ver.preparar.armado' ||
                                                      Route::currentRouteName() == 'palet.pendientes' ||
                                                      Route::currentRouteName() == 'palet.armados') ? ' top-menu--active' : '' }} ">
                <div class="top-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="top-menu__title"> Palets <i data-lucide="chevron-down" class="menu__sub-icon "></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{route('preparar.palet')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Preparar Palet</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('palet.pendientes')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Palets en preparación</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('palet.armados')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Palets armados</div>
                    </a>
                </li>

            </ul>
        </li>

        <li>
            <a href="javascript:;" class="top-menu {{(Route::currentRouteName() == 'add.palet.envasado' ||
                                                      Route::currentRouteName() == 'palets.envasado') ? ' top-menu--active' : '' }} ">
                <div class="top-menu__icon"> <i data-lucide="archive"></i> </div>
                <div class="top-menu__title"> Palet Envasado (manual)<i data-lucide="chevron-down" class="menu__sub-icon "></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{route('add.palet.envasado')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Agregar nuevo</div>
                    </a>
                </li>

                <li>
                    <a href="{{route('palets.envasado')}}" class="top-menu">
                        <div class="top-menu__icon"> <i data-lucide="zap"></i> </div>
                        <div class="top-menu__title">Listado</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
