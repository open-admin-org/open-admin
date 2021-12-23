/*-------------------------------------------------*/
/* grid - inline-edit */
/*-------------------------------------------------*/

    admin.grid.inline_edit = {

        popovers : [],
        popover : false,
        trigger : false,
        functions : {},

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

        init_popover : function(triggerId,target){

            var el = document.getElementById(triggerId);

            var popover = new bootstrap.Popover(el, {
                html: true,
                container: 'body',
                trigger: 'manual',
                placement: 'top',
                content : function () {

                    var content = document.querySelector("template#"+target).cloneNode(true);

                    if(typeof(admin.grid.inline_edit.functions[triggerId]) != 'undefined'){
                        if(typeof(admin.grid.inline_edit.functions[triggerId].content) === "function"){
                            admin.grid.inline_edit.functions[triggerId].content(el,content.content);
                        }
                    }
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
            el.addEventListener('shown.bs.popover', function (event) {
                let popover = bootstrap.Popover.getInstance(this);
                let content = popover.tip.querySelector(".ie-container");
                admin.grid.inline_edit.trigger = this;
                triggerId = this.id;
                if(typeof(admin.grid.inline_edit.functions[triggerId]) != 'undefined'){
                    if(typeof(admin.grid.inline_edit.functions[triggerId].shown) === "function"){
                        admin.grid.inline_edit.functions[triggerId].shown(el,popover.tip);
                    }
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
            let trigger = this.trigger;
            let valueObject = this.retrieveValues(trigger, content);
            let original = trigger.dataset.original;

            if (valueObject.val == original) {
                console.log("nah its the same");
                popover.hide();
                return;
            }

            let resource = trigger.dataset.resource;
            let key = trigger.dataset.key;
            let url = resource+"/" + key;
            let obj = {
                method : 'post',
                data : {
                    _method: 'PUT',
                    _edit_inline: true,
                    'after-save': 'exit'
                }
            }
            obj.data[trigger.dataset.name] = valueObject.val;

            admin.ajax.request(url,obj,function(result){
                if (result.status){
                    trigger.dataset.original = valueObject.val;
                    trigger.querySelector(".ie-display").innerHTML = valueObject.label;
                    admin.toastr.success(result.data);
                    popover.hide();
                }else{
                    admin.toastr.warning(result.data);
                    /* // old  jquery code
                    var errors = xhr.responseJSON.errors;
                    for (var key in errors) {
                        $popover.find('.error').append('<div><i class="icon-times-circle-o"></i> '+errors[key]+'</div>')
                    }
                    */
                }
            });
        },

        retrieveValues : function(trigger,content){

            let val = false;
            let triggerId = trigger.id;
            if(typeof(admin.grid.inline_edit.functions[triggerId]) != 'undefined'){
                if(typeof(admin.grid.inline_edit.functions[triggerId].returnValue) === "function"){
                    val = admin.grid.inline_edit.functions[triggerId].returnValue(trigger,content);
                }
            }
            console.log(val);
            if (!val){
                val = content.querySelector('.ie-input').value;
            }
            if (typeof(val) === "string"){
                val = {'val':val,'label':val};
            }

            /*
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
            */
            return val;
        }
    }