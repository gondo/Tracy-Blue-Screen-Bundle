<?php

namespace VasekPurchart\TracyBlueScreenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements \Symfony\Component\Config\Definition\ConfigurationInterface
{

	const PARAMETER_CONSOLE_BROWSER = 'browser';
	const PARAMETER_CONSOLE_LISTENER_PRIORITY = 'listener_priority';
	const PARAMETER_CONTROLLER_ENABLED = 'enabled';
	const PARAMETER_CONTROLLER_LISTENER_PRIORITY = 'listener_priority';

	const SECTION_CONSOLE = 'console';
	const SECTION_CONTROLLER = 'controller';

	/** @var string */
	private $rootNode;

	/**
	 * @param string $rootNode
	 */
	public function __construct($rootNode)
	{
		$this->rootNode = $rootNode;
	}

	/**
	 * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root($this->rootNode);

		$rootNode
			->children()
				->arrayNode(self::SECTION_CONTROLLER)
					->addDefaultsIfNotSet()
					->children()
						->scalarNode(self::PARAMETER_CONTROLLER_ENABLED)
							->info('Enable debug screen for controllers.')
							->defaultNull()
							->end()
						->integerNode(self::PARAMETER_CONTROLLER_LISTENER_PRIORITY)
							->info('Priority with which the listener will be registered.')
							->defaultValue(0)
							->end()
						->end()
					->end()
				->arrayNode(self::SECTION_CONSOLE)
					->addDefaultsIfNotSet()
					->children()
						->scalarNode(self::PARAMETER_CONSOLE_BROWSER)
							->info(
								'Configure this to open generated BlueScreen in your browser.'
								. ' Configuration option may be for example \'google-chrome\' or \'firefox\''
								. ' and it will be invoked as a shell command.'
							)
							->defaultNull()
							->end()
						->integerNode(self::PARAMETER_CONSOLE_LISTENER_PRIORITY)
							->info('Priority with which the listener will be registered.')
							->defaultValue(0)
							->end()
						->end()
					->end()
				->end()
			->end();

		return $treeBuilder;
	}

}
