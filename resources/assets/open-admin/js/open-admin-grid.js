/*-------------------------------------------------*/
/* grid  */
/*-------------------------------------------------*/

admin.grid = {

    selected: [], // array with selected items
    stickyTimer: null,

    init: function () {

        let trs = document.querySelectorAll(".select-table tr");
        trs.forEach(tr => {
            tr.addEventListener('click', function (event) {

                if (event.target.tagName == "TD") {
                    input = event.target.closest("tr").getElementsByClassName("row-selector")[0];
                    if (input) {
                        id = input.dataset.id;
                        document.querySelectorAll(".row-" + id + " .row-selector").forEach(input => {
                            input.checked ^= 1;
                        });
                        admin.grid.check_status();
                    }
                }
            }, false);
        });

        // allow dropdown to extend outside responsive table
        let table = document.querySelector('.table-responsive');
        if (table) {

            let actions = document.querySelector('th.column-__actions__');
            if (actions) {
                admin.cleanup.add(function () {
                    table.removeEventListener('scroll', admin.grid.checkStickEvent);
                    window.removeEventListener('resize', admin.grid.checkStickEvent);
                })

                table.addEventListener('scroll', admin.grid.checkStickEvent);
                window.addEventListener('resize', admin.grid.checkStickEvent);
                admin.grid.checkSticky();
            }
        }
    },

    checkStickEvent: function () {
        clearTimeout(admin.grid.stickyTimer)
        admin.grid.stickyTimer = setTimeout(function () {
            admin.grid.checkSticky()
        }, 100);
    },

    checkSticky: function () {
        let check, prev, table;
        if (!table) {
            table = document.querySelector('.table-responsive');
            check = table.querySelector("th.column-__actions__");
            prev = check.previousElementSibling;
        }

        let checkPos = check.getBoundingClientRect();
        let prevPos = prev.getBoundingClientRect();
        table.classList.toggle("sticky-actions", (checkPos.x - (prevPos.x + prevPos.width)) < -1);
    },

    select_all: function (event, checkbox) {

        let boxes = document.querySelectorAll(".grid-row-checkbox");
        for (const box of boxes) {
            box.checked ^= checkbox.checked;
        }
        admin.grid.check_status();
    },

    select_row: function (event, checkbox) {
        admin.grid.check_status();
    },

    check_status: function () {

        admin.grid.selected = [];

        let all_select = true;
        let num_selected = 0;

        document.querySelectorAll(".grid-row-checkbox").forEach(box => {

            if (!box.checked) {
                all_select = false;
                box.closest("tr").classList.remove("selected");
            } else {
                box.closest("tr").classList.add("selected");
                if (!admin.grid.selected.includes(box.dataset.id)) {
                    admin.grid.selected.push(box.dataset.id);
                    num_selected++;
                }
            }
        })

        document.getElementById("grid-select-all").checked = all_select;

        let elms = document.querySelectorAll(".show-on-rows-selected");
        elms.forEach(el => {
            if (num_selected) {
                el.classList.remove("d-none");
            } else {
                el.classList.add("d-none");
            }
            let el_selected = el.querySelectorAll(".selected");
            if (el_selected.length) {
                let str = el_selected[0].getAttribute("data");
                el.querySelectorAll(".selected")[0].innerHTML = str.replace('{n}', num_selected);
            }
        });

    },

    export_selected_row: function (event) {

        let rows = admin.grid.selected.join();
        if (rows == "") {
            Swal.fire({
                icon: 'info',
                text: event.target.dataset.no_rows_selected,
            });
        } else {
            let href = event.target.getAttribute("href");
            window.location = href.replace('__rows__', rows);
        }

        event.preventDefault();
    },

    columns: {

        all: function () {
            document.querySelectorAll('.column-selector').forEach(cb => {
                cb.checked = true;
            });
            admin.grid.columns.submit();
        },

        submit: function () {

            let selected = [];
            let defaults = new String(document.getElementById("grid-column-selector").dataset.defaults).split(",");
            document.querySelectorAll('.column-selector:checked').forEach(cb => {
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

    hotkeys: function () {

        document.removeEventListener("keydown", admin.grid.hotkeys_handle, false);
        document.addEventListener("keydown", admin.grid.hotkeys_handle, false);
    },

    hotkeys_handle: function (e) {

        var tag = e.target.tagName.toLowerCase();

        if (tag == 'input' || tag == 'textarea' || e.ctrlKey || e.metaKey || e.altKey || e.shiftKey) {
            return;
        }

        var current_page = document.querySelector('.pagination .page-item.active');

        switch (e.which) {

            case 82: // `r` for reload
                admin.ajax.reload();
                admin.toastr.success(__('refresh_succeeded'), { positionClass: "toast-top-center" });
                break;

            case 83: // `s` for search
                let qs = document.querySelector('input.grid-quick-search');
                if (qs == null) {
                    console.log('Quick search not enabled');
                } else {
                    qs.focus();
                }
                break;

            case 70: // `f` for open filter
                var myCollapse = document.getElementById('filter-box')
                var bsCollapse = new bootstrap.Collapse(myCollapse, {
                    toggle: false
                })
                bsCollapse.toggle();
                break;

            case 67: // `c` go to create page
                document.querySelector('.grid-create-btn').click();
                break;

            case 37: // `left` for go to prev page
                if (current_page.previousElementSibling.querySelector("a") != null) {
                    current_page.previousElementSibling.querySelector("a").click();
                }
                break;

            case 39: // `right` for go to next page
                if (current_page.nextElementSibling.querySelector("a") != null) {
                    current_page.nextElementSibling.querySelector("a").click();
                }
                break;

            case 46: // `delete` batch delete
                if (admin.grid.selected.length) {
                    document.querySelector(".batch-action.BatchDelete").click();
                }
                break;

            case 69: // `e` batch edit
                if (admin.grid.selected.length) {
                    document.querySelector(".batch-action.BatchEdit").click();
                }
                break;

            default: return;
        }
        e.preventDefault();

    }
}
