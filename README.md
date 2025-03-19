# CLOUDSPACE AML

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cloudspace/aml?style=flat-square)](https://packagist.org/packages/cloudspace/aml)
[![Known Vulnerabilities](https://snyk.io/test/github/ikechukwukalu/cloudspaceaml/badge.svg?style=flat-square)](https://security.snyk.io/package/composer/ikechukwukalu%2Fcloudspaceaml)
[![Github Workflow Status](https://img.shields.io/github/actions/workflow/status/ikechukwukalu/cloudspaceaml/cloudspaceaml.yml?branch=main&style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/actions/workflows/cloudspaceaml.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/cloudspace/aml?style=flat-square)](https://packagist.org/packages/cloudspace/aml)
[![GitHub Repo stars](https://img.shields.io/github/stars/ikechukwukalu/cloudspaceaml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/stargazers)
[![GitHub issues](https://img.shields.io/github/issues/ikechukwukalu/cloudspaceaml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/issues)
[![GitHub forks](https://img.shields.io/github/forks/ikechukwukalu/cloudspaceaml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/forks)
[![Licence](https://img.shields.io/packagist/l/cloudspace/aml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/blob/main/LICENSE.md)

A simple Laravel package that provides a middleware which will require users to confirm routes utilizing their pin for authentication.

## REQUIREMENTS

- PHP 8.0+
- Laravel 10+

## STEPS TO INSTALL

``` shell
composer require cloudspace/aml
```

## USAGE

```php
use Cloudspace\AML\Facades\AML;

$response = AML::checkSanctions([
    'name' => 'John Doe',
    'birthDate' => '1990-01-01',
    'gender' => 'A1234567',
    'bvn' => '111111',
    'nin' => '111111'
]);

dd($response);

```

## LICENSE

The CloudSpaceAML package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
