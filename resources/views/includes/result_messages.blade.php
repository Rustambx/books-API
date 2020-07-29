@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session()->get('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif


