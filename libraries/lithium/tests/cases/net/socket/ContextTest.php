<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\net\socket;

use lithium\net\http\Request;
use lithium\net\http\Response;
use lithium\net\socket\Context;

class ContextTest extends \lithium\test\Unit {

	protected $_testConfig = array(
		'persistent' => false,
		'scheme' => 'http',
		'host' => 'example.org',
		'port' => 80,
		'timeout' => 4,
		'classes' => array('request' => 'lithium\net\http\Request')
	);

	protected $_testUrl = 'http://example.org';

	public function skip() {
		$this->skipIf(dns_check_record("example.org") === false, "No internet connection.");
	}

	public function tearDown() {
		unset($this->socket);
	}

	public function testConstruct() {
		$subject = new Context(array('timeout' => 300));
		$this->assertTrue(300, $subject->timeout());
		unset($subject);
	}

	public function testGetSetTimeout() {
		$this->assertEqual(4, $this->socket->timeout());
		$this->assertEqual(25, $this->socket->timeout(25));
		$this->assertEqual(25, $this->socket->timeout());

		$this->socket->open();
		$this->assertEqual(25, $this->socket->timeout());

		$result = stream_context_get_options($this->socket->resource());
		$this->assertEqual(25, $result['http']['timeout']);
	}

	public function testOpen() {
		$stream = new Context($this->_testConfig);
		$this->assertTrue(is_resource($stream->open()));
	}

	public function testClose() {
		$stream = new Context($this->_testConfig);
		$this->assertEqual(true, $stream->close());
	}

	public function testEncoding() {
		$stream = new Context($this->_testConfig);
		$this->assertEqual(false, $stream->encoding());
	}

	public function testEof() {
		$stream = new Context($this->_testConfig);
		$this->assertTrue(true, $stream->eof());
	}

	public function testMessageInConfig() {
		$socket = new Context(array('message' => new Request()));
		$this->assertTrue(is_resource($socket->open()));
	}

	public function testWriteAndRead() {
		$stream = new Context($this->_testConfig);
		$this->assertTrue(is_resource($stream->open()));
		$this->assertTrue(is_resource($stream->resource()));
		$this->assertEqual(1, $stream->write());
		$this->assertPattern("/^HTTP/", (string) $stream->read());
	}

	public function testSendWithNull() {
		$stream = new Context($this->_testConfig);
		$this->assertTrue(is_resource($stream->open()));
		$result = $stream->send(
			new Request($this->_testConfig),
			array('response' => 'lithium\net\http\Response')
		);
		$this->assertTrue($result instanceof Response);
		$this->assertPattern("/^HTTP/", (string) $result);
		$this->assertTrue($stream->eof());
	}

	public function testSendWithArray() {
		$stream = new Context($this->_testConfig);
		$this->assertTrue(is_resource($stream->open()));
		$result = $stream->send($this->_testConfig,
			array('response' => 'lithium\net\http\Response')
		);
		$this->assertTrue($result instanceof Response);
		$this->assertPattern("/^HTTP/", (string) $result);
		$this->assertTrue($stream->eof());
	}

	public function testSendWithObject() {
		$stream = new Context($this->_testConfig);
		$this->assertTrue(is_resource($stream->open()));
		$result = $stream->send(
			new Request($this->_testConfig),
			array('response' => 'lithium\net\http\Response')
		);
		$this->assertTrue($result instanceof Response);
		$this->assertPattern("/^HTTP/", (string) $result);
		$this->assertTrue($stream->eof());
	}
}

?>