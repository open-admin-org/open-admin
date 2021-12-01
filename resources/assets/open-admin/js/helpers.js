/*--------------------------------------------------*/
/* visual */
/*--------------------------------------------------*/

	var show = function (list) {
		if (!isNodeList(list)){
			var list = [list];
		}
		list.forEach(elm => {
			showElm(elm);
		});
	};
	function showElm(elm){
		if(elm.tagName == "TR"){
			elm.style.display = "table-row";
		}else{
			elm.style.display = 'block';
		}
	}

	var hide = function (list) {
		if (!isNodeList(list)){
			var list = [list];
			isNodeList(list)
		}
		list.forEach(elm => {
			elm.style.display = 'none';
		});
	};

	var toggle = function (list) {
		if (!isNodeList(list)){
			var list = [list];
		}
		list.forEach(elm => {
			let calculatedStyle = window.getComputedStyle(elm).display;
			if (calculatedStyle === 'block' || calculatedStyle === 'table-row') {
				elm.style.display = 'none';
				return;
			}
			showElm(elm);
		});
	};

/*--------------------------------------------------*/
/* lang function */
/*--------------------------------------------------*/

	var __ = function(trans_string){
		return admin_lang_arr[trans_string];
	}

	var trans = __;

/*--------------------------------------------------*/
/* function helpers */
/*--------------------------------------------------*/

	var merge_default = function(defaults,object, ...rest){
		return Object.assign({}, defaults, object, ...rest);
	}

/*--------------------------------------------------*/
/* html elements */
/*--------------------------------------------------*/

	function getOuterHeigt(el) {
		// Get the DOM Node if you pass in a string
		el = (typeof el === 'string') ? document.querySelector(el) : el;

		var styles = window.getComputedStyle(el);
		var margin = parseFloat(styles['marginTop']) +
					 parseFloat(styles['marginBottom']);

		return Math.ceil(el.offsetHeight + margin);
	}

	function isNodeList(nodes) {
		var stringRepr = Object.prototype.toString.call(nodes);

		return typeof nodes === 'object' &&
			/^\[object (HTMLCollection|NodeList|Object)\]$/.test(stringRepr) &&
			(typeof nodes.length === 'number') &&
			(nodes.length === 0 || (typeof nodes[0] === "object" && nodes[0].nodeType > 0));
	}
