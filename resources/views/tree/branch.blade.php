<li class="dd-item" data-id="{{ $branch[$keyName] }}">
    <div class="dd-handle">
        {!! $branchCallback($branch) !!}
        <span class="pull-right dd-nodrag">
            <a href="{{ url("$path/$branch[$keyName]/edit") }}"><i class="icon-edit"></i></a>
            <a onclick="admin.tree.delete({{ $branch[$keyName] }})" data-id="{{ $branch[$keyName] }}" class="tree_branch_delete"><i class="icon-trash"></i></a>
        </span>
    </div>
    @if(isset($branch['children']))
    <ol class="dd-list">
        @foreach($branch['children'] as $branch)
            @include($branchView, $branch)
        @endforeach
    </ol>
    @endif
</li>