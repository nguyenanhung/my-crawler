[![Latest Stable Version](http://poser.pugx.org/nguyenanhung/template-php-lib/v)](https://packagist.org/packages/nguyenanhung/template-php-lib) [![Total Downloads](http://poser.pugx.org/nguyenanhung/template-php-lib/downloads)](https://packagist.org/packages/nguyenanhung/template-php-lib) [![Latest Unstable Version](http://poser.pugx.org/nguyenanhung/template-php-lib/v/unstable)](https://packagist.org/packages/nguyenanhung/template-php-lib) [![License](http://poser.pugx.org/nguyenanhung/template-php-lib/license)](https://packagist.org/packages/nguyenanhung/template-php-lib) [![PHP Version Require](http://poser.pugx.org/nguyenanhung/template-php-lib/require/php)](https://packagist.org/packages/nguyenanhung/template-php-lib)

# Template start helper, library

Template for repository helper, library - Basic, Simple and Lightweight

## Use this Template

First, you can `Use this template` for new project: [Use this template](https://github.com/nguyenanhung/template-php-lib/generate)

Second, clone your project to your to path in your machine

Finally, your edit file `composer.json` in root folder of project

```json
{
    "type": "library",
    "name": "nguyenanhung/template-php-lib",
    "description": "Template for repository helper, library - Basic, Simple and Lightweight",
    "keywords": [
        "template",
        "helper",
        "library",
        "php"
    ],
    "homepage": "https://github.com/nguyenanhung/template-php-lib",
    "license": "MIT",
    "minimum-stability": "stable",
    "authors": [
        {
            "name": "Nguyen An Hung",
            "email": "dev@nguyenanhung.com",
            "homepage": "https://nguyenanhung.com",
            "role": "Developer"
        }
    ],
    "require": {
        "php": ">=5.6"
    },
    "autoload": {
        "psr-4": {
            "nguyenanhung\\Libraries\\REPLACE_FOR_YOUR\\": "src/"
        },
        "files": [
            "helpers/helpers.php"
        ]
    }
}

```

Replace name space `REPLACE_FOR_YOUR` to Library space, example: `JSON`. After change namespace, project namespace same `"nguyenanhung\\Libraries\\JSON\\": "src/"`

Finished, your can writing new awesome helper and library now time.

## Contact & Support

If any question & request, please contact following information

| Name        | Email                | Skype            | Facebook      |
|-------------|----------------------|------------------|---------------|
| Hung Nguyen | dev@nguyenanhung.com | nguyenanhung5891 | @nguyenanhung |

From Vietnam with Love <3
