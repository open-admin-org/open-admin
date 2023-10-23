@if(Session::has('flashjs'))
    @php
        $flashjs     = Session::get('flashjs');
    @endphp
    <script>
        (function () {
            {!!  $flashjs  !!};
        }());
    </script>
@endif