@php 
$notifications=PilatesHelper::getNotificationsEmployee(auth()->user()->id,auth()->user()->id_rol); 
$notificationsNoRead=PilatesHelper::getNotificationsNoRead(auth()->user()->id,auth()->user()->id_rol); 
$statusDayWork=PilatesHelper::getStatusDayWorkEmployee(auth()->user()->id); 
@endphp
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
<!-- begin:: Header -->
    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

        <!-- begin:: Header Menu -->
        <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
        <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
       
        </div>

        <!-- end:: Header Menu -->

        <!-- begin:: Header Topbar -->
        <div class="kt-header__topbar">

           

            <!--begin: Notifications -->
            <div class="kt-header__topbar-item dropdown">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
               
                    <!--
Use dot badge instead of animated pulse effect: 
<span class="kt-badge kt-badge--dot kt-badge--notify kt-badge--sm kt-badge--brand"></span>
-->
                </div>
                <div id="menu-notifications" class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
                    <form>

                        <!--begin: Head -->
                        <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b pt-0 p-5" style="background-image: url({{asset("assets/$theme")}}/media/misc/bg-1.jpg);padding:10px !important;">
                            <h3 class="kt-head__title" id="container-new-notifications">
                               Notificaciónes
                          
                                &nbsp;
                                <a href="{{route('notification')}}"> <span class="btn btn-success btn-sm btn-bold btn-font-md">  Ver todas </span></a>
                             
                            </h3>
                           {{-- <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications" role="tab" aria-selected="true">Alerts</a>
                                </li>
                                 <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events" role="tab" aria-selected="false">Events</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs" role="tab" aria-selected="false">Logs</a>
                                </li>
                            </ul> --}}
                        </div>

                        <!--end: Head -->
                       
                        <div class="tab-content">
                            <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                                <div id="container-notifications" class="kt-notification kt-margin-t-0 kt-margin-b-0 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                                 
                                    @foreach($notifications as $notification) 
                                    <a href="#"  onclick="readOpenNotification({{$notification->id}},this)" class="kt-notification__item {{($notification->status=='no_read')?'no_read':''}}">
                                        <div class="kt-notification__item-icon">
                                            @if($notification->type_icon=='html-class')
                                           <i class="{{$notification->icon}}"></i>
                                            @endif
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                {{$notification->title}}
                                            </div>
                                            <div class="kt-notification__item-time">
                                                {{$notification->message}}
                                            </div>
                                            <div class="kt-notification__item-time">
                                                {{$notification->date}}
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach   
                                    @if($notifications->count()<=0)
                                    <div id="default-notification-text" class="w-100 h-100 d-flex justify-content-center align-items-center"><span class="kt-grid-nav__desc">Nínguna notificación para mostrar.</span> </div>
                                    @endif
                                    {{-- <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-time kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Falta injustificada
                                            </div>
                                            <div class="kt-notification__item-time">
                                                Empleado: Neha Murazik Dooley
                                            </div>
                                        </div>
                                    </a> --}}

                                    <div>

                                    </div>
                                  
                              
                                </div>
                            </div>
                        
                            
                        </div>
                    </form>
                </div>
            </div>

            <!--end: Notifications -->
        
            <?php if(PilatesHelper::getStatusStatusModuleAssitances()){ ?>
             <!--begin: Quick Actions -->
            <div class="kt-header__topbar-item dropdown" id="assistances-btn">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
                    <span class="kt-header__topbar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
                                <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" id="Path-94-Copy" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                                <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                            </g>
                        </svg> </span>
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                    <form>

                        <!--begin: Head -->
                        <div class="kt-head kt-head--skin-dark" style="background-image: url({{asset("assets/$theme")}}/media/misc/bg-1.jpg)">
                            <h3 class="kt-head__title">
                               Asistencia
                               <span class="kt-space-15"></span>
                            <span class="btn btn-success btn-sm btn-bold btn-font-md">{{$statusDayWork['status_formated']}}</span>
                            </h3>
                        </div>

                        <!--end: Head -->

                        <!--begin: Grid Nav -->
                        <div class="kt-grid-nav kt-grid-nav--skin-light">
                            @if($statusDayWork['status'] || isset($statusDayWork['except']))
                            <div class="kt-grid-nav__row">
  
                            <a href="#" onclick="document.getElementById('form_set_time_in').submit()" class="kt-grid-nav__item">
                                    <span class="kt-grid-nav__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
                                                <rect id="Rectangle" fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                            </g>
                                        </svg> </span>
                                
                                    @if($statusDayWork['in_time_finish']!=null)
                                    <span class="kt-grid-nav__title"><br></span>
                                    <span class="kt-grid-nav__desc icon-font-red">Entraste hoy a las: <br> <br> {{$statusDayWork['in_time_finish']}}</span>
                                    @else
                                    <span class="kt-grid-nav__title">Marcar entrada</span>
                                    <span class="kt-grid-nav__desc">Entras hoy a las: <br> <br> {{$statusDayWork['in_time']}}</span>
                                    @endif
                                  
                                </a>
                       
                                <a href="#" onclick="document.getElementById('form_set_time_out').submit()" class="kt-grid-nav__item">
                                    <span class="kt-grid-nav__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
                                                <rect id="Rectangle" fill="#000000" opacity="0.3" transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) " x="14" y="7" width="2" height="10" rx="1"/>
                                                <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
                                            </g>
                                        </svg> </span>
                                   
                                        @if($statusDayWork['out_time_finish']!=null)
                                        <span class="kt-grid-nav__title"><br></span>
                                        <span class="kt-grid-nav__desc icon-font-red">Saliste hoy a las: <br> <br> {{$statusDayWork['out_time_finish']}}</span>
                                        @else
                                        <span class="kt-grid-nav__title">Marcar salida</span>
                                        <span class="kt-grid-nav__desc">Sales hoy a las: <br> <br> {{$statusDayWork['out_time']}}</span>
                                        @endif
                                </a>
                          
                            </div>
                            @endif
                         
                        </div>
                        <!--end: Grid Nav -->
                    </form>
                </div>
            </div>
        <?php } ?>
