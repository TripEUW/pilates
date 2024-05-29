<!-- begin:: Aside -->
<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

<!-- begin:: Aside -->
<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">


<div class="row w-100 h-100">

<div class="col-10 col-xl-10 d-flex justify-content-center align-items-center">
<a href="{{route('dashboard')}}">
<img class="logo-dashboard-long" alt="Logo" src="{{url('assets/images/logo-dashboard.png')}}" />
</a>
</div>
<div class="col-2 col-xl-2 d-flex justify-content-center  align-items-center">

<div class="kt-aside__brand-tools">
<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<polygon id="Shape" points="0 0 24 0 24 24 0 24" />
<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
</g>
</svg></span>
<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<polygon id="Shape" points="0 0 24 0 24 24 0 24" />
<path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" />
<path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
</g>
</svg></span>
</button>

<!--
<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
-->
</div>
</div>

</div>

</div>
<!-- end:: Aside -->

<!-- begin:: Aside Menu -->
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
<ul class="kt-menu__nav">



{{-- <li class="kt-menu__section ">
<h4 class="kt-menu__section-text">MODULOS</h4>
<i class="kt-menu__section-icon flaticon-more-v2"></i>
</li> --}}


{{-- Panel de control Start--}}
<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z M10,13 C9.44771525,13 9,13.4477153 9,14 L9,17 C9,17.5522847 9.44771525,18 10,18 L14,18 C14.5522847,18 15,17.5522847 15,17 L15,14 C15,13.4477153 14.5522847,13 14,13 L10,13 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg></span><span class="kt-menu__link-text">Panel de Control</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
    <ul class="kt-menu__subnav">
    
        @if (PilatesHelper::getRolPermissionStatus(null,'administration_config',false))
        <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('administration_config')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('administration_config')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Configuración</span></a></li>
        @endif
        @if(PilatesHelper::getRolPermissionStatus(null,'rol_and_permission'))
        <li class="kt-menu__item  kt-menu__item {{ PilatesHelper::getMenuEnable('rol_and_permission')?'kt-menu__item--active' : '' }}" aria-haspopup="true"><a href="{{route('rol_and_permission')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Roles y Permisos</span></a></li>
        @endif
        @if (PilatesHelper::getRolPermissionStatus(null,'administration_backup',false))
        <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('administration_backup')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('administration_backup')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Copias de Seguridad</span></a></li>
        @endif
        @if (PilatesHelper::getRolPermissionStatus(null,'template',false))
        <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('template')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('template')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Plantillas</span></a></li>
        @endif

        @if(PilatesHelper::getRolPermissionStatus(null,'schedule',false))
        <li  class="kt-menu__item   {{ PilatesHelper::getMenuEnable('schedule')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('schedule')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Horarios</span></a></li>
        @endif

        @if(PilatesHelper::getRolPermissionStatus(null,'audit',false))
        <li  class="kt-menu__item   {{ PilatesHelper::getMenuEnable('audit')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('audit')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Auditorias (logs)</span></a></li>
        @endif


    </ul>
    </div>
</li>
{{-- Panel de control end --}}

{{-- Administración Start--}}
<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" id="Combined-Shape" fill="#000000"/>
        <rect id="Rectangle-152" fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"/>
        <rect id="Rectangle-152-Copy" fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"/>
    </g>
