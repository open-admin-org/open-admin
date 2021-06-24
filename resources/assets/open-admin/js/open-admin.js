/*-------------------------------------------------*/
/* main init */
/*-------------------------------------------------*/

    var admin = new Object();

    admin.ajax = new Object(); // ajax loading
    admin.pages = new Object(); // shared logic for pages
    admin.form = new Object(); // form in page
    admin.grid = new Object(); // grid / lister

    document.addEventListener("DOMContentLoaded", function() {
        admin.init();
    });

    admin.init = function(){
        admin.menu.init();
        admin.ajax.init();
        admin.pages.init();
    }

/*-------------------------------------------------*/
/* menu */
/*-------------------------------------------------*/

    admin.menu = {

        init: function() {

            let menuToggle = document.getElementById('menu-toggle');

            menuToggle.addEventListener('click', function (event) {
                if (!document.body.classList.contains("side-menu-closed")){
                    admin.menu.close();
                }

                if (window.innerWidth < 576){
                    document.body.classList.toggle("side-menu-open");
                    document.body.classList.remove("side-menu-closed");
                }else{
                    document.body.classList.toggle("side-menu-closed");
                    document.body.classList.remove("side-menu-open");
                }
            })

            window.addEventListener("resize", function(){
                if (window.innerWidth < 576){
                    document.body.classList.remove("side-menu-closed");
                }
            });

            function removeActiveClass(){
                let activeElements = document.querySelectorAll(".custom-menu > ul > li.active");
                for (let j = 0; j < activeElements.length; j++) {
                    activeElements[j].classList.remove("active");
                }
            }

            let elements = document.querySelectorAll(".custom-menu > ul > li > a");
            for (let i = 0; i < elements.length; i++) {
                elements[i].addEventListener('click', function(e){
                    admin.menu.close();
                    removeActiveClass();
                    this.parentNode.classList.add("active");
                }, false);
            }
            this.initSearch();
        },

        close: function(){
            let open_list = document.getElementById("menu").getElementsByClassName("show");
            for (let is_open of open_list) {
                is_open.previousElementSibling.click();
            };
        },

        initSearch: function (){

            let search_menu = document.querySelector('.sidebar-form .dropdown-menu');
            let search_field = document.querySelector(".sidebar-form .autocomplete");
            let selectedIndex = 0;

            searchMenu = function (event) {

                if(event.keyCode == 38 || event.keyCode == 40) {
                    up = (event.keyCode == 38);
                    menuItemSelect(up)
                    event.preventDefault();
                    return false;
                }else if(event.keyCode == 13) {
                    search_menu.querySelector("a.selected").click();
                }else{
                    selectedIndex = -1;
                }

                let text = this.value;

                if (text === '') {
                    hide(search_menu);
                    return;
                }

                var regex = new RegExp(text, 'i');
                var matched = false;

                search_menu.querySelectorAll('li').forEach(li => {
                    a = li.querySelector('a');
                    if (!regex.test(a.textContent)) {
                        hide(li);
                        li.classList.remove("shown");
                        a.classList.remove("selected");
                    } else {
                        show(li);
                        li.classList.add("shown");
                        matched = true;
                    }
                });

                if (matched) {
                    show(search_menu);
                }
            }

            function menuItemSelect(up){
                if (up){
                    selectedIndex --;
                }else{
                    selectedIndex ++;
                }
                let i = 0;
                search_menu.querySelectorAll("li.shown").forEach(li =>{
                    a = li.querySelector("a");
                    a.classList.remove("selected");
                    if (i == selectedIndex){
                        a.classList.add("selected");
                    }
                    i ++;
                });
            }

            var hideSearchMenu = function(){
                hide(search_menu);com
                search_field.value = "";
            }

            if (search_field){
                search_field.addEventListener("keyup",searchMenu);
                search_field.addEventListener("focus",searchMenu);
                document.addEventListener("click",hideSearchMenu);
            }

        },

        setActivePage : function(url){

            let menuItems = document.querySelectorAll("#menu a");
            menuItems.forEach(a =>{
                li = a.parentNode;
                li.classList.remove("active");
                a.blur();
                if (a.attributes.href.value == url){

                    parent = li.parentNode;

                    if (!parent.classList.contains("show")){
                        li.parentNode.classList.add("show");
                    }
                    if (parent.id == "menu"){
                        admin.menu.close();
                    }else{
                        li.parentNode.parentNode.classList.add("active");
                    }
                    li.classList.add("active");
                }
            })
        }
    };

