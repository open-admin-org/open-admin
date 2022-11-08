/*-------------------------------------------------*/
/* main init */
/*-------------------------------------------------*/

let admin = {};

admin.ajax = {}; // ajax loading
admin.pages = {}; // shared logic for pages
admin.form = {}; // form in page
admin.grid = {}; // grid / lister
admin.action = {}; // actions

document.addEventListener('DOMContentLoaded', function () {
    admin.init();
});

admin.init = function () {
    admin.menu.init();
    admin.ajax.init();
    admin.pages.init();
};

/*-------------------------------------------------*/
/* menu */
/*-------------------------------------------------*/

admin.menu = {
    init: function () {
        let menuToggle = document.getElementById('menu-toggle');

        menuToggle.addEventListener('click', function () {
            if (!document.body.classList.contains('side-menu-closed')) {
                admin.menu.close();
            }

            if (window.innerWidth < 576) {
                document.body.classList.toggle('side-menu-open');
                document.body.classList.remove('side-menu-closed');
            } else {
                document.body.classList.toggle('side-menu-closed');
                document.body.classList.remove('side-menu-open');
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth < 576) {
                document.body.classList.remove('side-menu-closed');
            }
        });

        function removeActiveClass() {
            let activeElements = document.querySelectorAll('.custom-menu > ul > li.active');
            for (let j = 0; j < activeElements.length; j++) {
                activeElements[j].classList.remove('active');
            }
        }

        let elements = document.querySelectorAll('.custom-menu > ul > li > a');
        for (let i = 0; i < elements.length; i++) {
            elements[i].addEventListener(
                'click',
                function () {
                    admin.menu.close();
                    removeActiveClass();
                    this.parentNode.classList.add('active');
                },
                false
            );
        }
        this.initSearch();
    },

    close: function () {
        let open_list = document.getElementById('menu').getElementsByClassName('show');
        for (let is_open of open_list) {
            is_open.previousElementSibling.click();
        }
    },

    initSearch: function () {
        let search_menu = document.querySelector('.sidebar-form .dropdown-menu');
        let search_field = document.querySelector('.sidebar-form .autocomplete');
        let selectedIndex = -1;

        let searchMenu = function (event) {
            if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                let up = event.key === 'ArrowUp';
                menuItemSelect(up);
                event.preventDefault();
                return false;
            } else if (event.key === 'Enter') {
                search_menu.querySelector('a.selected').click();
            } else {
                selectedIndex = -1;
                let selectedItems = search_menu.querySelector('a.selected');
                if (selectedItems) {
                    selectedItems.classList.remove('selected');
                }
            }

            let text = this.value;

            if (text === '') {
                hide(search_menu);
                return;
            }

            let regex = new RegExp(text, 'i');
            let matched = false;

            search_menu.querySelectorAll('li').forEach((li) => {
                let a = li.querySelector('a');
                if (!regex.test(a.textContent)) {
                    hide(li);
                    li.classList.remove('shown');
                    a.classList.remove('selected');
                } else {
                    show(li);
                    li.classList.add('shown');
                    matched = true;
                }
            });

            if (matched) {
                show(search_menu);
            }
        };

        function menuItemSelect(up) {
            let shownItem = search_menu.querySelectorAll('li.shown');
            if (up) {
                selectedIndex--;
            } else {
                selectedIndex++;
            }
            if (selectedIndex > shownItem.length) {
                selectedIndex = 0;
            }
            if (selectedIndex < 0) {
                selectedIndex = shownItem.length;
            }
            let i = 0;

            shownItem.forEach((li) => {
                let a = li.querySelector('a');
                a.classList.remove('selected');
                if (i === selectedIndex) {
                    a.classList.add('selected');
                }
                i++;
            });
        }

        let hideSearchMenu = function () {
            hide(search_menu);
            search_field.value = '';
        };

        if (search_field) {
            search_field.addEventListener('keyup', searchMenu);
            search_field.addEventListener('focus', searchMenu);
            document.addEventListener('click', hideSearchMenu);
        }
    },

    setActivePage: function (url) {
        let menuItems = document.querySelectorAll('#menu a');
        menuItems.forEach((a) => {
            let li = a.parentNode;
            li.classList.remove('active');
            a.blur();
            if (a.attributes['href'].value === url) {
                let parent = li.parentNode;

                if (!parent.classList.contains('show')) {
                    li.parentNode.classList.add('show');
                }
                if (parent.id === 'menu') {
                    admin.menu.close();
                } else {
                    li.parentNode.parentNode.classList.add('active');
                }
                li.classList.add('active');
            }
        });
    },
};

/*-------------------------------------------------*/
/* page loading */
/*-------------------------------------------------*/

let preventPopState;

