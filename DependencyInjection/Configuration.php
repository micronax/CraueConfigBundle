<?php

namespace Craue\ConfigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for the bundle.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Configuration implements ConfigurationInterface {

	/**
	 * {@inheritDoc}
	 */
	public function getConfigTreeBuilder() {
		$supportedDrivers = array('doctrine_orm');

		$treeBuilder = new TreeBuilder();
		$treeBuilder->root('craue_config')
			->children()
				->scalarNode('db_driver')
					->defaultValue($supportedDrivers[0])
					->validate()
						->ifNotInArray($supportedDrivers)
						->thenInvalid('The driver "%s" is not supported. Please choose one of ' . json_encode($supportedDrivers))
					->end()
				->end()
				->scalarNode('setting_class')
					->defaultValue('Craue\ConfigBundle\Entity\Setting')
				->end()
			->end()
		;

		return $treeBuilder;
	}

}
