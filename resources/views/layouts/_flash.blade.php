@if (session()->has('flash_notification.message'))
    <div class="alert alert-{{ session()->get('flash_notification.level') }} alert-dismissible" role="alert">
        <div>
            {!! session()->get('flash_notification.message') !!}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
@endif
