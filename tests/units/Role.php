<?php namespace r3oath\bantam\tests\units;

include_once 'src/Role.php';

use \mageekguy\atoum;
use \r3oath\bantam;

class Role extends atoum\test {
    public function testCreate() {
        // Edge cases.
        $this->variable(bantam\Role::create(''))->isEqualTo(false);
        $this->variable(bantam\Role::create(null))->isEqualTo(false);
        $this->variable(bantam\Role::create(123))->isEqualTo(false);
        $this->variable(bantam\Role::exists(''))->isEqualTo(false);
        $this->variable(bantam\Role::exists(null))->isEqualTo(false);
        $this->variable(bantam\Role::exists(123))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Role::create('admin'))->isEqualTo(true);
        $this->variable(bantam\Role::exists('admin'))->isEqualTo(true);
    }

    public function testSet() {
        // Setup.
        bantam\Role::create('admin');

        // Edge cases.
        $this->variable(bantam\Role::set(null, ''))->isEqualTo(false);
        $this->variable(bantam\Role::set('', ''))->isEqualTo(false);
        $this->variable(bantam\Role::set(123, ''))->isEqualTo(false);
        $this->variable(bantam\Role::set('admin', ''))->isEqualTo(false);
        $this->variable(bantam\Role::set('admin', null))->isEqualTo(false);
        $this->variable(bantam\Role::set('admin', 123))->isEqualTo(false);
        $this->variable(bantam\Role::can('admin', ''))->isEqualTo(false);
        $this->variable(bantam\Role::can('admin', null))->isEqualTo(false);
        $this->variable(bantam\Role::can('admin', 123))->isEqualTo(false);
        $this->variable(bantam\Role::can(null, ''))->isEqualTo(false);
        $this->variable(bantam\Role::can('', ''))->isEqualTo(false);
        $this->variable(bantam\Role::can(123, ''))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Role::set('admin', 'create'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'create'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'create123'))->isEqualTo(false);
    }

    public function testInherit() {
        // Setup.
        bantam\Role::create('admin');
        bantam\Role::create('manager');
        bantam\Role::set('admin', 'create');
        bantam\Role::set('manager', 'manage');

        // Edge cases.
        $this->variable(bantam\Role::inherit('admin', null))->isEqualTo(false);
        $this->variable(bantam\Role::inherit('admin', ''))->isEqualTo(false);
        $this->variable(bantam\Role::inherit('admin', 123))->isEqualTo(false);
        $this->variable(bantam\Role::inherit(null, 'manager'))->isEqualTo(false);
        $this->variable(bantam\Role::inherit('', 'manager'))->isEqualTo(false);
        $this->variable(bantam\Role::inherit(123, 'manager'))->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Role::inherit('admin', 'manager'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'manage'))->isEqualTo(true);
        $this->variable(bantam\Role::inherit('manager', 'admin'))->isEqualTo(true);
        $this->variable(bantam\Role::can('manager', 'create'))->isEqualTo(true);
        $this->variable(bantam\Role::inherit('admin', 'user'))->isEqualTo(false);
    }
}