admin.ajax = {
    currenTarget: false,
    defaults: {
        headers: { 'X-PJAX': true, 'X-PJAX-CONTAINER': '#pjax-container', 'X-Requested-With': 'XMLHttpRequest', Accept: 'text/html, application/json, text/plain, */*' },
        method: 'get',
    },

    init: function () {
        // history back
        window.onpopstate = function (event) {
            preventPopState = true;
            admin.ajax.navigate(document.location, preventPopState);
        };

        // link in content and menu
        document.addEventListener(
            'click',
            function (event) {
                if (event.target.matches('a[href], a[href] *')) {
                    let a = event.target.closest('a');
                    let url = a.getAttribute('href');

                    if (url.charAt(0) !== '#' && url.substring(0, 11) !== 'javascript:' && url !== '' && !a.classList.contains('no-ajax') && a.getAttribute('target') !== '_blank') {
                        preventPopState = false;
                        admin.ajax.navigate(url, preventPopState);
                        event.preventDefault();
                    }
                }
            },
            false
        );

        // forms that should be submitted with ajax
        // now handled by admin.form.initAjax()
        // also needs to work for widgets

        NProgress.configure({ parent: '#main' });
    },

    // use navigate when you want history working
    // and the url to be changed
    navigate: function (url, preventPopState) {
        admin.collectGarbage();
        if (window.innerWidth < 540) {
            document.body.classList.remove('side-menu-closed');
            document.body.classList.remove('side-menu-open');
        }

        if (url != document.location.href) {
            if (!preventPopState) {
                this.setUrl(url);
            }
            admin.menu.setActivePage(url);
        }

        this.load(url);
    },

    setUrl: function (url) {
        if (url != document.location.href && !admin.ajax.currenTarget) {
            history.pushState({}, url, url);
        }
    },

    reload: function () {
        preventPopState = true;
        this.navigate(document.location.href);
    },

    // use load for loading without history state
    // and don't refresh the url
    load: function (url, obj) {
        this.request(url, obj);
    },

    request: function (url, obj, result_function) {
        if (typeof obj == 'undefined') {
            obj = {};
        }

        NProgress.start();

        obj.url = url;
        let axios_obj = merge_default(this.defaults, obj);

        axios(axios_obj)
            .then(function (response) {
                if (typeof result_function === 'function') {
                    result_function(response);
                } else {
                    admin.ajax.done(response);
                }
            })
            .catch(function (error) {
                admin.ajax.error(error);
            })
            .then(function () {
                NProgress.done();
                if (typeof result_function == 'undefined' && !admin.ajax.currenTarget) {
                    admin.pages.init();
                }
            });
    },

    // posts and load this into the page
    loadPost: function (url, data) {
        let obj = {
            method: 'post',
            data: data,
        };
        obj.data._token = LA.token;
        this.request(url, obj);
    },

    /*
            NOTICE: axios automatically converts data to json string if its an object.
            also NOTE: axios.delete doesn't support _POST data. (dont use formData in combination with delete, just grab the vars from the json payload from the request)
            to send application/x-www-form-urlencoded data use formData object:

            const formData = new FormData();
            formData.append('name', value);
         */
    post: function (url, data, result_function) {
        let obj = {
            method: 'post',
            data: data,
            url: url,
        };
        obj.data._token = LA.token;
        this.request(url, obj, result_function);
    },

    get: function (url, data, result_function) {
        let obj = {
            method: 'get',
            data: data,
            url: url,
        };
        obj.data._token = LA.token;
        this.request(url, obj, result_function);
    },

    done: function (response) {
        if (window.location !== response.request.responseURL) {
            this.setUrl(response.request.responseURL);
        }

        let main = false;
        if (admin.ajax.currenTarget) {
            main = admin.ajax.currenTarget;
        }
        if (!main) {
            main = document.getElementById('main');
        }

        let data = response.data;
        if (typeof data != 'string') {
            data = JSON.stringify(data);
        }
        main.innerHTML = data;

        main.querySelectorAll('script').forEach((script) => {
            var src = script.getAttribute('src');
            if (src) {
                script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = src;
                document.getElementById('app').appendChild(script);
            } else {
                eval(script.innerText);
            }
        });

        if (!admin.ajax.currenTarget) {
            admin.pages.setTitle();
        }
    },

    error: function (error) {
        if (error.response) {
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);

            admin.ajax.done(error.response);
        } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
        } else {
            // Something happened in setting up the request that triggered an Error
            console.log('An error has accurred:');
            console.log(error);
        }
    },
};

admin.pages = {
    init: function () {
        this.setTitle();
        admin.menu.setActivePage(window.location.href);
        admin.grid.init();
        admin.grid.inline_edit.init();
        admin.form.init();
        this.initBootstrap();
    },

    setTitle: function () {
        if (document.querySelector('main h1')) {
            let h1_title = document.querySelector('main h1').innerText;
            if (h1_title) {
                document.title = 'Admin | ' + h1_title;
            }
        }
    },

    initBootstrap: function () {
        // popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]:not(.ie)'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
};

admin.collectGarbage = function () {
    document.querySelectorAll('.flatpickr-calendar').forEach((cal) => {
        cal.remove();
    });
};