/*-------------------------------------------------*/
/* page loading */
/*-------------------------------------------------*/

    admin.ajax = {

        defaults : {
            headers: {'X-PJAX': true,'X-PJAX-CONTAINER': "#pjax-container","X-Requested-With":"XMLHttpRequest"},
            method: 'get',
        },

        init : function (){

            // history back
            window.onpopstate = function(event) {
                preventPopState = true;
                admin.ajax.navigate(document.location,preventPopState);
            };

            // link in content and menu
            document.addEventListener('click', function(event) {

                if (event.target.matches('a[href], a[href] *')) {
                    a = event.target.closest('a');
                    let url = a.getAttribute("href");

                    if (url.charAt(0) != "#" && url != "" && !a.classList.contains('no-ajax') && a.getAttribute("target") != "_blank"){
                        preventPopState = false;
                        admin.ajax.navigate(url,preventPopState);
                        event.preventDefault();
                    }
                }
            }, false);

            // forms that should be submited with ajax
            document.addEventListener('submit', function(event) {
                if (event.target.getAttribute("pjax-container") != null){
                    let method = event.target.getAttribute("method");
                    let url = new String(event.target.getAttribute("action")).split("?")[0];
                    let obj = {};
                    //let data = Object.fromEntries(new FormData(event.target).entries()); this doesn't get arrays
                    var data = new FormData(event.target);

                    if (method == "get"){
                       let searchParams = new URLSearchParams(data);
                       let query_str =  searchParams.toString();
                       url += "?"+query_str;
                       admin.ajax.setUrl(url);
                    }else{
                        obj.data = data;
                        obj.method = method;
                    }
                    admin.ajax.load(url,obj);
                    event.preventDefault();
                }
            });

            NProgress.configure({ parent: '#main' });
        },

        // use navigate when you want history working
        // and the url to be changed
        navigate : function(url,preventPopState){
            if (window.innerWidth < 540){
                document.body.classList.remove("side-menu-closed")
                document.body.classList.remove("side-menu-open");
            }

            if (!preventPopState){
                this.setUrl(url)
            }

            admin.menu.setActivePage(url);
            this.load(url);
        },

        setUrl : function(url){
            history.pushState({}, url, url);
        },

        reload : function(){
            preventPopState = true;
            this.navigate(document.location.href);
        },

        // use load for loading without history state
        // and don't refresh the url
        load: function(url,obj){

            if (typeof(obj) == "undefined"){
                obj = {};
            }

            NProgress.start();

            obj.url = url;
            let axios_obj = merge_default(this.defaults,obj);

            axios(axios_obj)
            .then(function (response) {
                admin.ajax.done(response);
            })
            .catch(function (error) {
                admin.ajax.error(error);
            })
            .then(function () {
                NProgress.done();
                admin.pages.init();
            });
        },

        // posts and load this into the page
        loadPost : function(url,data){
            let obj = {
                method: "post",
                data : data
            }
            obj.data._token = LA.token;
            this.load(url,obj);
        },

        post : function (url,data,result_function){
            let obj = {
                method: "post",
                data : data,
                url : url,
            }
            obj.data._token = LA.token;

            NProgress.start();
            let axios_obj = merge_default(this.defaults,obj);

            axios(axios_obj)
            .then( result_function )
            .catch(function (error) {
                console.log(error);
            })
            .then(function () {
                NProgress.done();
            });
        },

        done : function(response){

            if (window.location != response.request.responseURL){
                this.setUrl(response.request.responseURL);
            }

            main = document.getElementById("main");
            let data = response.data;
            if (typeof(data) != 'string'){
                data = JSON.stringify(data);
            }
            main.innerHTML = data;
            main.querySelectorAll("script").forEach(script => {
                eval(script.innerText);
            })
        },

        error :function(error){

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
                console.log('Error', error.message);
            }
        }
    };

    admin.pages = {
        init : function(){
            admin.grid.init();
            admin.form.init();
        }
    }
