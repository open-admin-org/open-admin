/*-------------------------------------------------*/
/* grid - inline-edit */
/*-------------------------------------------------*/

    admin.grid.inline_edit = {

        popovers : [],
        popover : false,
        trigger : false,

        init : function(){
            
            document.addEventListener('click', function (event) {
                if (admin.grid.inline_edit.popover){
                    admin.grid.inline_edit.popover.hide();
                }
            });
        },

        hide_ohter_popovers : function(me){

            admin.grid.inline_edit.popovers.forEach(popover =>{
                if (me != popover._element){
                    popover.hide();
                }
            })
        },

        init_popover : function(trigger,target){
            

            var el = document.getElementById(trigger);                       
                        
            var popover = new bootstrap.Popover(el, {
                html: true,
                container: 'body',
                trigger: 'manual',
                placement: 'top',
                content : function () {                            
                    var content = document.querySelector("template#"+target).cloneNode(true);
                    return content.content;
                }
            })            
            
            el.addEventListener('show.bs.popover', function (event) {
                let popover = bootstrap.Popover.getInstance(this);
                admin.grid.inline_edit.trigger = this;
                admin.grid.inline_edit.popover = popover;
                admin.grid.inline_edit.hide_ohter_popovers(popover)
                
                if (typeof(popover.eventsAdded) == 'undefined'){
                    popover.tip.addEventListener("click",function(event){
                        
                        if (event.target.classList.contains("ie-cancel")){
                            popover.hide();
                        }
                        if (event.target.classList.contains("ie-submit")){
                            admin.grid.inline_edit.save();
                        }
                        event.stopPropagation();
                        return false;
                    })  
                    popover.eventsAdded = true;
                }              
            })            
            
            el.addEventListener('click', function (event) {
                bootstrap.Popover.getInstance(this).toggle();
                event.stopPropagation();
            })         

            admin.grid.inline_edit.popovers.push(popover);       

        },

        save : function(){
            
            let popover = admin.grid.inline_edit.popover;            
            let content = popover.tip.querySelector(".ie-container");           
            let trigger = admin.grid.inline_edit.trigger;
            
            let val = this.retrieveValues(trigger, content);                    
            let original = trigger.dataset.original;
            
            if (val == original) {
                console.log("nah its the same");
                popover.hide();
                return;
            }
            console.log("cool lets save");
            /*
        
            var data = {
                _token: LA.token,
                _method: 'PUT',
                _edit_inline: true,
            };
            data[$trigger.data('name')] = val;
        
            $.ajax({
                url: "{{ $resource }}/" + $trigger.data('key'),
                type: "POST",
                data: data,
                success: function (data) {
                    toastr.success(data.message);
        
                    {{ $slot }}
        
                    $trigger.data('value', val)
                        .data('original', val);
        
                    $('[data-bs-toggle="popover"]').popover('hide');
                },
                statusCode: {
                    422: function(xhr) {
                        $popover.find('.error').empty();
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            $popover.find('.error').append('<div><i class="icon-times-circle-o"></i> '+errors[key]+'</div>')
                        }
                    }
                }
            });
            */     
        },

        retrieveValues : function(trigger,content){

            let type = trigger.dataset.type || false;

            console.log(type);

            let val = false;      
            if (typeof(trigger.dataset.val) != 'undefined'){
                val = window.call(value_function,content);
            }else{
                if (type == "checkbox"){
                    val = [];
                    label = [];                                 
                    content.querySelectorAll('.ie-input:checked').forEach(el => {
                        val.push(el.value);
                        label.push(el.dataset.label);
                    });
                }else{
                    val = content.querySelector('.ie-input').value;
                }
            }
            return val;
        }
    }