# Bantam.
[![Build Status](https://travis-ci.org/r3oath/bantam.svg?branch=master)](https://travis-ci.org/r3oath/bantam)
[![Coverage Status](https://coveralls.io/repos/r3oath/bantam/badge.svg)](https://coveralls.io/r/r3oath/bantam)
[![Documentation Status](https://readthedocs.org/projects/bantam/badge/?version=latest)](https://readthedocs.org/projects/bantam/?badge=latest)

A lightweight and simple PHP framework.

## The Philosophy

Bantam is a lightweight, single file, implementation agnostic solution that makes your
PHP projects easier to complete in less time. It'll strive to always be fully tested with
100% code coverage, coupled together with up to date well written documentation.

## Download

**Recommended**: PHAR GZ build: [PHAR GZ Build](https://github.com/r3oath/bantam/blob/master/build/bantam.phar.gz?raw=true)

Alternative: PHAR build: [PHAR Build](https://github.com/r3oath/bantam/blob/master/build/bantam.phar?raw=true)

## Usage
It's dead easy to start using Bantam! At the top of your PHP file, simply add the following 2 lines:

```php
require_once 'phar://path/to/bantam.phar.gz';
use \r3oath\bantam;
```

Then wherever you want to use a particular Bantam class:

```php
bantam\Prelim::endsWith('This is awesome!', 'awesome!');
```

## Documentation.
Read the awesome [Documentation](http://bantam.readthedocs.org/ "Documentation") for Bantam provided by ReadTheDocs.

