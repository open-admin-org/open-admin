@if(is_array($errorKey))

    @foreach($errorKey as $key => $col)
        @if($errors->has($col.$key))
        <div class="alert alert-danger">
            <ul class="m-0">
            @foreach($errors->get($col.$key) as $message)
                <li for="inputError"> {{$message}}</li>
            @endforeach
            </ul>
            </div>
        @endif
    @endforeach

@else

    @if($errors->has($errorKey))
        <div class="alert alert-danger">
            <ul class="m-0 ps-3">
            @foreach($errors->get($errorKey) as $message)
                <li for="inputError"> {{$message}}</li>
            @endforeach
            </ul>
        </div>
    @endif

@endif