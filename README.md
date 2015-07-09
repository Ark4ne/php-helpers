Helpers
=======

[![Latest Stable Version](http://img.shields.io/github/release/Ark4ne/php-helpers.svg)](https://packagist.org/packages/Ark4ne/php-helpers) [![Total Downloads](http://img.shields.io/packagist/dm/Ark4ne/php-helpers.svg)](https://packagist.org/packages/Ark4ne/php-helpers) [![Build Status](https://travis-ci.org/Ark4ne/php-helpers.svg?branch=master)](https://travis-ci.org/Ark4ne/php-helpers) [![Coverage Status](https://coveralls.io/repos/github/Ark4ne/php-helpers/badge.svg?branch=master)](https://coveralls.io/github/Ark4ne/php-helpers?branch=master)

### URL

- Ark4ne URL support.
```php
// Construct URL
$url = new \Ark4ne\Helpers\URL('www.github.com/Ark4ne/php-helpers');

$url->getProtocol() == 'http';
$url->setSecure(true);
$url->getProtocol() == 'https';

(string) $url == 'https://www.github.com/Ark4ne/php-helpers';
```
- shortcut function.
```php
url_($url, $params, $secure, $domain);
$url = url_('www.github.com/Ark4ne/php-helpers', 
    [], // $params
    true // $secure
) == 'https://www.github.com/Ark4ne/php-helpers';
```

### HTML
