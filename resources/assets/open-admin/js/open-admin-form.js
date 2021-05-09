/*-------------------------------------------------*/
/* forms */
/*-------------------------------------------------*/

admin.form = {

    id : false,

    init : function(){

        this.footer();
        this.tabs();
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
        }

        document.querySelector('.nav-tabs').addEventListener('shown.bs.tab', function (event) {
            // replaceState insted of pushSt (prevents tab navigation from going into the history)
            history.replaceState(null,null, event.target.hash);
        });

        let errors = document.querySelectorAll('.has-error');
        if (errors){
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
            event.preventDefault();
            event.target.querySelectorAll('div.cascade-group.hide :input').forEach(field=>{
                field.setAttribute('disabled', true);
            })
        });
    }

}