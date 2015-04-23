<?php

namespace Craue\ConfigBundle;

use Craue\ConfigBundle\DependencyInjection\Compiler\RegisterMappingsPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CraueConfigBundle extends Bundle {

	/**
	 * {@inheritDoc}
	 */
	public function build(ContainerBuilder $container) {
		parent::build($container);

		$this->addRegisterMappingsPass($container);
	}

	/**
	 * Originally taken from https://github.com/FriendsOfSymfony/FOSUserBundle/blob/192c53916942847aee687722af54f431aead0b70/FOSUserBundle.php#L36.
	 * @param ContainerBuilder $container
	 */
	private function addRegisterMappingsPass(ContainerBuilder $container) {
		$mappings = array(
			realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'Craue\ConfigBundle\Entity',
		);

		// the base class is only available since Symfony 2.3
		// TODO remove as soon as Symfony >= 2.3 is required
		$baseClassExists = class_exists('Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass');

		if ($baseClassExists && class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
			$container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array(), 'craue_config.db_driver.doctrine_orm'));
		} else {
			$container->addCompilerPass(RegisterMappingsPass::createOrmMappingDriver($mappings));
		}
	}

}
