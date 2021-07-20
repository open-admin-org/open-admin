/*-------------------------------------------------*/
/* resource  */
/*-------------------------------------------------*/

    admin.resource = {

        delete : function(event,delete_link){

            let navigate_url = false;
            let resource_url = delete_link.dataset.url;
            if (delete_link.dataset.list_url){
                navigate_url = delete_link.dataset.list_url;
            }
            this.delete_do(resource_url,navigate_url);
        },

        batch_delete : function (resource_url){
            this.delete_do(resource_url);
        },

        delete_do : function(resource_url,navigate_url){

            Swal.fire({
                title: __('delete_confirm'),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: __('confirm'),
                showLoaderOnConfirm: true,
                cancelButtonText:  __('cancel'),
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        let url = resource_url;
                        let data = {_method:'delete'};
                        admin.ajax.post(url,data,function(data){
                            resolve(data);
                            if (navigate_url){
                                admin.ajax.navigate(navigate_url);
                            }else{
                                admin.ajax.reload();
                            }
                        });
                    });
                }
            }).then(admin.resource.default_swal_response);
        },

        batch_edit : function (resource_url){
            let parts = resource_url.split(",");
            let edit_url = parts.shift()+"/edit";
            if (parts.length){
                edit_url += "?ids[]="+parts.join("&ids[]=");
            }
            admin.ajax.navigate(edit_url);
        },

        default_swal_response : function(result) {
            let data = result.value;
            if (typeof data === 'object') {
                if (data.status) {
                    Swal.fire(data.message, '', 'success');
                } else {
                    Swal.fire(data.message, '', 'error');
                }
            }
        },

        default_swal_response_and_reload : function(result) {
            admin.ajax.reload();
            admin.resource.default_swal_response(result);
        }
    }