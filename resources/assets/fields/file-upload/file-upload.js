class FileUpload {

    constructor(element,options) {

        this.fileTypes = {
            'image'      : /\.(gif|png|jpeg|jpg|svg|webp|bpm|tiff)$/i,
            'html'       : /\.(htm|html)$/i,
            'word'       : /\.(doc|docx|rtf)$/i,
            'excel'      : /\.(xls|xlsx|csv)$/i,
            'powerpoint' : /\.(ppt|pptx|pps|potx)$/i,
            'text'       : /\.(txt|rtf|md|csv|nfo|ini|json|php|js|css|ts|sql)$/i,
            'video'      : /\.(og?|mp4|m4p|m4v|webm|mp?g|mov|3gp|avi|wmv|mkv)$/i,
            'audio'      : /\.(og?|mp3|mp?g|wav|flac)$/i,
            'pdf'        : /\.(pdf)$/i,
            'archive'    : /\.(zip|rar|7z|gz)$/i
        };

        this.fileTypesIcons = {
            'image'      : 'icon-file-image',
            'html'       : 'icon-file-code',
            'word'       : 'icon-file-word',
            'excel'      : 'icon-file-excel',
            'powerpoint' : 'icon-file-powerpoint',
            'text'       : 'icon-file-alt',
            'video'      : 'icon-file-video',
            'audio'      : 'icon-file-audio',
            'pdf'        : 'icon-file-pdf',
            'archive'    : 'icon-file-archive'
        };

        var ref = this;
        if (typeof(options) == 'undefined'){
            options = {}
        }
        var defaults = {
            "retainable":false,
            "sortable": true,
            "download" : true,
            "delete" : true,
            "confirm_delete" : true,
        }

        this.options = Object.assign({}, defaults, options);

        this.input = element;
        this.fieldName = this.input.getAttribute("name").replace("[]","");
        this.multiple = element.multiple;
        this.hasCard = false;
        this.index = 0;

        let holder = document.createElement('div');
        holder.classList.add("file-upload-preview-div");
        holder.classList.add("form-field-helper");
        holder.classList.add("d-none");

        let existing_files = document.createElement('div');
        existing_files.classList.add("files");
        //existing_files.classList.add("file-upload-preview-div");
        existing_files.classList.add("existing");
        holder.appendChild(existing_files)

        let new_files = document.createElement('div');
        //new_files.classList.add("file-upload-preview-div");
        new_files.classList.add("files");
        new_files.classList.add("new");
        holder.appendChild(new_files)

        // don't insert into input group, but before that
        if (this.input.parentNode.classList.contains("input-group")){
            this.input.parentNode.parentNode.insertBefore(holder, this.input.parentNode);
        }else{
            this.input.parentNode.insertBefore(holder, this.input);
        }

        this.input.holder = holder;
        this.holder = holder;
        this.new_files = new_files;
        this.existing_files = existing_files;

        element.addEventListener("change",function(event){

            Array.from(event.target.files).forEach(file => {

                let fileInfo = ref.getFileInfoFromName("new/"+file.name);
                fileInfo.uploading = true;

                let img;
                if (!ref.hasCard || ref.multiple){
                    img = ref.createCard(fileInfo);
                }
                if (fileInfo.type == "image"){
                    if (ref.hasCard && !ref.multiple){
                        img = ref.holder.querySelector(".preview");
                    }
                    //console.log(file);
                    img.src = URL.createObjectURL(file);
                    img.onload = function() {
                        URL.revokeObjectURL(img.src) // free memory
                    }
                }
            });
        })

        this.initPreview(this);
        if (this.options.sortable){
            this.enableSortable();
        }
        return this;
    }

    getFileInfoFromName = function(filepath){

        let uploading = false;
        let icon = "none";
        let type = "unknown";
        let name = this.getFileFromPath(filepath);
        for (const [key, value] of Object.entries(this.fileTypes)) {

            let regex = new RegExp(value);
            if (regex.test(name)){
                type = key;
                icon = this.fileTypesIcons[key];
            };
        }

        return {icon:icon,type:type,filepath:filepath,name:name,size:0,uploading:uploading};

    }

    getFileFromPath = function(path){
        return path.split('\\').pop().split('/').pop();
    }

    addDeleteField = function(){

        let deleteFieldName = this.fieldName+"_file_del_";

        if (!document.getElementById(deleteFieldName)){

            let deleteField = document.createElement("INPUT");
            deleteField.setAttribute("type", "hidden");
            deleteField.setAttribute("id", deleteFieldName);
            deleteField.setAttribute("name", deleteFieldName);
            deleteField.setAttribute("autocomplete", false);
            this.input.insertAdjacentElement('afterend', deleteField);
            this.deleteField = deleteField;
        }
    }

    addAddField = function(){

        let addFieldName = this.fieldName+"_file_add_";

        if (!document.getElementById(addFieldName)){

            let addField = document.createElement("INPUT");
            addField.setAttribute("type", "hidden");
            addField.setAttribute("id", addFieldName);
            addField.setAttribute("name", addFieldName);
            addField.setAttribute("autocomplete", false);
            this.input.insertAdjacentElement('afterend', addField);
            this.addField = addField;
        }
    }

    addOrderField = function(){

        let orderFieldName = this.fieldName+"_file_sort_";

        if (!document.getElementById(orderFieldName)){

            let orderField = document.createElement("INPUT");
            orderField.setAttribute("type", "hidden");
            orderField.setAttribute("id", orderFieldName);
            orderField.setAttribute("name", orderFieldName);
            orderField.setAttribute("autocomplete", false);
            this.input.insertAdjacentElement('afterend', orderField);
            this.orderField = orderField;
        }
    }

    createCard = function(fileInfo,str){

        if (typeof(str) == 'undefined'){
            str = '';
        }

        let id = this.fieldName+'-'+this.index;
        let cardstr = `
            <div>
                <div class="card">
                    <div class="card-image">` +
                        this.preview(fileInfo,id,str)
                        +`
                        <span class='label'>`+fileInfo.name+`</span>
                    </div>
                    <div class="card-body">` +
                        this.optionButtons(fileInfo)
                        +`
                    </div>
                </div>
                <div class="modal fade modal-dialog-centered" id="model-`+id+`" tabindex="-1" aria-hidden="true" style="display:none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">`+str+`</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="position:relative;">
                            <img id="model-img-`+id+`" src="`+str+`" style="width:100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

        let cardHolder = this.htmlToElement(cardstr);
        let card = cardHolder.querySelector(".card");
        let modal = cardHolder.querySelector(".modal");
        let preview = card.querySelector(".preview");
        card.dataset.name = fileInfo.name;
        card.dataset.filepath = fileInfo.filepath;

        if (this.options.delete){
            let removeBtn = card.querySelector(".icon-trash");
            removeBtn.ref = this;
            removeBtn.addEventListener("click",function(event){
                event.currentTarget.ref.removeFile(event.currentTarget);
                event.preventDefault();
            });
        }

        if (this.options.download){
            let downloadBtn = card.querySelector(".icon-download");
            downloadBtn.href = str;
            downloadBtn.target = "_blank";
            downloadBtn.download = str;
        }

        if (fileInfo.uploading){
            this.new_files.appendChild(card);
        }else{
            this.existing_files.appendChild(card);
        }
        this.holder.appendChild(modal);
        this.holder.classList.remove("d-none");
        this.hasCard = true;

        this.index ++;

        return preview;
    }

    preview = function(fileInfo,id,str){


        if (fileInfo.type == "image"){
            return `<img id="img-`+id+`" class="preview" src="`+str+`" data-bs-toggle="modal" data-bs-target="#model-`+id+`"></img>`;
        }else{
            return `<span id="img-`+id+`" class='preview icon `+fileInfo.icon+`'></span>`;
        }
    }

    optionButtons = function(fileInfo){
        var str = '';
        if (this.options.delete){
            str += `<a class="btn btn-light icon-trash"></a> `;
        }
        if (this.options.download){
            str += `<a class="btn btn-light icon-download"></a> `;
        }
        if (this.options.sortable && this.multiple){

            if (fileInfo.uploading){
                str += `<a class="btn btn-light icon-arrows-alt in-active" title="Can be sorted after save."></a>`;
            }else{
                str += `<a class="btn btn-light handle icon-arrows-alt"></a>`;
            }
        }
        return str;
    }

    removeFile = function(btn){

        var current_obj = this;
        var btn = btn;

        if (this.options.retainable || this.options.confirm_delete == false){
            this.removeFileDo(btn);
        }else{

            Swal.fire({
                title: __('delete_file_on_save'),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: __('confirm'),
                showLoaderOnConfirm: true,
                cancelButtonText:  __('cancel'),
            }).then(function (res){

                if (res.isConfirmed){
                    current_obj.removeFileDo(btn);
                }
            })
        }
    }

    removeFileDo = function(btn){
        this.addDeleteField();
        let card = btn.closest(".card");
        this.deleteField.value += (card.dataset.filepath+",");
        card.remove();
        this.checkNumCards();
    }

    checkNumCards = function(){
        if(!this.holder.querySelectorAll(".card").length){
            this.holder.classList.add("d-none");
            this.hasCard = false;
        }
    }

    initPreview = function(ref){
        if( typeof(ref.input.dataset.files) != 'undefined'){
            let files = (new String(ref.input.dataset.files)).split(',');
            files.forEach(file =>{
                let fileInfo = this.getFileInfoFromName(file);
                ref.createCard(fileInfo, this.options.storageUrl+file);
            })
        }
    }

    addFileFromUrl = function(url){

        this.addAddField();

        var file = url.replace(this.options.storageUrl,"");

        if (!this.multiple){
            this.existing_files.innerHTML = "";
            this.addField.value = file;
        }else{
            let sep = (this.addField.value != "") ? "," : "";
            this.addField.value += sep+file;
        }

        var url = new URL(url);
        let fileInfo = this.getFileInfoFromName(file);
        this.createCard(fileInfo, url);
    }

    enableSortable = function(){
        let ref = this;
        var sortable = new Sortable(this.existing_files, {
            animation:150,
            handle: ".handle",
            onUpdate: function(){
                ref.setOrder()
            }
        });
    }

    setOrder = function(evt){

        this.addOrderField();
        var arr = new Array();
        this.holder.querySelectorAll(".card").forEach(card => {
            arr.push(card.dataset.filepath);
        });
        this.orderField.value = arr.join(",");
    }


    //helper functions
    /**
     * @param {String} HTML representing a single element
     * @return {Element}
     */
    htmlToElement = function(html) {
        var template = document.createElement('template');
        html = html.trim(); // Never return a text node of whitespace as the result
        template.innerHTML = html;
        return template.content.firstChild;
    }

    /**
     * @param {String} HTML representing any number of sibling elements
     * @return {NodeList}
     */
    htmlToElements = function(html) {
        var template = document.createElement('template');
        template.innerHTML = html;
        return template.content.childNodes;
    }

    //examples
    //var td = htmlToElement('<td>foo</td>'),
    //var div = htmlToElement('<div><span>nested</span> <span>stuff</span></div>');
    //var rows = htmlToElements('<tr><td>foo</td></tr><tr><td>bar</td></tr>');
}