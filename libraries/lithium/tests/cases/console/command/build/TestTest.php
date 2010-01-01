<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\console\command\build;

use \lithium\console\command\Build;
use \lithium\console\command\build\Test;
use \lithium\console\Request;
use \lithium\core\Libraries;

class TestTest extends \lithium\test\Unit {

	public $request;

	protected $_backup = array();

	protected $_testPath = null;

	public function setUp() {
		$this->classes = array('response' => '\lithium\tests\mocks\console\MockResponse');
		$this->_backup['cwd'] = getcwd();
		$this->_backup['_SERVER'] = $_SERVER;
		$_SERVER['argv'] = array();
		$this->_testPath = LITHIUM_APP_PATH . '/resources/tmp/tests';

		Libraries::add('build_test', array('path' => $this->_testPath .'/build_test'));
		$this->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->request->params = array('library' => 'build_test');
	}

	public function tearDown() {
		$_SERVER = $this->_backup['_SERVER'];
		chdir($this->_backup['cwd']);
		$this->_cleanUp();
	}

	public function testModel() {
		$test = new Test(array(
			'request' => $this->request, 'classes' => $this->classes
		));
		$test->path = $this->_testPath;
		$test->run('model', 'Post');
		$expected = "PostTest created for Post in build_test\\tests\\cases\\models.\n";
		$result = $test->response->output;
		$this->assertEqual($expected, $result);

		$expected = <<<'test'


namespace build_test\tests\cases\models;

use \build_test\models\Post;

class PostTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}


}


test;
		$replace = array("<?php", "?>");
		$result = str_replace($replace, '',
			file_get_contents($this->_testPath . '/build_test/tests/cases/models/PostTest.php')
		);
		$this->assertEqual($expected, $result);
	}

	public function testMockModel() {
		$test = new Test(array(
			'request' => $this->request, 'classes' => $this->classes
		));
		$test->path = $this->_testPath;
		$test->mock('model', 'Post');
		$expected = "MockPost created for Post in build_test\\tests\\mocks\\models.\n";
		$result = $test->response->output;
		$this->assertEqual($expected, $result);

		$expected = <<<'test'


namespace build_test\tests\mocks\models;

class MockPost extends \build_test\models\Post {


}


test;
		$replace = array("<?php", "?>");
		$result = str_replace($replace, '',
			file_get_contents($this->_testPath . '/build_test/tests/mocks/models/MockPost.php')
		);
		$this->assertEqual($expected, $result);
	}
}

?>