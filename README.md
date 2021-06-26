<p align="center">
<a href="https://open-admin.org/">
<img src="https://open-admin.org/images/logo002.png" alt="open-admin">
</a>

<p align="center"><code>open-admin</code> is administrative interface builder for laravel which can help you build CRUD backends just with few lines of code.</p>

<p align="center">
<a href="https://open-admin.org/docs">Documentation</a> |
<a href="https://demo.open-admin.org">Demo</a> |
<a href="#extensions">Extensions</a>
</p>

<p align="center">
    <a href="https://travis-ci.org/z-song/open-admin">
        <img src="https://travis-ci.org/z-song/open-admin.svg?branch=master" alt="Build Status">
    </a>
    <a href="https://styleci.io/repos/48796179">
        <img src="https://styleci.io/repos/48796179/shield" alt="StyleCI">
    </a>
    <a href="https://packagist.org/packages/open-admin/open-admin">
        <img src="https://img.shields.io/packagist/l/open-admin/open-admin.svg?maxAge=2592000&&style=flat-square" alt="Packagist">
    </a>
    <a href="https://packagist.org/packages/open-admin/open-admin">
        <img src="https://img.shields.io/packagist/dt/open-admin/open-admin.svg?style=flat-square" alt="Total Downloads">
    </a>
    <a href="https://github.com/wishbone-productions/open-admin">
        <img src="https://img.shields.io/badge/Awesome-Laravel-brightgreen.svg?style=flat-square" alt="Awesome Laravel">
    </a>
<!--
    <a href="#backers" alt="sponsors on Open Collective">
        <img src="https://opencollective.com/open-admin/backers/badge.svg?style=flat-square" />
    </a>
    <a href="https://www.paypal.me/wishbone-prductions" alt="Paypal donate">
        <img src="https://img.shields.io/badge/Donate-Paypal-green.svg?style=flat-square" />
    </a>-->
</div>

<p align="center">
    Forked from <a href="https://github.com/zsong/laravel-admin">Laravel-admin</a> Much thanks to Z-song for all the effort & great setup!
</p>




Requirements
------------
 - PHP >= 7.0.0
 - Laravel >= 7.0.0
 - Fileinfo PHP Extension

Installation
------------

> This package requires PHP 7+ and Laravel 7.0

First, install laravel 7.0, and make sure that the database connection settings are correct.

```
composer require open-admin/open-admin
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="OpenAdmin\Admin\AdminServiceProvider"
```
After run command you can find config file in `config/admin.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install.
```
php artisan admin:install
```

Open `http://localhost/admin/` in browser,use username `admin` and password `admin` to login.

Configurations
------------
The file `config/admin.php` contains an array of configurations, you can find the default configurations in there.

## Extensions

