@if($errors->any())

<div class="alert alert-outline-danger fade show p-1 mb-0" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">
        <ul>
        @foreach ($errors->all() as $error)
        <li>{!! $error !!}</li>
        @endforeach
    </ul>
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="la la-close"></i></span>
        </button>
    </div>
</div>
<br>

@endif

@if(session('success'))

<div class="alert alert-outline-success fade show p-1 mb-0" role="alert">
    <div class="alert-icon"><i class="flaticon2-accept"></i></div>
    <div class="alert-text">
        
     {!! session('success') !!}
  
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="la la-close"></i></span>
        </button>
    </div>
</div>
<br>

@endif


@if(session('warning'))

<div class="alert alert-warning fade show p-1 mb-0" role="alert">
    <div class="alert-icon"><i class="flaticon2-accept"></i></div>
    <div class="alert-text">
        
     {!! session('warning') !!}
  
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="la la-close"></i></span>
        </button>
    </div>
</div>
<br>

@endif

@if(session('danger'))

<div class="alert alert-outline-danger fade show p-1 mb-0" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">
        
     {!! session('danger') !!}
  
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="la la-close"></i></span>
        </button>
    </div>
</div>
<br>

@endif


@if (session('status'))

<div class="alert alert-outline-success fade show p-1 mb-0" role="alert">
    <div class="alert-icon"><i class="flaticon2-accept"></i></div>
    <div class="alert-text">
        
     {{session('status')}}
  
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="la la-close"></i></span>
        </button>
    </div>
</div>
<br>
@endif




