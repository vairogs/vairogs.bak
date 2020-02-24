# Twig Sort Functions

[![Latest Stable Version](https://poser.pugx.org/ravenflux/sort-functions/v/stable)](https://packagist.org/packages/ravenflux/sort-functions) 
[![Latest Unstable Version](https://poser.pugx.org/ravenflux/sort-functions/v/unstable)](https://packagist.org/packages/ravenflux/sort-functions) 
[![License](https://poser.pugx.org/ravenflux/sort-functions/license)](https://packagist.org/packages/ravenflux/sort-functions) 
[![Total Downloads](https://poser.pugx.org/ravenflux/sort-functions/downloads)](https://packagist.org/packages/ravenflux/sort-functions)

<p align="center">
  <img src="https://github.com/ravenflux/ravenflux/raw/master/ravenflux.jpg">
</p>

Twig extension that implements sorting functions in Twig template.

Installation
------------
The recommended way to install is via Composer:
```shell
composer require ravenflux/sort-functions
```
For Symfony usage, import ravenflux/sort-function services.yaml file:
```yaml
# config/services.yaml
imports:
    - { resource: '../vendor/ravenflux/sort-functions/src/Resources/config/services.yaml' }
```
Usage
------------
```twig
{% for user in users|raven_usort('name') %}
    {{ user.name }}
{% endfor %}
```
```twig
{% for user in users|raven_usort('name', 'DESC') %}
    {{ user.name }}
{% endfor %}
```
