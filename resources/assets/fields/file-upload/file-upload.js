class FileUpload {

    constructor(element) {

        var ref = this;

        this.input = element;
        this.fieldName = this.input.getAttribute("name").replace("[]","");
        this.multiple = element.multiple;
        this.hasCard = false;
        this.index = 0;

        let holder = document.createElement('div');
        holder.classList.add("file-upload-preview-div");
        holder.classList.add("form-field-helper");
        holder.classList.add("d-none");

        this.input.parentNode.insertBefore(holder, this.input);
        this.input.holder = holder;
        this.holder = holder;

        element.addEventListener("change",function(event){

            Array.from(event.target.files).forEach(file => {

                let img;
                if (!ref.hasCard || ref.multiple){
                    img = ref.createCard();
                }
                if (ref.hasCard && !ref.multiple){
                    img = ref.holder.querySelector("img");
                }

                img.src = URL.createObjectURL(file);
                img.onload = function() {
                    URL.revokeObjectURL(img.src) // free memory
                }
            });
        })

        this.initPreview(this);
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

    createCard = function(str){

        if (typeof(str) == 'undefined'){
            str = '';
        }

        let id = this.fieldName+'-'+this.index;
        this.index ++;

        let cardstr = `
            <div>
                <div class="card">
                    <div class="card-image">
                        <img id="img-`+id+`" src="`+str+`" data-bs-toggle="modal" data-bs-target="#model-`+id+`">
                    </div>
                    <div class="card-body">
                        <button class="btn btn-light icon-trash"></button>
                        <button class="btn btn-light icon-download"></button>
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

        let card = this.htmlToElement(cardstr);
        let img = card.querySelector("img");

        let removeBtn = card.querySelector(".icon-trash");
        removeBtn.ref = this;
        removeBtn.addEventListener("click",function(event){
            event.currentTarget.ref.removeImage(event.currentTarget);
            event.preventDefault();
        });

        let downloadBtn = card.querySelector(".icon-download");
        downloadBtn.downloadUrl = str;
        downloadBtn.ref = this;
        downloadBtn.addEventListener("click",function(event){
            event.currentTarget.ref.downloadImage(event.currentTarget);
            event.preventDefault();
        });

        this.holder.appendChild(card);
        this.holder.classList.remove("d-none");
        this.hasCard = true;

        return img;
    }

    removeImage = function(btn){

        let res = confirm("Delete image on save?");
        if (res){

            this.addDeleteField();

            let card = btn.closest(".card");
            let image = card.querySelector("img").getAttribute("src");
            this.deleteField.value += (image+",");
            card.remove();
            this.checkNumCards();
        }
    }

    checkNumCards = function(){
        if(!this.holder.querySelectorAll(".card").length){
            this.holder.classList.add("d-none");
        }
    }

    downloadImage = function(btn){
        window.open(btn.downloadUrl);
    }

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

    //var td = htmlToElement('<td>foo</td>'),
    //var div = htmlToElement('<div><span>nested</span> <span>stuff</span></div>');

    /**
     * @param {String} HTML representing any number of sibling elements
     * @return {NodeList}
     */
    htmlToElements = function(html) {
        var template = document.createElement('template');
        template.innerHTML = html;
        return template.content.childNodes;
    }

    //var rows = htmlToElements('<tr><td>foo</td></tr><tr><td>bar</td></tr>');


    initPreview = function(ref){
        if( typeof(ref.input.dataset.files) != 'undefined'){
            let files = (new String(ref.input.dataset.files)).split(',');
            files.forEach(file =>{
                ref.createCard(file);
            })
        }
    }

}