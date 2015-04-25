<?php namespace r3oath\bantam\tests\units;

// require_once '../../mageekguy.atoum.phar';
include_once 'src/Prelim.php';

use \mageekguy\atoum;
use \r3oath\bantam;

class Prelim extends atoum\test {
    public function testAllowedKeys() {
        $arrEmpty = array();
        $arrNull = null;
        $arrSimple = array('a' => 0, 'b' => 1, 'c' => 2);
        $arrNot = 123;

        // Edge cases.
        $this->variable(bantam\Prelim::allowedKeys($arrEmpty))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys($arrNot))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys($arrSimple, $arrNot))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys($arrEmpty, array('a', 'b')))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys($arrNull))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys($arrNull, array('a', 'b')))->isEqualTo(null);
        $this->array(bantam\Prelim::allowedKeys($arrSimple, array('a', 'x')))
            ->integer['a']->isEqualTo(0)
            ->variable['x']->isEqualTo(null);
        $this->array(bantam\Prelim::allowedKeys($arrSimple, array('y', 'x')))
            ->variable['y']->isEqualTo(null)
            ->variable['x']->isEqualTo(null);
        $this->array(bantam\Prelim::allowedKeys($arrSimple))->hasKeys(array('a', 'b', 'c'));

        // Normal use.
        $this->array(bantam\Prelim::allowedKeys($arrSimple, array('a', 'b', 'c')))->hasKeys(array('a', 'b', 'c'));
        $this->array(bantam\Prelim::allowedKeys($arrSimple, array('a', 'b', 'c')))
            ->variable['a']->isEqualTo(0)
            ->variable['b']->isEqualTo(1)
            ->variable['c']->isEqualTo(2);
        $this->array(bantam\Prelim::allowedKeys($arrSimple, array('a', 'b')))->hasKeys(array('a', 'b'));
        $this->array(bantam\Prelim::allowedKeys($arrSimple, array('a', 'b')))
            ->variable['a']->isEqualTo(0)
            ->variable['b']->isEqualTo(1);
    }

    public function testHasLength() {
        $strEmpty = '';
        $strNull = null;
        $strShort = 'Tristan';
        $strLong = 'This is a pretty long string aye!';
        $strNot = 123;

        // Edge cases.
        $this->variable(bantam\Prelim::hasLength($strEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strNull))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strNot))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strShort, array('min' => 10, 'max' => 5)))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Prelim::hasLength($strShort))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasLength($strLong))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasLength($strShort, array('min' => 5)))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasLength($strShort, array('min' => 10)))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strShort, array('max' => 10)))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasLength($strShort, array('max' => 5)))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strShort, array('min' => 1, 'max' => 10)))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasLength($strShort, array('min' => 10, 'max' => 10)))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength($strShort, array('exact' => strlen($strShort))))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasLength($strShort, array('exact' => strlen($strShort)+1)))->isEqualTo(false);
    }

    public function testHasNulls() {
        $arrEmpty = array();
        $arrNull = null;
        $arrWithNull = array('a', 'b', null);
        $arrWithoutNull = array('a', 'b', 'c' => 0);
        $arrNot = 123;

        // Edge cases.
        $this->variable(bantam\Prelim::hasNulls($arrEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasNulls($arrNull))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasNulls($arrNot))->isEqualTo(true);

        // Normal use.
        $this->variable(bantam\Prelim::hasNulls($arrWithNull))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasNulls($arrWithoutNull))->isEqualTo(false);
    }

    public function testIsNumeric() {
        $numericStr = '5';
        $numericInt = 5;
        $numericFloat = 5.0;
        $numericNull = null;
        $numericNot = 'abc';

        // Edge cases.
        $this->variable(bantam\Prelim::isNumeric($numericNull))->isEqualTo(false);
        $this->variable(bantam\Prelim::isNumeric($numericNot))->isEqualTo(false);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('min' => 10, 'max' => 5)))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Prelim::isNumeric($numericStr))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericInt))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericFloat))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('min' => 1)))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('min' => 6)))->isEqualTo(false);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('max' => 10)))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('max' => 1)))->isEqualTo(false);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('min' => 1, 'max' => 10)))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('min' => 10, 'max' => 10)))->isEqualTo(false);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('exact' => 5)))->isEqualTo(true);
        $this->variable(bantam\Prelim::isNumeric($numericStr, array('exact' => 10)))->isEqualTo(false);
    }

    public function testStrNullOrEmpty() {
        $strEmpty = '';
        $strNull = null;
        $strSimple = 'Sphinx of black quartz, judge my vow.';
        $strNot = 123;

        // Normal use.
        $this->variable(bantam\Prelim::strNullOrEmpty($strEmpty))->isEqualTo(true);
        $this->variable(bantam\Prelim::strNullOrEmpty($strNull))->isEqualTo(true);
        $this->variable(bantam\Prelim::strNullOrEmpty($strNot))->isEqualTo(true);
        $this->variable(bantam\Prelim::strNullOrEmpty($strSimple))->isEqualTo(false);
    }

    public function testStartsWith() {
        $strEmpty = '';
        $strNull = null;
        $strSimple = 'Sphinx of black quartz, judge my vow.';
        $strNot = 123;

        // Edge cases.
        $this->variable(bantam\Prelim::startsWith($strEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strNull))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, $strNull))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, $strEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, $strNot))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, $strSimple.'extra'))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strNot))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Prelim::startsWith($strSimple, 'Sphinx'))->isEqualTo(true);
        $this->variable(bantam\Prelim::startsWith($strSimple, 'S'))->isEqualTo(true);
        $this->variable(bantam\Prelim::startsWith($strSimple, 'of'))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, ''))->isEqualTo(false);
    }

    public function testEndsWith() {
        $strEmpty = '';
        $strNull = null;
        $strSimple = 'Sphinx of black quartz, judge my vow.';
        $strNot = 123;

        // Edge cases.
        $this->variable(bantam\Prelim::endsWith($strEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strNull))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, $strNull))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, $strEmpty))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, $strNot))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, $strSimple.'extra'))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strNot))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Prelim::endsWith($strSimple, 'vow.'))->isEqualTo(true);
        $this->variable(bantam\Prelim::endsWith($strSimple, '.'))->isEqualTo(true);
        $this->variable(bantam\Prelim::endsWith($strSimple, 'of'))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, ''))->isEqualTo(false);
    }

    public function testClamp() {
        $value = 5;
        $valueNot = 'xyz';

        // Edge cases.
        $this->variable(bantam\Prelim::clamp($value))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, null, null))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, $valueNot, $valueNot))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp(null))->isEqualTo(null);
        $this->variable(bantam\Prelim::clamp($valueNot))->isEqualTo(null);

        // Normal use.
        $this->variable(bantam\Prelim::clamp($value, 0, $value))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, 0, $value-2))->isEqualTo($value-2);
        $this->variable(bantam\Prelim::clamp($value, $value, $value+10))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, $value+1, $value+10))->isEqualTo($value+1);
        $this->variable(bantam\Prelim::clamp($value, 0, 0))->isEqualTo(0);
        $this->variable(bantam\Prelim::clamp($value, 25, 25))->isEqualTo(25);
    }
}
