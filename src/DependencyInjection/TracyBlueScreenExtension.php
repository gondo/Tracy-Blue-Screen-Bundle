<?php

namespace VasekPurchart\TracyBlueScreenBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TracyBlueScreenExtension extends \Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension
{

	const CONTAINER_PARAMETER_CONSOLE_BROWSER = 'vasek_purchart.tracy_blue_screen.console.browser';
	const CONTAINER_PARAMETER_CONSOLE_LISTENER_PRIORITY = 'vasek_purchart.tracy_blue_screen.console.listener_priority';
	const CONTAINER_PARAMETER_CONTROLLER_LISTENER_PRIORITY = 'vasek_purchart.tracy_blue_screen.controller.listener_priority';

	/**
	 * @param mixed[] $mergedConfig
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 */
	public function loadInternal(array $mergedConfig, ContainerBuilder $container)
	{
		$container->setParameter(
			self::CONTAINER_PARAMETER_CONSOLE_BROWSER,
			$mergedConfig[Configuration::SECTION_CONSOLE][Configuration::PARAMETER_CONSOLE_BROWSER]
		);
		$container->setParameter(
			self::CONTAINER_PARAMETER_CONSOLE_LISTENER_PRIORITY,
			$mergedConfig[Configuration::SECTION_CONSOLE][Configuration::PARAMETER_CONSOLE_LISTENER_PRIORITY]
		);
		$container->setParameter(
			self::CONTAINER_PARAMETER_CONTROLLER_LISTENER_PRIORITY,
			$mergedConfig[Configuration::SECTION_CONTROLLER][Configuration::PARAMETER_CONTROLLER_LISTENER_PRIORITY]
		);

		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
		$loader->load('services.yml');

		$environment = $container->getParameter('kernel.environment');
		$debug = $container->getParameter('kernel.debug');

		$loader->load('console_listener.yml');
		if ($this->isEnabled(
			$mergedConfig[Configuration::SECTION_CONTROLLER][Configuration::PARAMETER_CONTROLLER_ENABLED],
			$environment,
			$debug
		)) {
			$loader->load('controller_listener.yml');
		}
	}

	/**
	 * @param mixed[] $config
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \VasekPurchart\TracyBlueScreenBundle\DependencyInjection\Configuration
	 */
	public function getConfiguration(array $config, ContainerBuilder $container)
	{
		return new Configuration(
			$this->getAlias()
		);
	}

	/**
	 * @param boolean|null $configOption
	 * @param string $environment
	 * @param boolean $debug
	 * @return boolean
	 */
	private function isEnabled($configOption, $environment, $debug)
	{
		if ($configOption === null) {
			return $environment === 'dev' && $debug === true;
		}

		return $configOption;
	}

}
