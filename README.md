# Bantam.
[![Build Status](https://travis-ci.org/r3oath/bantam.svg?branch=master)](https://travis-ci.org/r3oath/bantam)
[![Coverage Status](https://coveralls.io/repos/r3oath/bantam/badge.svg)](https://coveralls.io/r/r3oath/bantam)
[![Documentation Status](https://readthedocs.org/projects/bantam/badge/?version=latest)](https://readthedocs.org/projects/bantam/?badge=latest)

A lightweight and simple PHP framework.

## Download
Download the latest PHAR build here: [PHAR Build](https://github.com/r3oath/bantam/blob/master/build/bantam.phar?raw=true)
Download the latest PHAR GZ build here: [PHAR Build](https://github.com/r3oath/bantam/blob/master/build/bantam.phar.gz?raw=true)

## Usage
It's dead easy to start using Bantam! At the top of your PHP file, simply add the following 2 lines:

```
require_once 'phar://path/to/bantam.phar.gz';
// OR require_once 'phar://path/to/bantam.phar';
use \r3oath\bantam;
```

Then wherever you want to use a particular class/feature of Bantam, simple call the class/feature like so:

```
bantam\Prelim::endsWith('This is awesome!', 'awesome!');
```

## Documentation.
Read the awesome [Documentation](http://bantam.readthedocs.org/ "Documentation") for Bantam provided by ReadTheDocs.