<form action="{{route('attendances_set_time_out')}}"  id="form_set_time_out" method="POST">
@csrf
@method('post')
<input type="hidden" name="id" value="{{auth()->user()->id}}">
</form>

<form action="{{route('attendances_set_time_in')}}" id="form_set_time_in" method="POST">
@csrf
@method('post')
<input type="hidden" name="id" value="{{auth()->user()->id}}">
</form>

            <!--end: Quick Actions --> 

    

            <!--begin: User Bar -->
            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                    <div class="kt-header__topbar-user">
                        <span class="kt-header__topbar-welcome kt-hidden-mobile"><strong>Hola,</strong></span>
                    <span class="kt-header__topbar-username kt-hidden-mobile">{{ auth()->user()->name }}</span>
                        <img class="kt-hidden" alt="Pic" src="{{asset("assets/$theme")}}/media/users/300_25.jpg" />

                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                        <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold picture-profile-span">
                                <img  class="picture-profile w-100" src="{{ (empty(auth()->user()->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".auth()->user()->picture) }}"  />   
                               
                        </span>
                    </div>
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                    <!--begin: Head -->
                    <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{asset("assets/$theme")}}/media/misc/bg-1.jpg)">
                        <div class="kt-user-card__avatar">
                            <img class="kt-hidden" alt="Pic" src="{{asset("assets/$theme")}}/media/users/300_25.jpg" />

                            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                            <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success picture-profile-span">
                                    <img  class="picture-profile" src="{{ (empty(auth()->user()->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".auth()->user()->picture) }}"  />  
                            </span>
                        </div>
                        <div class="kt-user-card__name">
                                {{ auth()->user()->name.' '.auth()->user()->last_name }}
                              
                        </div>
                   
                    </div>

                    <!--end: Head -->

                    <!--begin: Navigation -->
                    <div class="kt-notification">
                        <div class="kt-user-card__badge w-100 text-center m-2">
                        <span class="btn btn-success btn-sm btn-bold btn-font-md text-capitalize">{{PilatesHelper::getNameRolById(auth()->user()->id_rol)}}</span>
                        </div>
                    <a href="{{route('employee_profile')}}" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-calendar-3 kt-font-brand""></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    Mi perfil
                                </div>
                                <div class="kt-notification__item-time">
                                    Configuraciones de cuenta y más
                                </div>
                            </div>
                        </a>
                  
                    <a href="{{route('schedule_employee')}}" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon-calendar-2 kt-font-brand"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    Mi horario
                                </div>
                                <div class="kt-notification__item-time">
                                  Mi horario de trabajo y vacaciónes
                                </div>
                            </div>
                        </a>
                        <div class="kt-notification__custom">
                        <a href="{{route('logout')}}"  class="btn btn-brand btn-elevate btn-icon-sm">Cerrar sesión</a>
                        </div>
                    </div>

                    <!--end: Navigation -->
                </div>
            </div>

            <!--end: User Bar -->
        </div>

        <!-- end:: Header Topbar -->
    </div>

    <!-- end:: Header -->
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
<!-- begin:: Subheader -->
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">

            @include("$theme/parts/breadcrumbs")
     
        </div>
        <!-- end:: Subheader -->