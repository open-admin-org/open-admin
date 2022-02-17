


<h4 class="embed-title ps-3" style="font-size:1.2rem;">{{ $label }}</h4>
<hr>

<div id="embed-{{$column}}" class="embed-{{$column}}">


    <div class="embed-{{$column}}-forms">

        <div class="embed-{{$column}}-form fields-group">

            @foreach($form->fields() as $field)
                {!! $field->render() !!}
            @endforeach

        </div>
    </div>
</div>

<hr>