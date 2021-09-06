<tr style="height: 28px;">
    <td><strong><small>{{ $label }}:</small></strong>&nbsp;&nbsp;&nbsp;</td>
    <td><input type="checkbox" class="{{ $class }}" {{ $checked }} data-key="{{ $key }}" /></td>
</tr>

@include("admin::grid/inline-edit/switch-script")