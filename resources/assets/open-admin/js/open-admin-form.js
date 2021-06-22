/*-------------------------------------------------*/
/* forms */
/*-------------------------------------------------*/

admin.form = {

    id : false,
    tabs_ref : false,

    init : function(){

        this.footer();
        this.tabs();
        this.validation()
    },

    footer : function(){
        document.querySelectorAll(".after-submit").forEach(check => {
            check.addEventListener("click",function(){
                document.querySelectorAll(".after-submit:not([value='"+this.value+"']").forEach(other => {
                    other.checked = false;
                })
            })
        });
    },

    tabs : function(){
        var hash = document.location.hash;
        if (hash) {
            var activeTab = document.querySelector('.nav-tabs a[href="' + hash + '"]');
            if (activeTab){
               new bootstrap.Tab(activeTab).show();
            }
        };

        this.tabs_ref = document.querySelectorAll('.nav-tabs');
        if (this.tabs_ref.length){
            this.tabs_ref.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (event) {
                    // replaceState insted of pushSt (prevents tab navigation from going into the history)
                    history.replaceState(null,null, event.target.hash);
                });
            });
        }
        this.check_tab_errors();
    },

    check_tab_errors(){
        let errors = document.querySelectorAll('.has-error, .was-validated .form-control:invalid');
        if (this.tabs_ref.length && errors){
            let first_tab = false;
            errors.forEach(error => {
                let tabId = '#'+error.closest('.tab-pane').getAttribute('id');
                document.querySelector('li a[href="'+tabId+'"] i').classList.remove('hide');
                if (!first_tab){
                    first_tab = tabId;
                }
            });
            if (first_tab){
                let errorTab = document.querySelector('.nav-tabs a[href="' + first_tab + '"]');
                new bootstrap.Tab(errorTab).show();
            }
        }
    },

    disable_cascaded_forms : function(selector){
        document.querySelector(selector).addEventListener("submit",function (event) {
            let elems = event.target.querySelectorAll('div.cascade-group.hide input');
            if(elems){
                elems.forEach(field=>{
                    field.setAttribute('disabled', true);
                })
            }
        });
    },

    validation : function(){

        var forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated');
                admin.form.check_tab_errors();
            }, false)
        });
    }
}