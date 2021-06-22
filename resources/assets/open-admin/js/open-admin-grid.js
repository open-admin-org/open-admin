/*-------------------------------------------------*/
/* grid  */
/*-------------------------------------------------*/

    admin.grid = {

        selected : [], // array with selected items

        init : function(){

            let trs = document.querySelectorAll(".select-table tr");
            trs.forEach(tr => {
                tr.addEventListener('click', function(event) {
                    if (!event.target.classList.contains("form-check-input") && !event.target.classList.contains("icon")){
                        input = event.target.closest("tr").getElementsByClassName("form-check-input")[0];
                        if (input){
                            input.checked  ^= 1;
                            admin.grid.check_status();
                        }
                    }
                }, false);
            });

          // allow dropdown to extend outside responsive table
            let table = document.querySelector('.table-responsive');
            if (table){
                table.addEventListener('show.bs.dropdown', function (event) {
                    document.querySelector('.table-responsive').style.overflow = "inherit";
                });
                table.addEventListener('hide.bs.dropdown', function (event) {
                    document.querySelector('.table-responsive').style.overflow = "auto";
                });
            }
        },

        select_all : function(event,checkbox){

            let boxes = document.querySelectorAll(".grid-row-checkbox");
            for (const box of boxes) {
                box.click();
            }
            admin.grid.check_status();
        },

        select_row : function (event,checkbox){
            admin.grid.check_status();
        },

        check_status : function(){

            admin.grid.selected = [];

            let all_select = true;
            let num_selected = 0;

            document.querySelectorAll(".grid-row-checkbox").forEach( box => {

                if (!box.checked){
                    all_select = false;
                    box.closest("tr").classList.remove("selected");
                }else{
                    admin.grid.selected.push(box.dataset.id);
                    box.closest("tr").classList.add("selected");
                    num_selected ++;
                }
            })

            document.getElementById("grid-select-all").checked = all_select;

            let el = document.querySelector(".grid-select-all-btn");
            if(num_selected){
                el.classList.remove("d-none");
            }else{
                el.classList.add("d-none");
            }
            let str = el.querySelectorAll(".selected")[0].getAttribute("data");
            el.querySelectorAll(".selected")[0].innerHTML = str.replace('{n}',num_selected);
        },

        export_selected_row : function(event){

            let rows = admin.grid.selected.join();
            if (rows == ""){
                Swal.fire({
                    icon: 'info',
                    text: event.target.dataset.no_rows_selected,
                });
            }else{
                let href = event.target.getAttribute("href");
                window.location = href.replace('__rows__', rows);
            }

            event.preventDefault();
        },

        columns : {

            all : function(){
                document.querySelectorAll('.form-check-input').forEach(cb => {
                    cb.checked = true;
                });
                admin.grid.columns.submit();
            },

            submit : function(){

                let selected = [];
                let defaults = new String(document.getElementById("grid-column-selector").dataset.defaults).split(",");
                document.querySelectorAll('.form-check-input:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length == 0) {
                    return;
                }

                let url = new URL(location);
                if (selected.sort().toString() == defaults.sort().toString()) {
                    url.searchParams.delete('_columns_');
                } else {
                    url.searchParams.set('_columns_', selected.join());
                }
                admin.ajax.navigate(url.toString());

            }
        },
    }
