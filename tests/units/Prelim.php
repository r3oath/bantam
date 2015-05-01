<?php namespace r3oath\bantam\tests\units;

include_once 'src/Prelim.php';

use \mageekguy\atoum;
use \r3oath\bantam;

class Prelim extends atoum\test {
    public function testAllowedKeys() {
        $arr = array('a' => 0, 'b' => 1, 'c' => 2);

        // Edge cases.
        $this->variable(bantam\Prelim::allowedKeys(array()))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys(123))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys($arr, 123))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys(array(), array('a', 'b')))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys(null))->isEqualTo(null);
        $this->variable(bantam\Prelim::allowedKeys(null, array('a', 'b')))->isEqualTo(null);
        $this->array(bantam\Prelim::allowedKeys($arr, array('a', 'x')))
            ->integer['a']->isEqualTo(0)
            ->variable['x']->isEqualTo(null);
        $this->array(bantam\Prelim::allowedKeys($arr, array('y', 'x')))
            ->variable['y']->isEqualTo(null)
            ->variable['x']->isEqualTo(null);
        $this->array(bantam\Prelim::allowedKeys($arr))->hasKeys(array('a', 'b', 'c'));

        // Normal use.
        $this->array(bantam\Prelim::allowedKeys($arr, array('a', 'b', 'c')))->hasKeys(array('a', 'b', 'c'));
        $this->array(bantam\Prelim::allowedKeys($arr, array('a', 'b', 'c')))
            ->variable['a']->isEqualTo(0)
            ->variable['b']->isEqualTo(1)
            ->variable['c']->isEqualTo(2);
        $this->array(bantam\Prelim::allowedKeys($arr, array('a', 'b')))->hasKeys(array('a', 'b'));
        $this->array(bantam\Prelim::allowedKeys($arr, array('a', 'b')))
            ->variable['a']->isEqualTo(0)
            ->variable['b']->isEqualTo(1);
    }

    public function testHasLength() {
        $strShort = 'Tristan';
        $strLong = 'This is a pretty long string aye!';

        // Edge cases.
        $this->variable(bantam\Prelim::hasLength(''))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength(''))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength(null))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasLength(123))->isEqualTo(false);
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
        $arrWithNull = array('a', 'b', null);
        $arrWithoutNull = array('a', 'b', 'c' => 0);

        // Edge cases.
        $this->variable(bantam\Prelim::hasNulls(array()))->isEqualTo(false);
        $this->variable(bantam\Prelim::hasNulls(null))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasNulls(123))->isEqualTo(true);

        // Normal use.
        $this->variable(bantam\Prelim::hasNulls($arrWithNull))->isEqualTo(true);
        $this->variable(bantam\Prelim::hasNulls($arrWithoutNull))->isEqualTo(false);
    }

    public function testIsNumeric() {
        $numericStr = '5';
        $numericInt = 5;
        $numericFloat = 5.0;

        // Edge cases.
        $this->variable(bantam\Prelim::isNumeric(null))->isEqualTo(false);
        $this->variable(bantam\Prelim::isNumeric('abc'))->isEqualTo(false);
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
        $strSimple = 'Sphinx of black quartz, judge my vow.';

        // Normal use.
        $this->variable(bantam\Prelim::strNullOrEmpty(''))->isEqualTo(true);
        $this->variable(bantam\Prelim::strNullOrEmpty(null))->isEqualTo(true);
        $this->variable(bantam\Prelim::strNullOrEmpty(123))->isEqualTo(true);
        $this->variable(bantam\Prelim::strNullOrEmpty($strSimple))->isEqualTo(false);
    }

    public function testStartsWith() {
        $strSimple = 'Sphinx of black quartz, judge my vow.';

        // Edge cases.
        $this->variable(bantam\Prelim::startsWith(''))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith(null))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, null))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, ''))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, 123))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, $strSimple.'extra'))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith(123))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Prelim::startsWith($strSimple, 'Sphinx'))->isEqualTo(true);
        $this->variable(bantam\Prelim::startsWith($strSimple, 'S'))->isEqualTo(true);
        $this->variable(bantam\Prelim::startsWith($strSimple, 'of'))->isEqualTo(false);
        $this->variable(bantam\Prelim::startsWith($strSimple, ''))->isEqualTo(false);
    }

    public function testEndsWith() {
        $strSimple = 'Sphinx of black quartz, judge my vow.';

        // Edge cases.
        $this->variable(bantam\Prelim::endsWith(''))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith(null))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, null))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, ''))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, 123))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, $strSimple.'extra'))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith(123))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Prelim::endsWith($strSimple, 'vow.'))->isEqualTo(true);
        $this->variable(bantam\Prelim::endsWith($strSimple, '.'))->isEqualTo(true);
        $this->variable(bantam\Prelim::endsWith($strSimple, 'of'))->isEqualTo(false);
        $this->variable(bantam\Prelim::endsWith($strSimple, ''))->isEqualTo(false);
    }

    public function testClamp() {
        $value = 5;

        // Edge cases.
        $this->variable(bantam\Prelim::clamp($value))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, null, null))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, 'xyz', 'xyz'))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp(null))->isEqualTo(null);
        $this->variable(bantam\Prelim::clamp('xyz'))->isEqualTo(null);

        // Normal use.
        $this->variable(bantam\Prelim::clamp($value, 0, $value))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, 0, $value-2))->isEqualTo($value-2);
        $this->variable(bantam\Prelim::clamp($value, $value, $value+10))->isEqualTo($value);
        $this->variable(bantam\Prelim::clamp($value, $value+1, $value+10))->isEqualTo($value+1);
        $this->variable(bantam\Prelim::clamp($value, 0, 0))->isEqualTo(0);
        $this->variable(bantam\Prelim::clamp($value, 25, 25))->isEqualTo(25);
        $this->variable(bantam\Prelim::clamp(2.3, 2, 5))->isEqualTo(2.3);
        $this->variable(bantam\Prelim::clamp(2.3, 3, 5))->isEqualTo(3);
    }
}
