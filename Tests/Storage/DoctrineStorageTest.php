<?php

namespace Craue\FormFlowBundle\Tests\Storage;

use Craue\FormFlowBundle\Storage\DoctrineStorage;
use Doctrine\DBAL\DriverManager;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class DoctrineStorageTest extends AbstractStorageTest {

	/**
	 * {@inheritDoc}
	 */
	protected function getStorageImplementation() {
		$conn = DriverManager::getConnection(array(
			'driver' => 'pdo_sqlite',
			'memory' => true,
		));

		$generator = $this->getMock('Craue\FormFlowBundle\Storage\StorageKeyGeneratorInterface');

		$generator
			->method('generate')
			->will($this->returnArgument(0))
		;

		return new DoctrineStorage($conn, $generator);
	}

	/**
	 * Ensure that quoted data is properly handled by DBAL.
	 * @dataProvider dataSetGet_stringsContainQuotes
	 */
	public function testSetGet_stringsContainQuotes($key, $value) {
		$this->storage->set($key, $value);
		$this->assertSame($value, $this->storage->get($key));
	}

	public function dataSetGet_stringsContainQuotes() {
		return array(
			array("f'oo", "b'ar"),
			array('f"oo', 'b"ar'),
		);
	}

}
