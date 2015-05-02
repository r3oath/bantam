<?php namespace r3oath\bantam\tests\units;

include_once 'src/I18N.php';

use \mageekguy\atoum;
use \r3oath\bantam;

class I18N extends atoum\test {
    function testLoad() {
        // Edge cases.
        $this->variable(bantam\I18N::load(null))->isEqualTo(false);
        $this->variable(bantam\I18N::load(123))->isEqualTo(false);
        $this->variable(bantam\I18N::load(''))->isEqualTo(false);
        $this->variable(bantam\I18N::load('en', 123))->isEqualTo(false);
        $this->variable(bantam\I18N::load('en', false))->isEqualTo(false);
        $this->variable(bantam\I18N::load('en', array()))->isEqualTo(false);
        $this->variable(bantam\I18N::load('en', array('wrong')))
            ->isEqualTo(false);
        $this->variable(bantam\I18N::load('en', array('wrong' => array())))
            ->isEqualTo(false);
        $this->variable(bantam\I18N::load('en',
            array('wrong' => array('again'))))
            ->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\I18N::load('en', array(
            'bantam' => array(
                'welcome' => 'Welcome to Bantam!'
            )
        )))->isEqualTo(true);
        $this->variable(bantam\I18N::load(__DIR__.'/locales/en.php'))->isEqualTo(true);
    }

    function testE() {
        // Setup.
        bantam\I18N::load(__DIR__.'/locales/en.php');

        // These tests required the use of the output buffer as the Atoum testing
        // suite internally seems to use the class string which is a reserved
        // PHP keyword. Tests using newer versions of PHP failed here.

        // Edge cases.
        ob_start(); bantam\I18N::e(null);
        $this->variable(ob_get_clean())->isEqualTo('');
        ob_start(); bantam\I18N::e(123);
        $this->variable(ob_get_clean())->isEqualTo('');
        ob_start(); bantam\I18N::e(false);
        $this->variable(ob_get_clean())->isEqualTo('');
        ob_start(); bantam\I18N::e('bantam.hello', 'badvar');
        $this->variable(ob_get_clean())->isEqualTo('');

        // Normal use.
        ob_start(); bantam\I18N::e('notexist');
        $this->variable(ob_get_clean())->isEqualTo('');
        ob_start(); bantam\I18N::e('notexist.either');
        $this->variable(ob_get_clean())->isEqualTo('');
        ob_start(); bantam\I18N::e('notexist.either.123');
        $this->variable(ob_get_clean())->isEqualTo('');
        ob_start(); bantam\I18N::e('bantam.hello');
        $this->variable(ob_get_clean())->isEqualTo('Hello World!');
        ob_start(); bantam\I18N::e('bantam.hello', array('ignoredvar'));
        $this->variable(ob_get_clean())->isEqualTo('Hello World!');
        ob_start(); bantam\I18N::e('bantam.vars');
        $this->variable(ob_get_clean())->isEqualTo('The first {0} is better than the {1}');
        ob_start(); bantam\I18N::e('bantam.vars', array('cake'));
        $this->variable(ob_get_clean())->isEqualTo('The first cake is better than the {1}');
        ob_start(); bantam\I18N::e('bantam.vars', array('cake', 'snake'));
        $this->variable(ob_get_clean())->isEqualTo('The first cake is better than the snake');
    }

    function testR() {
        // Setup.
        bantam\I18N::load(__DIR__.'/locales/en.php');

        // Edge cases.
        $this->variable(bantam\I18N::r(null))->isEqualTo('');
        $this->variable(bantam\I18N::r(123))->isEqualTo('');
        $this->variable(bantam\I18N::r(false))->isEqualTo('');
        $this->variable(bantam\I18N::r('bantam.hello', 'badvar'))
            ->isEqualTo('');

        // Normal use.
        $this->variable(bantam\I18N::r('notexist'))->isEqualTo('');
        $this->variable(bantam\I18N::r('notexist.either'))->isEqualTo('');
        $this->variable(bantam\I18N::r('notexist.either.123'))->isEqualTo('');
        $this->variable(bantam\I18N::r('bantam.hello'))->isEqualTo('Hello World!');
        $this->variable(bantam\I18N::r('bantam.hello', array('ignoredvar')))
            ->isEqualTo('Hello World!');
        $this->variable(bantam\I18N::r('bantam.vars'))
            ->isEqualTo('The first {0} is better than the {1}');
        $this->variable(bantam\I18N::r('bantam.vars', array('cake')))
            ->isEqualTo('The first cake is better than the {1}');
        $this->variable(bantam\I18N::r('bantam.vars', array('cake', 'snake')))
            ->isEqualTo('The first cake is better than the snake');
    }

    function testSetLocale() {
        // Normal use.
        $this->variable(bantam\I18N::getLocale())->isEqualTo('en');
        bantam\I18N::setLocale('en');
        $this->variable(bantam\I18N::getLocale())->isEqualTo('en');
        bantam\I18N::setLocale('ge');
        $this->variable(bantam\I18N::getLocale())->isEqualTo('ge');
        bantam\I18N::setLocale('');
        $this->variable(bantam\I18N::getLocale())->isEqualTo('en');
        bantam\I18N::setLocale();
        $this->variable(bantam\I18N::getLocale())->isEqualTo('en');
        bantam\I18N::setLocale(123);
        $this->variable(bantam\I18N::getLocale())->isEqualTo('en');
    }

    function testMultipleLocales() {
        // Setup.
        bantam\I18N::load(__DIR__.'/locales/en.php');
        bantam\I18N::load(__DIR__.'/locales/ge.php');

        // Default locale should be en.
        $this->variable(bantam\I18N::r('bantam.hello'))
            ->isEqualTo('Hello World!');
        $this->variable(bantam\I18N::r('bantam.vars'))
            ->isEqualTo('The first {0} is better than the {1}');
        bantam\I18N::setLocale('ge');
        $this->variable(bantam\I18N::r('bantam.hello'))
            ->isEqualTo('Hallo Welt!');
        $this->variable(bantam\I18N::r('bantam.vars'))
            ->isEqualTo('Die erste {0} ist besser als die {1}');
    }
}
