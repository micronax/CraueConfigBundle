<?php

namespace Craue\ConfigBundle\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel {

	private $config;

	public function __construct($environment, $config) {
		parent::__construct($environment, true);

		$fs = new Filesystem();
		if (!$fs->isAbsolutePath($config)) {
			$config = __DIR__.'/config/'.$config;
		}

		if (!file_exists($config)) {
			throw new \RuntimeException(sprintf('The config file "%s" does not exist.', $config));
		}

		$this->config = $config;
	}

	public function registerBundles() {
		return array(
			new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
			new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
			new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new \Symfony\Bundle\TwigBundle\TwigBundle(),
			new \Craue\ConfigBundle\CraueConfigBundle(),
			new \Craue\ConfigBundle\Tests\IntegrationTestBundle\IntegrationTestBundle(),
		);
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load($this->config);

		if (class_exists('Symfony\Component\Asset\Package')) {
			// enable assets to avoid fatal error "Call to a member function needsEnvironment() on a non-object in vendor/twig/twig/lib/Twig/Node/Expression/Function.php on line 25" with Symfony 3.0
			$loader->load(function(ContainerBuilder $container) {
				$container->loadFromExtension('framework', array(
					'assets' => null,
				));
			});
		}
	}

	public function getCacheDir() {
		if (array_key_exists('CACHE_DIR', $_ENV)) {
			return $_ENV['CACHE_DIR'] . DIRECTORY_SEPARATOR . $this->environment;
		}

		return parent::getCacheDir();
	}

	public function getLogDir() {
		if (array_key_exists('LOG_DIR', $_ENV)) {
			return $_ENV['LOG_DIR'] . DIRECTORY_SEPARATOR . $this->environment;
		}

		return parent::getLogDir();
	}

	public function serialize() {
		return $this->config;
	}

	public function unserialize($config) {
		$this->__construct($config);
	}

}
