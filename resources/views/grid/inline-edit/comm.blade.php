{{--inline edit popover--}}

<span class="ie-wrap">
    <a
        id="{{ $trigger }}"
        class="ie"
        data-bs-toggle="popover"
        data-target="{{ $target }}"
        data-value="{{ $value }}"
        data-original="{{ $value }}"
        data-key="{{ $key }}"
        data-name="{{ $name }}"
        data-resource="{{ $resource }}"
        @isset($type)
        data-type="{{ $type }}"
        @endisset
        data-init="0"
    >
        <span class="ie-display">{{ $display }}</span>
        <i class="icon-edit" style="visibility: hidden;"></i>
    </a>
</span>

<template id="{{ $target }}">
    <div class="ie-content ie-content-{{ $name }}">
        <div class="ie-container">
            @yield('field')
            <div class="error"></div>
        </div>
        <div class="ie-action">
            <button class="btn btn-primary btn-sm ie-submit">{{ __('admin.submit') }}</button>
            <button class="btn btn-light btn-sm ie-cancel">{{ __('admin.cancel') }}</button>
        </div>
    </div>
</template>

<script>
    admin.grid.inline_edit.init_popover("{{$trigger}}","{{$target}}");
</script>

@yield('assert')
