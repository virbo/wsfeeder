<p align="center">
    <h1 align="center">Web Service PDDIKTI versi 3</h1>
    <br>
</p>

DIRECTORY STRUCTURE
-------------------
      protected           contains core Yii
        assets/             contains assets definition
        commands/           contains console commands (controllers)
        config/             contains application configurations
        controllers/        contains Web controller classes
        mail/               contains view files for e-mails
        models/             contains model classes
        runtime/            contains files generated during runtime
        tests/              contains various tests for the basic application
        vendor/             contains dependent 3rd-party packages
        views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install via Git Clone

Clone this repository

~~~
git-clone https://github.com/virbo/wsfeeder.git
~~~

Then running comman composer update from into directory

~~~
composer update
~~~

Now you should be able to access the application through the following URL, assuming `wsfeeder` is the directory
directly under the Web root.

~~~
http://localhost/wsfeeder/web/
~~~

### Install from an Archive File

Extract the archive file downloaded from my repository [gtihub.com/virbo/wsfeeder](https://github.com/virbo/wsfeeder/archive/gh-pages.zip) to
a directory under the Web root.

Set cookie validation key in `config/web.php` file to some random secret string:

```php
'request' => [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => '<secret random string goes here>',
],
```

You can then access the application through the following URL:

~~~
http://localhost/wsfeeder/web/
~~~

CONFIGURATION
-------------

### Database

Edit the file `protected/config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```
