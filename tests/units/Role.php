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
        $this->variable(bantam\Role::can('manager', 'create'))->isEqualTo(false);
        $this->variable(bantam\Role::inherit('manager', 'admin'))->isEqualTo(true);
        $this->variable(bantam\Role::can('manager', 'create'))->isEqualTo(true);
        $this->variable(bantam\Role::inherit('admin', 'user'))->isEqualTo(false);
    }

    public function testGetRoles() {
        // Setup.
        bantam\Role::reset(true);

        // Normal use.
        $this->array(bantam\Role::getRoles())->isEmpty();

        // Setup.
        bantam\Role::create('admin');
        bantam\Role::create('user');

        // Normal use.
        $this->array(bantam\Role::getRoles())
            ->strictlyContains('admin')
            ->strictlyContains('user')
            ->size
                ->isEqualTo(2);
    }

    public function testGetPerms() {
        // Setup.
        bantam\Role::reset(true);
        bantam\Role::create('admin');

        // Edge cases.
        $this->array(bantam\Role::getPerms(''))->isEmpty();
        $this->array(bantam\Role::getPerms(123))->isEmpty();
        $this->array(bantam\Role::getPerms(null))->isEmpty();
        $this->array(bantam\Role::getPerms('not-exist'))->isEmpty();

        // Normal use.
        $this->array(bantam\Role::getPerms('admin'))->isEmpty();

        // Setup.
        bantam\Role::set('admin', 'create');
        bantam\Role::set('admin', 'delete');

        // Normal use.
        $this->array(bantam\Role::getPerms('admin'))
            ->strictlyContains('create')
            ->strictlyContains('delete')
            ->size
                ->isEqualTo(2);
    }

    public function testReset() {
        // Edge cases.
        $this->variable(bantam\Role::reset(''))->isEqualTo(false);
        $this->variable(bantam\Role::reset(123))->isEqualTo(false);
        $this->variable(bantam\Role::reset(null))->isEqualTo(false);
        $this->variable(bantam\Role::reset())->isEqualTo(false);
        $this->variable(bantam\Role::reset('not-exist'))->isEqualTo(false);

        // Setup.
        bantam\Role::create('admin');
        bantam\Role::create('user');
        bantam\Role::set('admin', 'create');
        bantam\Role::set('user', 'view');

        // Normal Use.
        $this->variable(bantam\Role::reset('someone'))->isEqualTo(false);
        $this->variable(bantam\Role::reset('admin'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'create'))->isEqualTo(false);
        $this->variable(bantam\Role::exists('user'))->isEqualTo(true);
        $this->variable(bantam\Role::reset(true))->isEqualTo(true);
        $this->variable(bantam\Role::exists('user'))->isEqualTo(false);
    }

    public function testLoad() {
        $rolesTable = array(
            'admin' => array('create', 'delete', 'edit'),
            'user' => array('view', 'comment')
        );
        $rolesTableMalformed = array(
            'admin',
            123,
            'user' => array('view', 'comment')
        );
        $rolesTableMalformed2 = array(
            123
        );

        // Edge cases.
        $this->variable(bantam\Role::load(null))->isEqualTo(false);
        $this->variable(bantam\Role::load(''))->isEqualTo(false);
        $this->variable(bantam\Role::load(123))->isEqualTo(false);
        $this->variable(bantam\Role::load(array()))->isEqualTo(false);
        $this->variable(bantam\Role::load($rolesTableMalformed))->isEqualTo(true);
        $this->variable(bantam\Role::exists('admin'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'create'))->isEqualTo(false);
        $this->variable(bantam\Role::exists(123))->isEqualTo(false);
        $this->variable(bantam\Role::exists('user'))->isEqualTo(true);
        $this->variable(bantam\Role::can('user', 'view'))->isEqualTo(true);
        $this->variable(bantam\Role::can('user', 'comment'))->isEqualTo(true);
        bantam\Role::reset(true);
        $this->variable(bantam\Role::load($rolesTableMalformed2))->isEqualTo(false);
        bantam\Role::reset(true);

        // Normal use.
        $this->variable(bantam\Role::load($rolesTable))->isEqualTo(true);
        $this->variable(bantam\Role::exists('admin'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'create'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'delete'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'edit'))->isEqualTo(true);
        $this->variable(bantam\Role::can('admin', 'comment'))->isEqualTo(false);
        $this->variable(bantam\Role::exists('user'))->isEqualTo(true);
        $this->variable(bantam\Role::can('user', 'view'))->isEqualTo(true);
        $this->variable(bantam\Role::can('user', 'comment'))->isEqualTo(true);
        $this->variable(bantam\Role::can('user', 'create'))->isEqualTo(false);
    }
}
