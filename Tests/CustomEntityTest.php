<?php

namespace Craue\ConfigBundle\Tests;

use Craue\ConfigBundle\Tests\IntegrationTestBundle\Entity\CustomSetting;
use Craue\ConfigBundle\Tests\IntegrationTestCase;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CustomEntityTest extends IntegrationTestCase {

	public function testCustomEntity() {
		$client = static::createClient(array('environment' => 'customEntity', 'config' => 'config_customEntity.yml'));
		$container = $client->getContainer();
		$customConfig = $container->get('craue_config');

		$this->assertInstanceOf('Craue\ConfigBundle\Tests\IntegrationTestBundle\Util\CustomConfig', $customConfig);

		$customSetting = new CustomSetting();
		$customSetting->setName('name1');
		$customSetting->setValue('value1');
		$customSetting->setSection('section1');
		$customSetting->setComment('comment1');

		$em = $container->get('doctrine')->getManager();
		$em->persist($customSetting);
		$em->flush();

		$fetchedSetting = $customConfig->getSetting('name1');
		$this->assertInstanceOf('Craue\ConfigBundle\Tests\IntegrationTestBundle\Entity\CustomSetting', $fetchedSetting);
		$this->assertSame($customSetting, $fetchedSetting);
		$this->assertEquals('value1', $customConfig->get('name1'));
	}

}
