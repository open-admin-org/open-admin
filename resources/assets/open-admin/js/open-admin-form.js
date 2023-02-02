/*-------------------------------------------------*/
/* forms */
/*-------------------------------------------------*/

admin.form = {
    id: false,
    tabs_ref: false,
    beforeSaveCallbacks: [],

    init: function () {
        this.addAjaxSubmit();
        this.footer();
        this.tabs();
        this.initValidation();
    },

    addSaveCallback: function (callback) {
        this.beforeSaveCallbacks.push(callback);
    },

    beforeSave: function () {
        for (i in this.beforeSaveCallbacks) {
            var callback = this.beforeSaveCallbacks[i];
            callback();
        }
    },

    addAjaxSubmit: function () {
        // forms that should be submitted with ajax
        Array.from(document.getElementsByTagName('form')).forEach((form) => {
            if (form.getAttribute('pjax-container') != null && !form.classList.contains('has-ajax-handler')) {
                form.addEventListener('submit', function (event) {
                    admin.form.submit(event.target);

                    event.preventDefault();
                    return false;
                });

                form.classList.add('has-ajax-handler');
            }
        });
    },

    submit: function (form, result_function) {
        let method = form.getAttribute('method').toLowerCase();
        let url = String(form.getAttribute('action')).split('?')[0];
        let obj = {};

        this.beforeSave();

        if (admin.form.validate(form)) {
            if (method === 'post' || method === 'put') {
                obj.data = new FormData(form);
                obj.method = method;
            } else {
                //let data = Object.fromEntries(new FormData(form).entries()); //this doesn't get arrays, not sure why used in the first place
                let data = new FormData(form);
                let searchParams = new URLSearchParams(data);
                let query_str = searchParams.toString();
                url += '?' + query_str;

                if (typeof result_function !== 'function') {
                    admin.ajax.setUrl(url);
                }
            }

            if (typeof result_function === 'function') {
                admin.ajax.request(url, obj, result_function);
            } else {
                admin.ajax.load(url, obj);
            }
        } else {
            console.log('Form still has errors');
        }
    },

    footer: function () {
        document.querySelectorAll('.after-submit').forEach((check) => {
            check.addEventListener('click', function () {
                document.querySelectorAll(".after-submit:not([value='" + this.value + "']").forEach((other) => {
                    other.checked = false;
                });
            });
        });
    },

    tabs: function () {
        var hash = document.location.hash;
        if (hash) {
            var activeTab = document.querySelector('.nav-tabs a[href="' + hash + '"]');
            if (activeTab) {
                new bootstrap.Tab(activeTab).show();
            }
        }

        this.tabs_ref = document.querySelectorAll('.nav-tabs');
        if (this.tabs_ref.length) {
            this.tabs_ref.forEach((tab) => {
                tab.addEventListener('shown.bs.tab', function (event) {
                    // replaceState insted of pushSt (prevents tab navigation from going into the history)
                    history.replaceState(null, null, event.target.hash);
                });
            });
        }
        this.check_tab_errors();
    },

    check_tab_errors() {
        let errors = document.querySelectorAll('.tab-pane .has-error, .was-validated .tab-pane .form-control:invalid');
        if (this.tabs_ref.length && errors) {
            let first_tab = false;
            errors.forEach((error) => {
                let tabId = '#' + error.closest('.tab-pane').getAttribute('id');
                document.querySelector('li a[href="' + tabId + '"] i').classList.remove('hide');
                if (!first_tab) {
                    first_tab = tabId;
                }
            });
            if (first_tab) {
                let errorTab = document.querySelector('.nav-tabs a[href="' + first_tab + '"]');
                new bootstrap.Tab(errorTab).show();
            }
        }
    },

    disable_cascaded_forms: function (selector) {
        document.querySelector(selector).addEventListener('submit', function (event) {
            let elems = event.target.querySelectorAll('div.cascade-group.d-none input');
            if (elems) {
                elems.forEach((field) => {
                    field.setAttribute('disabled', true);
                });
            }
        });
    },

    initValidation: function () {
        var forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function (form) {
            form.addEventListener(
                'submit',
                function (event) {
                    if (!admin.form.validate(form)) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    return false;
                },
                false
            );
        });
    },

    validate: function (form) {
        let res = true;

        if (form.classList.contains('needs-validation')) {
            res = form.checkValidity();
            form.classList.add('was-validated');
            admin.form.check_tab_errors();
        }
        return res;
    },
};
