<!-- Main Sidebar -->
<div id="sidebar">
    <!-- Sidebar Brand -->
    <div id="sidebar-brand" class="themed-background">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-title">
            <span class="sidebar-nav-mini-hide">Ayamonte.mx</span>
        </a>
    </div>
    <!-- END Sidebar Brand -->

    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">

            <figure class="text-center">
                {{ HTML::image('assets/admin/img/logo.jpg', 'Ayamonte', array('class' => 'img-responsive')) }}
            </figure>

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class=" active"><i class="gi gi-compass sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard</span></a>
                </li>

                <li class="sidebar-separator">
                    <i class="fa fa-ellipsis-h"></i>
                </li>

                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-home sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Condominios</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.lots.index') }}">Ver listado</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.lots.create') }}">Agregar nuevo</a>
                        </li>
                    </ul>
                </li>
                <!--li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-list-ul sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Cuotas</span></a>
                    <ul>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-chevron-left sidebar-nav-indicator"></i>Ordinarias</a>
                            <ul>
                                <li>
                                    <a href="">Mensuales</a>
                                </li>
                                <li>
                                    <a href="">Anuales</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-chevron-left sidebar-nav-indicator"></i>Extraordinarias</a>
                            <ul>
                                <li>
                                    <a href="">De Obra</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-chevron-left sidebar-nav-indicator"></i>Especiales</a>
                            <ul>
                                <li>
                                    <a href="">De Reserva</a>
                                </li>
                                <li>
                                    <a href="">Adeudo 2010</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-dollar sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Intereses</span></a>
                    <ul>
                        <li>
                            <a href="">Ver todos</a>
                        </li>
                        <li>
                            <a href="">Agregar nuevo</a>
                        </li>
                    </ul>
                </li-->

                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Pagos</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.payments.index') }}">Listado de pagos</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.payments.create') }}">Generar pago</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-dollar sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Tarifas</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.years.index') }}">Ver todos</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.years.create') }}">Agregar nuevo</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-separator">
                    <i class="fa fa-ellipsis-h"></i>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-newspaper-o sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Novedades</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.news.index') }}">Ver listado</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.news.create') }}">Agregar nuevo</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-comment sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Comunicados</span></a>
                    <ul>
                        <li>
                            <a href="">Ver listado</a>
                        </li>
                        <li>
                            <a href="">Agregar nuevo</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-separator">
                    <i class="fa fa-ellipsis-h"></i>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-users sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Usuarios</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.users.index') }}">Ver listado</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.create') }}">Agregar nuevo</a>
                        </li>
                    </ul>
                </li>


                <!--li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-rocket sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">User Interface</span></a>
                    <ul>
                        <li>
                            <a href="page_ui_widgets.html">Widgets</a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-chevron-left sidebar-nav-indicator"></i>Elements</a>
                            <ul>
                                <li>
                                    <a href="page_ui_blocks_grid.html">Blocks &amp; Grid</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li-->
                <li class="sidebar-separator">
                    <i class="fa fa-ellipsis-h"></i>
                </li>
            </ul>
            <!-- END Sidebar Navigation -->


        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->

    <!-- Sidebar Extra Info -->
    <div id="sidebar-extra-info" class="sidebar-content sidebar-nav-mini-hide">
        <div class="text-center">
            <small>Desarrollador por <a href="http://ascmexico.com.mx" target="_blank">ASC México</a></small><br>
            <small>{{ date('Y') }} &copy; <a href="http://ayamonte.mx" target="_blank">Ayamonte.mx</a></small>
        </div>
    </div>
    <!-- END Sidebar Extra Info -->
</div>
<!-- END Main Sidebar -->