<?php

namespace Craue\ConfigBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Originally taken from https://github.com/FriendsOfSymfony/FOSUserBundle/blob/192c53916942847aee687722af54f431aead0b70/DependencyInjection/Compiler/RegisterMappingsPass.php.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class RegisterMappingsPass implements CompilerPassInterface {

	private $driver;
	private $driverPattern;
	private $namespaces;
	private $enabledParameter;
	private $fallbackManagerParameter;

	public function __construct($driver, $driverPattern, $namespaces, $enabledParameter, $fallbackManagerParameter) {
		$this->driver = $driver;
		$this->driverPattern = $driverPattern;
		$this->namespaces = $namespaces;
		$this->enabledParameter = $enabledParameter;
		$this->fallbackManagerParameter = $fallbackManagerParameter;
	}

	/**
	 * Register mappings with the metadata drivers.
	 * @param ContainerBuilder $container
	 */
	public function process(ContainerBuilder $container) {
		if (!$container->hasParameter($this->enabledParameter)) {
			return;
		}

		$chainDriverDef = $container->getDefinition($this->getChainDriverServiceName($container));
		foreach ($this->namespaces as $namespace) {
			$chainDriverDef->addMethodCall('addDriver', array($this->driver, $namespace));
		}
	}

	protected function getChainDriverServiceName(ContainerBuilder $container) {
		$name = $container->getParameter($this->fallbackManagerParameter);

		if (empty($name)) {
			throw new \InvalidArgumentException(sprintf('No valid name could be found for manager "%s".', $this->fallbackManagerParameter));
		}

		return sprintf($this->driverPattern, $name);
	}

	public static function createOrmMappingDriver(array $mappings) {
		$locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', array($mappings, '.orm.xml'));
		$driver = new Definition('Doctrine\ORM\Mapping\Driver\XmlDriver', array($locator));

		return new static($driver, 'doctrine.orm.%s_metadata_driver', $mappings, 'craue_config.db_driver.doctrine_orm', 'doctrine.default_entity_manager');
	}

}
