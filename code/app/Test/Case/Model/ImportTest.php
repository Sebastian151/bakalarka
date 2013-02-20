<?php
App::uses('Import', 'Model');

/**
 * Import Test Case
 *
 */
class ImportTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.import',
		'app.user',
		'app.user_type',
		'app.transaction'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Import = ClassRegistry::init('Import');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Import);

		parent::tearDown();
	}

}