</svg></span><span class="kt-menu__link-text">Administración</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
    <ul class="kt-menu__subnav">
 
    @if (PilatesHelper::getRolPermissionStatus(null,'administration_sale',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('administration_sale')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('administration_sale')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">TPV (Ventas)</span></a></li>
    @endif
@if(PilatesHelper::getRolPermissionStatus(null,'dashboard'))
<li  class="kt-menu__item   {{ PilatesHelper::getMenuEnable('dashboard') || PilatesHelper::getMenuEnable('/')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('dashboard')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Planificador</span></a></li>
@endif
@if (PilatesHelper::getRolPermissionStatus(null,'administration_billing',false))
<li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('administration_billing')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('administration_billing')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Facturación</span></a></li>
@endif
@if (PilatesHelper::getRolPermissionStatus(null,'report_report_cash_register',false))
<li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('report_report_cash_register')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('report_report_cash_register')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Arqueo Diario</span></a></li>
@endif




    </ul>
    </div>
</li>
{{-- Administración end --}}

{{-- Gestión de tablas start --}}
<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
    <polygon id="Bound" points="0 0 24 0 24 24 0 24" />
    <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" id="Shape" fill="#000000" fill-rule="nonzero" />
    <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" id="Path" fill="#000000" opacity="0.3" />
    </g>
    </svg></span><span class="kt-menu__link-text">Gestión de Tablas</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
  
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
    <ul class="kt-menu__subnav">
    @if (PilatesHelper::getRolPermissionStatus(null,'management_client',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('management_client')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('management_client')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Clientes</span></a></li>
    @endif
    @if (PilatesHelper::getRolPermissionStatus(null,'management_product',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('management_product')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('management_product')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Productos</span></a></li>
    @endif
    @if (PilatesHelper::getRolPermissionStatus(null,'management_employee',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('management_employee')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('management_employee')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Empleados</span></a></li>
    @endif
    @if (PilatesHelper::getRolPermissionStatus(null,'management_room_group',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('management_room_group')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('management_room_group')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Salas y Grupos</span></a></li>
    @endif
    @if (PilatesHelper::getRolPermissionStatus(null,'management_sale',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('management_sale')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('management_sale')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Registro Ventas</span></a></li>
    @endif
    </ul>
    </div>
</li>
{{-- Gestión de tablas end --}}



@if(auth()->user()->id_rol != 1)
{{-- Mi horario start --}}
@if(PilatesHelper::getRolPermissionStatus(null,'schedule_employee',true))
<li  class="kt-menu__item   {{ PilatesHelper::getMenuEnable('schedule_employee')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('schedule_employee')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
width="512px" height="512px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve" class="kt-svg-icon">
<g>
<path d="M446.562,98H429v15.058C429,126.271,418.629,137,405.415,137h-0.36C391.841,137,381,126.271,381,113.058V98H137v15.058
C137,126.271,125.9,137,112.686,137h-0.361C99.11,137,88,126.271,88,113.058V98H65.439C46.419,98,31,113.372,31,132.392v303.061
C31,454.473,46.419,470,65.439,470h381.123c19.02,0,34.438-15.527,34.438-34.548V132.392C481,113.372,465.581,98,446.562,98z
M445,402.562c0,19.02-15.419,34.438-34.439,34.438H102.439C83.419,437,68,421.581,68,402.562V206.438
C68,187.418,83.419,172,102.439,172h308.121C429.581,172,445,187.418,445,206.438V402.562z"  id="Combined-Shape" fill="#000000" opacity="0.3"/>
<path d="M112.5,128.947c10.144,0,18.5-8.223,18.5-18.367V60.07c0-10.145-8.356-18.367-18.5-18.367S94,49.926,94,60.07v50.51
C94,120.725,102.356,128.947,112.5,128.947z"   id="Shape" fill="#000000" fill-rule="nonzero"/>
<path d="M405.5,128.947c10.144,0,18.5-8.224,18.5-18.367V60.07c0-10.145-8.356-18.367-18.5-18.367
c-10.143,0-18.5,8.223-18.5,18.367v50.51C387,120.724,395.357,128.947,405.5,128.947z"   id="Shape" fill="#000000" fill-rule="nonzero"/>
<path d="M412,207h-14v31h-60v-31h-14v31h-62v-31h-14v31h-60v-31h-14v31h-61v-31H99v31h-8v13h8v62h-8v14h8v58h-8v13h8v22h14v-22h61
v22h14v-22h60v22h14v-22h62v22h14v-22h60v22h14v-22h8v-13h-8v-58h8v-14h-8v-62h8v-13h-8V207z M174,385h-61v-58h61V385z M174,313
h-61v-62h61V313z M248,385h-60v-58h60V385z M248,313h-60v-62h60V313z M324,385h-62v-58h62V385z M324,313h-62v-62h62V313z M398,385
h-60v-58h60V385z M398,313h-60v-62h60V313z"   id="Shape" fill="#000000" fill-rule="nonzero"/>
</g>
</svg>
</span><span class="kt-menu__link-text">Mi Horario</span></a></li>
@endif
@endif
{{-- Mi horario end --}}


{{-- Historial clínico start --}}
@if(PilatesHelper::getRolPermissionStatus(null,'medical_history',false))
<li  class="kt-menu__item   {{ PilatesHelper::getMenuEnable('medical_history')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('medical_history')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<rect id="bound" x="0" y="0" width="24" height="24"/>
<path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
<path d="M14.35,10.5 C13.54525,10.5 12.604125,11.4123161 12.1,12 C11.595875,11.4123161 10.65475,10.5 9.85,10.5 C8.4255,10.5 7.6,11.6110899 7.6,13.0252044 C7.6,14.5917348 9.1,16.25 12.1,18 C15.1,16.25 16.6,14.625 16.6,13.125 C16.6,11.7108856 15.7745,10.5 14.35,10.5 Z" id="Shape" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
</g>
</svg></span><span class="kt-menu__link-text">Historial Clínico</span></a></li>
@endif
{{-- Historial clínico end --}}

{{-- Informes start --}}
<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" id="Combined-Shape" fill="#000000"/>
        <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
    </g>
</svg></span><span class="kt-menu__link-text">Informes</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
  
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
    <ul class="kt-menu__subnav">
    @if (PilatesHelper::getRolPermissionStatus(null,'report_report_listings',false))
    <li class="kt-menu__item  {{ PilatesHelper::getMenuEnable('report_report_listings')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('report_report_listings')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Listados</span></a></li>
    @endif
    </ul>
    </div>
</li>
{{-- Informes end --}}


{{-- start example submenus into --}}
{{-- <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<rect id="bound" x="0" y="0" width="24" height="24" />
<path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" id="Combined-Shape" fill="#000000" opacity="0.3" />
<path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" id="Combined-Shape" fill="#000000" />
</g>
</svg></span><span class="kt-menu__link-text">Subheaders</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
<ul class="kt-menu__subnav">
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_toolbar.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Toolbar Nav</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_actions.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Actions Buttons</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_tabbed.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tabbed Nav</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_classic.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Classic</span></a></li>
<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
<a href="javascript:;" class="kt-menu__link kt-menu__toggle"> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Subheaders</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
<ul class="kt-menu__subnav">
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_toolbar.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Toolbar Nav</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_actions.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Actions Buttons</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_tabbed.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tabbed Nav</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_classic.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Classic</span></a></li>
<li class="kt-menu__item " aria-haspopup="true"><a href="layout_subheader_none.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">None</span></a></li>
</ul>
</div>
</li>
</ul>
</div>
</li> --}}
{{-- end example submenus into --}}



</ul>
</div>
</div>

<!-- end:: Aside Menu -->
</div>

<!-- end:: Aside -->