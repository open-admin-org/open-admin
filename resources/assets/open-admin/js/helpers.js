/*--------------------------------------------------*/
/* visual */
/*--------------------------------------------------*/

	var show = function (elem) {
		elem.style.display = 'block';
	};

	var hide = function (elem) {
		elem.style.display = 'none';
	};

	var toggle = function (elem) {
		if (window.getComputedStyle(elem).display === 'block') {
			hide(elem);
			return;
		}
		show(elem);
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
