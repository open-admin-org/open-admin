# Iconpicker for Bootstrap 5

### Usage

**1** - Via **cdn**

```js
<script src="https://unpkg.com/codethereal-iconpicker@1.1.3/dist/iconpicker.js"></script>
```

**2** - Via **npm**

```
npm i codethereal-iconpicker
```

```js
import Iconpicker from 'codethereal-iconpicker'
```

**3** - Or just download the git repo and get file under dist directory and import it

```js
<script src="/path/to/iconpicker.js"></script>
```


```js
new Iconpicker(document.querySelector(".iconpicker"));
new Iconpicker(document.querySelector(".iconpicker"), options);
document.querySelectorAll('.iconpicker').forEach(picker => new Iconpicker(picker))
```


**Options**
```js
const iconpicker = new Iconpicker(document.querySelector(".iconpicker"), {
    //icons: [], // default: all of the icons (icon set if you want to use only some of them)
    showSelectedIn: document.querySelector(".selected-icon"), // default: none (element to show selected icon)
    searchable: true, // default: true (use the input as a search box)
    selectedClass: "selected", // default: selected (selected icon class)
    containerClass: "my-picker", // default: (container class of iconpicker)
    hideOnSelect: true, // default: true (hides the dropdown on select)
    fade: true, // default: false (fade animation)
    defaultValue: 'bi-alarm', // default: (default value)
    valueFormat: val => `bi ${val}` // default: bi ${val} (format the value instead of prefix in previous versions)
});

iconpicker.set() // Set as empty
iconpicker.set('') // Set as empty
iconpicker.set('bi-alarm') // Set a value
```

**Use with font awesome**
```js
new Iconpicker(document.querySelector(".iconpicker"), {
  icons: ['fa-times', 'fa-check'],
  valueFormat: val => `fa ${val}`
```
