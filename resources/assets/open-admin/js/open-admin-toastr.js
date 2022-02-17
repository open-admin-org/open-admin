/*-------------------------------------------------*/
/* forms */
/*-------------------------------------------------*/

admin.toastr = {

    defaults : {
        offset: {
            x: 0,
            y: -6
          },
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true, // Prevents dismissing of toast on hover
        className: "t-success",
    },
    className : "t-success",

    toast : function(message, options){

        if (typeof(options) == 'undefined'){
            options = {"text":message};
        }else{
            options.text = message;
        }
        options.className = this.className;
        let toastOptions = merge_default(this.defaults,options);
        Toastify(toastOptions).showToast();
    },

    success : function(text,obj){
        this.className = 't-success';
        this.toast(text,obj);
    },
    alert : function(text,obj){
        this.className = 't-alert';
        this.toast(text,obj);
    },
    warning : function(text,obj){
        this.className = 't-alert';
        this.toast(text,obj);
    },
    error : function(text,obj){
        this.className = 't-error';
        this.toast(text,obj);
    },
    info : function(text,obj){
        this.className = 't-info';
        this.toast(text,obj);
    }
}

admin.toast = function(text,obj){
    admin.toastr.toast(text,obj);
}