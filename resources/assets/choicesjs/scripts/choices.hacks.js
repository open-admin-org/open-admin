function choicesjs_allow_create(obj) {

    var addNew = document.createElement("div");
    var choiceList = obj.choiceList.element;
    addNew.addEventListener('click', function (e) {
        addItem();
    })

    obj.input.element.addEventListener('keyup', function (e) {

        if (addNewAllowed()) {
            obj.removeHighlightedItems();
            obj._currentState.choices.map(elm => elm.active = false)
            addNew.innerHTML = "Press ENTER to add: <b>" + obj._currentValue + "</b>";
            addNew.className = "choices__item choices__item--choice";
            addNew.style.display = "block";
            addNew.dataset.choiceSelectable = '';
            choiceList.append(addNew)

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                e.preventDefault()
                e.stopPropagation()
                return false;
            }

        } else {
            addNew.style.display = "none";
        }
        if (!choiceList.querySelectorAll(".is-highlighted").length) {
            console.log("ASDF")

            addNew.classList.add("is-highlighted", "choices__item--selectable");
        }
    })

    obj.input.element.addEventListener('keydown', function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);

        if (code == 13 && addNewAllowed()) {
            addItem();
            e.preventDefault()
            e.stopPropagation()
            return false;
        }
    })

    function addNewAllowed() {
        let choices_found = obj._currentState.choices.filter(elm => elm.label == obj._currentValue).length
        let items_found = obj._currentState.items.filter(elm => elm.label == obj._currentValue).length
        return (choices_found + items_found) == 0 && obj._currentValue != '';
    }

    function addItem() {

        obj.removeHighlightedItems();
        obj._currentState.choices.map(elm => elm.active = false)
        obj.input.element.value = ''

        obj._addChoice({
            value: obj._currentValue,
            label: obj._currentValue,
            isSelected: true,
            isDisabled: false
        });

        obj.input.element.dispatchEvent(new Event('keyup'));

    }
}