class NumberInput {

    constructor(element) {

        this.input = element;
        this.input.ref = this;
        var plus = element.parentNode.querySelector(".plus");
        plus.ref = this;
        plus.addEventListener("click",function(){
            this.ref.plus();
        });
        var minus = element.parentNode.querySelector(".minus");
        minus.ref = this;
        minus.addEventListener("click",function(){
            this.ref.minus();
        });

        this.min = element.getAttribute('min');
        this.max = element.getAttribute('max');
        this.step = Number(element.getAttribute('step'));
        if (this.step == 0){
            this.step = 1;
        }

        element.addEventListener("change",function(){
            this.ref.setText(this.value);
        })
    }
    plus = function(){
        this.setText(Number(this.input.value) + this.step);
    }

    minus = function(){
        this.setText(Number(this.input.value) - this.step);
    }

    setText = function(n) {
        n = isNaN(n) ? 0 : n;
        if ((this.min && n < this.min)) {
            n = min;
        } else if (this.max && n > this.max) {
            n = this.max;
        }
        this.input.value = n;
    }
}