| Extension                                        | Description                              | open-admin                              |
| ------------------------------------------------ | ---------------------------------------- |---------------------------------------- |
| [helpers](https://github.com/open-admin-extensions/helpers)             | Several tools to help you in development | ~1.5 |
| [media-manager](https://github.com/open-admin-extensions/media-manager) | Provides a web interface to manage local files          | ~1.5 |
| [api-tester](https://github.com/open-admin-extensions/api-tester) | Help you to test the local laravel APIs          |~1.5 |
| [scheduling](https://github.com/open-admin-extensions/scheduling) | Scheduling task manager for open-admin          |~1.5 |
| [redis-manager](https://github.com/open-admin-extensions/redis-manager) | Redis manager for open-admin          |~1.5 |
| [backup](https://github.com/open-admin-extensions/backup) | An admin interface for managing backups          |~1.5 |
| [log-viewer](https://github.com/open-admin-extensions/log-viewer) | Log viewer for laravel           |~1.5 |
| [config](https://github.com/open-admin-extensions/config) | Config manager for open-admin          |~1.5 |
| [reporter](https://github.com/open-admin-extensions/reporter) | Provides a developer-friendly web interface to view the exception          |~1.5 |
| [wangEditor](https://github.com/open-admin-extensions/wangEditor) | A rich text editor based on [wangeditor](http://www.wangeditor.com/)         |~1.6 |
| [summernote](https://github.com/open-admin-extensions/summernote) | A rich text editor based on [summernote](https://summernote.org/)          |~1.6 |
| [china-distpicker](https://github.com/open-admin-extensions/china-distpicker) | 一个基于[distpicker](https://github.com/fengyuanchen/distpicker)的中国省市区选择器          |~1.6 |
| [simplemde](https://github.com/open-admin-extensions/simplemde) | A markdown editor based on [simplemde](https://github.com/sparksuite/simplemde-markdown-editor)          |~1.6 |
| [phpinfo](https://github.com/open-admin-extensions/phpinfo) | Integrate the `phpinfo` page into open-admin          |~1.6 |
| [php-editor](https://github.com/open-admin-extensions/php-editor) <br/> [python-editor](https://github.com/open-admin-extensions/python-editor) <br/> [js-editor](https://github.com/open-admin-extensions/js-editor)<br/> [css-editor](https://github.com/open-admin-extensions/css-editor)<br/> [clike-editor](https://github.com/open-admin-extensions/clike-editor)| Several programing language editor extensions based on code-mirror          |~1.6 |
| [star-rating](https://github.com/open-admin-extensions/star-rating) | Star Rating extension for open-admin          |~1.6 |
| [json-editor](https://github.com/open-admin-extensions/json-editor) | JSON Editor for Open-admin          |~1.6 |
| [grid-lightbox](https://github.com/open-admin-extensions/grid-lightbox) | Turn your grid into a lightbox & gallery          |~1.6 |
| [daterangepicker](https://github.com/open-admin-extensions/daterangepicker) | Integrates daterangepicker into open-admin          |~1.6 |
| [material-ui](https://github.com/open-admin-extensions/material-ui) | Material-UI extension for open-admin          |~1.6 |
| [sparkline](https://github.com/open-admin-extensions/sparkline) | Integrates jQuery sparkline into open-admin          |~1.6 |
| [chartjs](https://github.com/open-admin-extensions/chartjs) | Use Chartjs in open-admin          |~1.6 |
| [echarts](https://github.com/open-admin-extensions/echarts) | Use Echarts in open-admin          |~1.6 |
| [simditor](https://github.com/open-admin-extensions/simditor) | Integrates simditor full-rich editor into open-admin          |~1.6 |
| [cropper](https://github.com/open-admin-extensions/cropper) | A simple jQuery image cropping plugin.          |~1.6 |
| [composer-viewer](https://github.com/open-admin-extensions/composer-viewer) | A web interface of composer packages in laravel.          |~1.6 |
| [data-table](https://github.com/open-admin-extensions/data-table) | Advanced table widget for open-admin |~1.6 |
| [watermark](https://github.com/open-admin-extensions/watermark) | Text watermark for open-admin |~1.6 |
| [google-authenticator](https://github.com/ylic/open-admin-google-authenticator) | Google authenticator |~1.6 |


## Contributors
 This project exists thanks to all the people who contribute. [[Contribute](CONTRIBUTING.md)].
<a href="graphs/contributors"><img src="https://opencollective.com/open-admin/contributors.svg?width=890&button=false" /></a>
 ## Backers
 Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/open-admin#backer)]
 <a href="https://opencollective.com/open-admin#backers" target="_blank"><img src="https://opencollective.com/open-admin/backers.svg?width=890"></a>

Other
------------
`open-admin` based on following plugins or services:

+ [Laravel](https://laravel.com/)
+ [Font-awesome](http://fontawesome.io)
+ [Moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Sweetalert2](https://github.com/sweetalert2/sweetalert2)

License
------------
`open-admin` is licensed under [The MIT License (MIT)](LICENSE).
