# Env In Twig

[![Latest Stable Version](https://poser.pugx.org/ravenflux/getenv/v/stable)](https://packagist.org/packages/ravenflux/getenv) 
[![Latest Unstable Version](https://poser.pugx.org/ravenflux/getenv/v/unstable)](https://packagist.org/packages/ravenflux/getenv) 
[![License](https://poser.pugx.org/ravenflux/getenv/license)](https://packagist.org/packages/ravenflux/getenv) 
[![Total Downloads](https://poser.pugx.org/ravenflux/getenv/downloads)](https://packagist.org/packages/ravenflux/getenv)

<p align="center">
  <img src="https://github.com/ravenflux/ravenflux/raw/master/ravenflux.jpg">
</p>

Twig extension that implements getenv in Twig template.

Installation
------------
The recommended way to install is via Composer:
```shell
composer require ravenflux/getenv
```
This package requires at least PHP 8.0.0

Usage
------------
```twig
{{ raven_getenv('APP_ENV') }}
```
