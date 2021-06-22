/*-------------------------------------------------*/
/* forms */
/*-------------------------------------------------*/

admin.tree = {

    elm : false,
    url : false,
    sortable : false,

    sortableDefaults : {
        group: 'nested',
        animation: 150,
        fallbackOnBody: false,
        swapThreshold: 0.65
    },

    init : function(elm, settings,url){

        this.url = url;
        this.elm = elm;
        this.sortable = false;

        let nestedSortables = document.querySelectorAll("#"+elm+" ol");
        let sortableSettings = merge_default(this.sortableDefaults,settings);

        for (var i = 0; i < nestedSortables.length; i++) {
            let setSortable = new Sortable(nestedSortables[i], sortableSettings);
            if (!this.sortable){
                this.sortable = setSortable;
            }
        }
    },

    delete : function(id){

        let resource_url = this.url + "/"+id;
        admin.resource.delete_do(resource_url);

    },

    save : function(){
        let order = this.toArrayNested();
        admin.ajax.loadPost(this.url,{_order:JSON.stringify(order)});
    },

    toArrayNested:function(){
        let top = document.querySelector("#"+this.elm+" > ol");
        return this.getChildren(top);
    },

    getChildren : function(elm){
        let arr = [];
        elm.querySelectorAll(":scope > li").forEach(li=>{
            let obj = {id:li.dataset.id};
            let ol = li.querySelector(":scope > ol");
            if (ol){
                obj.children = this.getChildren(ol,arr);
            }
            arr.push(obj);
        })
        return arr;
    },

    collapse : function(){
        document.querySelectorAll("#"+this.elm+" > ol ol").forEach(ol =>{
            hide(ol);
        })
    },

    expand : function(){
        document.querySelectorAll("#"+this.elm+" > ol ol").forEach(ol =>{
            show(ol);
        })
    }
}