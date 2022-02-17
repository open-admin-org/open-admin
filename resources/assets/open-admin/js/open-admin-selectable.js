/*-------------------------------------------------*/
/* admin.modals  */
/*-------------------------------------------------*/

    admin.selectable = {

        /*
        var config = {
            modal_elm : '#id / .class',
            url : 'resourceSelectUrl',
            update : function, //for setting value
            modalTrigger : '#id / .class', //select that triggers the modal',
            value : 'string, array or function'
        }
        */

        init : function(config){

            var modal_elm = config.modal_elm;
            var modal = new bootstrap.Modal(modal_elm);
            var related;
            var values;
            var rows = {};

            if (typeof(config.trigger) !== 'undefined'){
                document.querySelectorAll(config.trigger).forEach(elm=>{
                    elm.addEventListener("click",function (e) {
                        modal.show();
                        e.preventDefault();
                    });
                })
            }

            var load = function (url) {
                admin.ajax.request(url, {}, function (data) {
                    modal_elm.querySelector('.modal-body').innerHTML = data.data;

                    modal_elm.querySelectorAll(".form-check-input").forEach(input=>{

                        if (values.includes(String(input.value)) || values.includes(Number(input.value))){
                            input.checked = true;
                            rows[input.value] = input.closest("tr");
                        }
                        input.addEventListener("change",function(event){

                            if (event.target.checked){
                                if (input.type == "radio"){
                                    values = [input.value];
                                }else{
                                    values.push(input.value);
                                }
                                rows[input.value] = event.target.closest("tr");
                            }else{
                                values = arr_remove(values,input.value);
                            }
                        })
                    })
                });
            };

            modal_elm.ref = this;
            modal_elm.modal = modal;
            modal_elm.addEventListener('show.bs.modal', function (event) {

                related = event.relatedTarget;
                this.ref.currentModal = this.modal;

                admin.ajax.currenTarget = modal_elm.querySelector('.modal-body');

                if (typeof(config.value) != 'undefined'){
                    if (typeof(config.value) === 'function'){
                        values = config.value(related);
                    }else{
                        values = config.value;
                    }

                    if (typeof(values) === "string"){
                        values = [values];
                    }
                }else{
                    values = [];
                }

                load(config.url);
            })
            modal_elm.addEventListener('hide.bs.modal', function (event) {
                admin.ajax.currenTarget = false;
            })

            modal_elm.querySelector('.modal-footer .submit').addEventListener('click', function (event) {

                if (typeof(config.update) != 'undefined'){
                    config.update(values,rows,related);
                }
                modal.hide();

                event.preventDefault();
                event.stopPropagation();
                return false;
            });

            modal_elm.addEventListener('click', function (event) {

                if (event.target.classList.contains('submit')){
                    var form = event.target.closest("form");
                    var formData = new FormData(form);
                    var queryString = new URLSearchParams(formData).toString();
                    load(form.getAttribute('action')+'&'+queryString);
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                if (event.target.classList.contains('btn-light')){
                    var form = event.target.closest("form");
                    if (form){
                        load(form.getAttribute('action'));
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                /*
                // now handeled through admin.ajax.currentTarget
                if (event.target.classList.contains('page-link')){
                    load(event.target.getAttribute("href"));
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }
                */

                if (event.target.tagName == "TD"){
                    event.target.parentNode.querySelector(".form-check-input").click();
                }
            })
        },

        hideModal : function (){
            this.currentModal.hide();
        }
    }
