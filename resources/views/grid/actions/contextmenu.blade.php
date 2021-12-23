@extends('admin::grid.actions.dropdown')

@section('child')
<script>

    var contextMenu = document.createElement("div");
    contextMenu.classList.add("context-menu");
    document.body.appendChild(contextMenu);
    var lastParentMenu;

    document.querySelectorAll("table.select-table>tbody>tr").forEach(tr=>{

        tr.addEventListener("contextmenu",function(e){

            hideContextMenu();

            if (event.target.tagName == "TD"){
                let tr = event.target.closest("tr");
                let key = tr.dataset.key;
                let row_menu = tr.querySelector('td.column-__actions__ .dropdown-menu');
                lastParentMenu = row_menu.parentNode;
                contextMenu.innerHTML = '';
                contextMenu.appendChild(row_menu);
                show(contextMenu);

                var height = row_menu.offsetHeight;
                if (height > (document.body.clientHeight - e.pageY)) {
                    contextMenu.style.left = (e.pageX + 10)+"px";
                    contextMenu.style.top = (e.pageY - height)+"px";
                } else {
                    contextMenu.style.left = (e.pageX + 10)+"px";
                    contextMenu.style.top = (e.pageY - 10)+"px";
                }
            }

            e.preventDefault();
            e.stopPropagation();
            return false;
        },true)

    });

    document.addEventListener("contextmenu",function(e){
       hideContextMenu();
    },false);

    document.addEventListener("click",function(e){
       hideContextMenu();
    },false);

    function hideContextMenu(){
        let menu = contextMenu.querySelector(".dropdown-menu");
        if (menu){
            lastParentMenu.appendChild(menu);
            hide(contextMenu);
        }
    }

</script>
<style>
    .select-table .column-__actions__ {
        display: none !important;
    }
</style>
@endsection

