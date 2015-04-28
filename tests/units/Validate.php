<?php namespace r3oath\bantam\tests\units;

include_once 'src/Validate.php';

use \mageekguy\atoum;
use \r3oath\bantam;

// The majority of these are wrappers for PHP's filter_var so tests are limited as
// we're relying on the PHP dev team to test their own code. Let's just make
// sure it works for the every-day cases.
class Validate extends atoum\test {
    public function testEmail() {
        // Invalid.
        $this->variable(bantam\Validate::email(''))->isEqualTo(false);
        $this->variable(bantam\Validate::email(123))->isEqualTo(false);
        $this->variable(bantam\Validate::email(null))->isEqualTo(false);
        $this->variable(bantam\Validate::email('aaa'))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::email('bob@smith.com'))->isEqualTo(true);
        $this->variable(bantam\Validate::email('bob+work@smith.com'))->isEqualTo(true);
        $this->variable(bantam\Validate::email('bob.smith@smith.com'))->isEqualTo(true);
        $this->variable(bantam\Validate::email('bob@smith.corp.com'))->isEqualTo(true);
    }

    public function testBool() {
        // These odd cases are considered bools in PHP's filter_var, we however
        // wont as we'd like to keep it more user-data realistic.
        $this->variable(bantam\Validate::bool(''))->isEqualTo(false);
        $this->variable(bantam\Validate::bool(null))->isEqualTo(false);

        // Invalid.
        $this->variable(bantam\Validate::bool('notbool'))->isEqualTo(false);
        $this->variable(bantam\Validate::bool(2))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::bool(true))->isEqualTo(true);
        $this->variable(bantam\Validate::bool(false))->isEqualTo(true);
        $this->variable(bantam\Validate::bool('true'))->isEqualTo(true);
        $this->variable(bantam\Validate::bool('false'))->isEqualTo(true);
        $this->variable(bantam\Validate::bool(0))->isEqualTo(true);
        $this->variable(bantam\Validate::bool(1))->isEqualTo(true);
        $this->variable(bantam\Validate::bool('0'))->isEqualTo(true);
        $this->variable(bantam\Validate::bool('1'))->isEqualTo(true);
        $this->variable(bantam\Validate::bool('yes'))->isEqualTo(true);
        $this->variable(bantam\Validate::bool('no'))->isEqualTo(true);
    }

    public function testIP() {
        // Invalid.
        $this->variable(bantam\Validate::ip('notip'))->isEqualTo(false);
        $this->variable(bantam\Validate::ip(null))->isEqualTo(false);
        $this->variable(bantam\Validate::ip(false))->isEqualTo(false);
        $this->variable(bantam\Validate::ip(array()))->isEqualTo(false);
        // Local addresses are not valid.
        $this->variable(bantam\Validate::ip('192.168.0.1'))->isEqualTo(false);
        $this->variable(bantam\Validate::ip('127.0.0.1'))->isEqualTo(false);
        $this->variable(bantam\Validate::ip('fde4:8dba:82e1::/64'))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::ip('108.108.108.108'))->isEqualTo(true);
        $this->variable(bantam\Validate::ip('8.8.8.8'))->isEqualTo(true);
        $this->variable(bantam\Validate::ip('2001:4860:4860::8888'))->isEqualTo(true);
    }

    public function testFloat() {
        // Invalid.
        $this->variable(bantam\Validate::float('noturl'))->isEqualTo(false);
        $this->variable(bantam\Validate::float(null))->isEqualTo(false);
        $this->variable(bantam\Validate::float(false))->isEqualTo(false);
        $this->variable(bantam\Validate::float(array()))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::float('1.5'))->isEqualTo(true);
        $this->variable(bantam\Validate::float(1.5))->isEqualTo(true);
        $this->variable(bantam\Validate::float(0))->isEqualTo(true);
        $this->variable(bantam\Validate::float('1,300.00'))->isEqualTo(true);
        $this->variable(bantam\Validate::float('1300.00'))->isEqualTo(true);
    }

    public function testInt() {
        // Invalid.
        $this->variable(bantam\Validate::int('notint'))->isEqualTo(false);
        $this->variable(bantam\Validate::int(null))->isEqualTo(false);
        $this->variable(bantam\Validate::int(false))->isEqualTo(false);
        $this->variable(bantam\Validate::int(1.5))->isEqualTo(false);
        $this->variable(bantam\Validate::int('1.5'))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::int(0))->isEqualTo(true);
        $this->variable(bantam\Validate::int('0'))->isEqualTo(true);
        $this->variable(bantam\Validate::int(200))->isEqualTo(true);
        $this->variable(bantam\Validate::int('200'))->isEqualTo(true);
        $this->variable(bantam\Validate::int('0x100'))->isEqualTo(true);
        $this->variable(bantam\Validate::int('0123'))->isEqualTo(true);
        $this->variable(bantam\Validate::int(0x100))->isEqualTo(true);
        $this->variable(bantam\Validate::int(0123))->isEqualTo(true);
    }

    public function testUrl() {
        // Invalid.
        $this->variable(bantam\Validate::url(2))->isEqualTo(false);
        $this->variable(bantam\Validate::url(false))->isEqualTo(false);
        $this->variable(bantam\Validate::url(null))->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://'))->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://github.com', array()))->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://github.com', array('')))->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://github.com', array('', null)))->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://github.com', array('http'), 123))->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://github.com', array('http'), '123'))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::url('github.com'))->isEqualTo(true);
        $this->variable(bantam\Validate::url('www.github.com'))->isEqualTo(true);
        $this->variable(bantam\Validate::url('http://www.github.com'))->isEqualTo(true);
        $this->variable(bantam\Validate::url('http://www.github.com.au'))->isEqualTo(true);
        $this->variable(bantam\Validate::url('github.com', array('http', 'ftp')))->isEqualTo(true);
        $this->variable(bantam\Validate::url('mailto://github.com', array('http', 'ftp')))->isEqualTo(false);

        // Valid URL structures but the DNS checks should failed, making them invalid.
        $this->variable(bantam\Validate::url('http://www.github.com.au.nz', array('http'), true))
            ->isEqualTo(false);
        $this->variable(bantam\Validate::url('http://www.github.com', array('http'), true))
            ->isEqualTo(true);
        // Let's hope no one purchases this domain!...
        $this->variable(bantam\Validate::url('ahhhhh.akjsdh093470194019aljhsldjhalsd.com', array('http'), true))
            ->isEqualTo(false);
    }

    public function testAge() {
        // Invalid.
        $this->variable(bantam\Validate::age('notage'))->isEqualTo(false);
        $this->variable(bantam\Validate::age(null))->isEqualTo(false);
        $this->variable(bantam\Validate::age(false))->isEqualTo(false);
        $this->variable(bantam\Validate::age(0))->isEqualTo(false);
        $this->variable(bantam\Validate::age(array()))->isEqualTo(false);
        // According to Wikipedia's list of the oldest people alive, anyone claiming
        // to be older than about 130 years, is probably not telling the truth, safe
        // to assume? Otherwise give them a cookie, they're on the internet!
        $this->variable(bantam\Validate::age(131))->isEqualTo(false);
        $this->variable(bantam\Validate::age(999))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::age(1))->isEqualTo(true);
        $this->variable(bantam\Validate::age(130))->isEqualTo(true);
        $this->variable(bantam\Validate::age('1'))->isEqualTo(true);
        $this->variable(bantam\Validate::age('130'))->isEqualTo(true);
        // Very specific, but technically still an age.
        $this->variable(bantam\Validate::age(1.5))->isEqualTo(true);
        $this->variable(bantam\Validate::age(50.25))->isEqualTo(true);
        $this->variable(bantam\Validate::age('1.5'))->isEqualTo(true);
        $this->variable(bantam\Validate::age('50.25'))->isEqualTo(true);
    }

    public function testRegex() {
        // Invalid.
        $this->variable(bantam\Validate::regex(null, 'hello world'))->isEqualTo(false);
        $this->variable(bantam\Validate::regex(123, 'hello world'))->isEqualTo(false);
        $this->variable(bantam\Validate::regex(false, 'hello world'))->isEqualTo(false);
        $this->variable(bantam\Validate::regex('abc', 'hello world'))->isEqualTo(false);
        $this->variable(bantam\Validate::regex('hello', 'hello world'))->isEqualTo(false);
        $this->variable(bantam\Validate::regex('world', 'hello world'))->isEqualTo(false);

        // Valid.
        $this->variable(bantam\Validate::regex('/\Ahello world\Z/', 'hello world'))->isEqualTo(true);
    }
}
