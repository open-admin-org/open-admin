(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else {
		var a = factory();
		for(var i in a) (typeof exports === 'object' ? exports : root)[i] = a[i];
	}
})(window, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/dual-listbox.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/dual-listbox.js":
/*!*****************************!*\
  !*** ./src/dual-listbox.js ***!
  \*****************************/
/*! exports provided: default, DualListbox */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DualListbox", function() { return DualListbox; });
function _typeof(obj){"@babel/helpers - typeof";if(typeof Symbol==="function"&&typeof Symbol.iterator==="symbol"){_typeof=function _typeof(obj){return typeof obj;};}else{_typeof=function _typeof(obj){return obj&&typeof Symbol==="function"&&obj.constructor===Symbol&&obj!==Symbol.prototype?"symbol":typeof obj;};}return _typeof(obj);}function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function");}}function _defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||false;descriptor.configurable=true;if("value"in descriptor)descriptor.writable=true;Object.defineProperty(target,descriptor.key,descriptor);}}function _createClass(Constructor,protoProps,staticProps){if(protoProps)_defineProperties(Constructor.prototype,protoProps);if(staticProps)_defineProperties(Constructor,staticProps);return Constructor;}var MAIN_BLOCK='dual-listbox';var CONTAINER_ELEMENT='dual-listbox__container';var AVAILABLE_ELEMENT='dual-listbox__available';var SELECTED_ELEMENT='dual-listbox__selected';var TITLE_ELEMENT='dual-listbox__title';var ITEM_ELEMENT='dual-listbox__item';var BUTTONS_ELEMENT='dual-listbox__buttons';var BUTTON_ELEMENT='dual-listbox__button';var SEARCH_ELEMENT='dual-listbox__search';var SELECTED_MODIFIER='dual-listbox__item--selected';var DualListbox=function(){function DualListbox(selector){var options=arguments.length>1&&arguments[1]!==undefined?arguments[1]:{};_classCallCheck(this,DualListbox);this.setDefaults();this.selected=[];this.available=[];if(DualListbox.isDomElement(selector)){this.select=selector;}else{this.select=document.querySelector(selector);}this._initOptions(options);this._initReusableElements();this._splitOptions(this.select.options);if(options.options!==undefined){this._splitOptions(options.options);}this._buildDualListbox(this.select.parentNode);this._addActions();this.redraw();}_createClass(DualListbox,[{key:"setDefaults",value:function setDefaults(){this.addEvent=null;this.removeEvent=null;this.availableTitle='Available options';this.selectedTitle='Selected options';this.showAddButton=true;this.addButtonText='add';this.showRemoveButton=true;this.removeButtonText='remove';this.showAddAllButton=true;this.addAllButtonText='add all';this.showRemoveAllButton=true;this.removeAllButtonText='remove all';this.searchPlaceholder='Search';}},{key:"addEventListener",value:function addEventListener(eventName,callback){this.dualListbox.addEventListener(eventName,callback);}},{key:"addSelected",value:function addSelected(listItem){var _this=this;var index=this.available.indexOf(listItem);if(index>-1){this.available.splice(index,1);this.selected.push(listItem);this._selectOption(listItem.dataset.id);this.redraw();setTimeout(function(){var event=document.createEvent("HTMLEvents");event.initEvent("added",false,true);event.addedElement=listItem;_this.dualListbox.dispatchEvent(event);},0);}}},{key:"redraw",value:function redraw(){this.updateAvailableListbox();this.updateSelectedListbox();}},{key:"removeSelected",value:function removeSelected(listItem){var _this2=this;var index=this.selected.indexOf(listItem);if(index>-1){this.selected.splice(index,1);this.available.push(listItem);this._deselectOption(listItem.dataset.id);this.redraw();setTimeout(function(){var event=document.createEvent("HTMLEvents");event.initEvent("removed",false,true);event.removedElement=listItem;_this2.dualListbox.dispatchEvent(event);},0);}}},{key:"searchLists",value:function searchLists(searchString,dualListbox){var items=dualListbox.querySelectorAll(".".concat(ITEM_ELEMENT));var lowerCaseSearchString=searchString.toLowerCase();for(var i=0;i<items.length;i++){var item=items[i];if(item.textContent.toLowerCase().indexOf(lowerCaseSearchString)===-1){item.style.display='none';}else{item.style.display='list-item';}}}},{key:"updateAvailableListbox",value:function updateAvailableListbox(){this._updateListbox(this.availableList,this.available);}},{key:"updateSelectedListbox",value:function updateSelectedListbox(){this._updateListbox(this.selectedList,this.selected);}},{key:"_actionAllSelected",value:function _actionAllSelected(event){var _this3=this;event.preventDefault();var selected=this.available.filter(function(item){return item.style.display!=="none";});selected.forEach(function(item){return _this3.addSelected(item);});}},{key:"_updateListbox",value:function _updateListbox(list,elements){while(list.firstChild){list.removeChild(list.firstChild);}for(var i=0;i<elements.length;i++){var listItem=elements[i];list.appendChild(listItem);}}},{key:"_actionItemSelected",value:function _actionItemSelected(event){event.preventDefault();var selected=this.dualListbox.querySelector(".".concat(SELECTED_MODIFIER));if(selected){this.addSelected(selected);}}},{key:"_actionAllDeselected",value:function _actionAllDeselected(event){var _this4=this;event.preventDefault();var deselected=this.selected.filter(function(item){return item.style.display!=="none";});deselected.forEach(function(item){return _this4.removeSelected(item);});}},{key:"_actionItemDeselected",value:function _actionItemDeselected(event){event.preventDefault();var selected=this.dualListbox.querySelector(".".concat(SELECTED_MODIFIER));if(selected){this.removeSelected(selected);}}},{key:"_actionItemDoubleClick",value:function _actionItemDoubleClick(listItem){var event=arguments.length>1&&arguments[1]!==undefined?arguments[1]:null;if(event){event.preventDefault();event.stopPropagation();}if(this.selected.indexOf(listItem)>-1){this.removeSelected(listItem);}else{this.addSelected(listItem);}}},{key:"_actionItemClick",value:function _actionItemClick(listItem,dualListbox){var event=arguments.length>2&&arguments[2]!==undefined?arguments[2]:null;if(event){event.preventDefault();}var items=dualListbox.querySelectorAll(".".concat(ITEM_ELEMENT));for(var i=0;i<items.length;i++){var value=items[i];if(value!==listItem){value.classList.remove(SELECTED_MODIFIER);}}if(listItem.classList.contains(SELECTED_MODIFIER)){listItem.classList.remove(SELECTED_MODIFIER);}else{listItem.classList.add(SELECTED_MODIFIER);}}},{key:"_addActions",value:function _addActions(){this._addButtonActions();this._addSearchActions();}},{key:"_addButtonActions",value:function _addButtonActions(){var _this5=this;this.add_all_button.addEventListener('click',function(event){return _this5._actionAllSelected(event);});this.add_button.addEventListener('click',function(event){return _this5._actionItemSelected(event);});this.remove_button.addEventListener('click',function(event){return _this5._actionItemDeselected(event);});this.remove_all_button.addEventListener('click',function(event){return _this5._actionAllDeselected(event);});}},{key:"_addClickActions",value:function _addClickActions(listItem){var _this6=this;listItem.addEventListener('dblclick',function(event){return _this6._actionItemDoubleClick(listItem,event);});listItem.addEventListener('click',function(event){return _this6._actionItemClick(listItem,_this6.dualListbox,event);});return listItem;}},{key:"_addSearchActions",value:function _addSearchActions(){var _this7=this;this.search_left.addEventListener('change',function(event){return _this7.searchLists(event.target.value,_this7.availableList);});this.search_left.addEventListener('keyup',function(event){return _this7.searchLists(event.target.value,_this7.availableList);});this.search_right.addEventListener('change',function(event){return _this7.searchLists(event.target.value,_this7.selectedList);});this.search_right.addEventListener('keyup',function(event){return _this7.searchLists(event.target.value,_this7.selectedList);});}},{key:"_buildDualListbox",value:function _buildDualListbox(container){this.select.style.display='none';this.dualListBoxContainer.appendChild(this._createList(this.search_left,this.availableListTitle,this.availableList));this.dualListBoxContainer.appendChild(this.buttons);this.dualListBoxContainer.appendChild(this._createList(this.search_right,this.selectedListTitle,this.selectedList));this.dualListbox.appendChild(this.dualListBoxContainer);container.insertBefore(this.dualListbox,this.select);}},{key:"_createList",value:function _createList(search,header,list){var result=document.createElement('div');result.appendChild(search);result.appendChild(header);result.appendChild(list);return result;}},{key:"_createButtons",value:function _createButtons(){this.buttons=document.createElement('div');this.buttons.classList.add(BUTTONS_ELEMENT);this.add_all_button=document.createElement('button');this.add_all_button.innerHTML=this.addAllButtonText;this.add_button=document.createElement('button');this.add_button.innerHTML=this.addButtonText;this.remove_button=document.createElement('button');this.remove_button.innerHTML=this.removeButtonText;this.remove_all_button=document.createElement('button');this.remove_all_button.innerHTML=this.removeAllButtonText;var options={showAddAllButton:this.add_all_button,showAddButton:this.add_button,showRemoveButton:this.remove_button,showRemoveAllButton:this.remove_all_button};for(var optionName in options){if(optionName){var option=this[optionName];var button=options[optionName];button.setAttribute('type','button');button.classList.add(BUTTON_ELEMENT);if(option){this.buttons.appendChild(button);}}}}},{key:"_createListItem",value:function _createListItem(option){var listItem=document.createElement('li');listItem.classList.add(ITEM_ELEMENT);listItem.innerHTML=option.text;listItem.dataset.id=option.value;this._addClickActions(listItem);return listItem;}},{key:"_createSearchLeft",value:function _createSearchLeft(){this.search_left=document.createElement('input');this.search_left.classList.add(SEARCH_ELEMENT);this.search_left.placeholder=this.searchPlaceholder;}},{key:"_createSearchRight",value:function _createSearchRight(){this.search_right=document.createElement('input');this.search_right.classList.add(SEARCH_ELEMENT);this.search_right.placeholder=this.searchPlaceholder;}},{key:"_deselectOption",value:function _deselectOption(value){var options=this.select.options;for(var i=0;i<options.length;i++){var option=options[i];if(option.value===value){option.selected=false;option.removeAttribute('selected');}}if(this.removeEvent){this.removeEvent(value);}}},{key:"_initOptions",value:function _initOptions(options){for(var key in options){if(options.hasOwnProperty(key)){this[key]=options[key];}}}},{key:"_initReusableElements",value:function _initReusableElements(){this.dualListbox=document.createElement('div');this.dualListbox.classList.add(MAIN_BLOCK);if(this.select.id){this.dualListbox.classList.add(this.select.id);}this.dualListBoxContainer=document.createElement('div');this.dualListBoxContainer.classList.add(CONTAINER_ELEMENT);this.availableList=document.createElement('ul');this.availableList.classList.add(AVAILABLE_ELEMENT);this.selectedList=document.createElement('ul');this.selectedList.classList.add(SELECTED_ELEMENT);this.availableListTitle=document.createElement('div');this.availableListTitle.classList.add(TITLE_ELEMENT);this.availableListTitle.innerText=this.availableTitle;this.selectedListTitle=document.createElement('div');this.selectedListTitle.classList.add(TITLE_ELEMENT);this.selectedListTitle.innerText=this.selectedTitle;this._createButtons();this._createSearchLeft();this._createSearchRight();}},{key:"_selectOption",value:function _selectOption(value){var options=this.select.options;for(var i=0;i<options.length;i++){var option=options[i];if(option.value===value){option.selected=true;option.setAttribute('selected','');}}if(this.addEvent){this.addEvent(value);}}},{key:"_splitOptions",value:function _splitOptions(options){for(var i=0;i<options.length;i++){var option=options[i];if(DualListbox.isDomElement(option)){this._addOption({text:option.innerHTML,value:option.value,selected:option.attributes.selected});}else{this._addOption(option);}}}},{key:"_addOption",value:function _addOption(option){var listItem=this._createListItem(option);if(option.selected){this.selected.push(listItem);}else{this.available.push(listItem);}}}],[{key:"isDomElement",value:function isDomElement(o){return(typeof HTMLElement==="undefined"?"undefined":_typeof(HTMLElement))==="object"?o instanceof HTMLElement:o&&_typeof(o)==="object"&&o!==null&&o.nodeType===1&&typeof o.nodeName==="string";}}]);return DualListbox;}();/* harmony default export */ __webpack_exports__["default"] = (DualListbox);

/***/ })

/******/ });
});