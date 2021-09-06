class FileUpload {

    constructor(element) {

        var ref = this;

        this.input = element;
        this.multiple = element.multiple;
        this.hasCard = false;

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

    createCard = function(str){

        if (typeof(str) == 'undefined'){
            str = '';
        }

        let cardstr = `
            <div class="card">
                <div class="card-image">
                    <img src="`+str+`">
                </div>
                <div class="card-body">
                    <button class="btn btn-light icon-trash"></button>
                    <button class="btn btn-light icon-download"></button>
                </div>
            </div>`;

        let card = this.htmlToElement(cardstr);        
        let img = card.querySelector("img");               

        this.holder.appendChild(card);
        this.holder.classList.remove("d-none");
        this.hasCard = true;

        return img;
        
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