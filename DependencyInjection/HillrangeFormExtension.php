<?php
namespace Hillrange\Form\DependencyInjection;

use Hillrange\Form\Type\EventSubscriber\ImageSubscriber;
use Hillrange\Form\Type\Extension\ToggleTypeExtension;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HillrangeFormExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config        = $this->processConfiguration($configuration, $configs);

		$locator = new FileLocator(__DIR__ . '/../Resources/config');
		$loader  = new YamlFileLoader(
			$container,
			$locator
		);
		$loader->load('services.yaml');

        if (!empty($config['upload_path'])) {
            $container
                ->getDefinition(ImageSubscriber::class)
                ->addMethodCall('setTargetDir', [$config['upload_path']]);
            $container->setParameter('upload_path', $config['upload_path']);
        } else {
            $container
                ->getDefinition(ImageSubscriber::class)
                ->addMethodCall('setTargetDir', ['uploads']);
            $container->setParameter('upload_path', 'uploads');
        }

        $this->registerButtons($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function registerButtons(array $config, ContainerBuilder $container)
    {
        if (!empty($config['button_class_off'])) {
            $container
                ->getDefinition(ToggleTypeExtension::class)
                ->addMethodCall('setButtonClassOff', [$config['button_class_off']]);
            $container
                ->getDefinition(ToggleType::class)
                ->addMethodCall('setButtonClassOff', [$config['button_class_off']]);
        } else {
            $container
                ->getDefinition(ToggleTypeExtension::class)
                ->addMethodCall('setButtonClassOff', []);
            $container
                ->getDefinition(ToggleType::class)
                ->addMethodCall('setButtonClassOff', []);
        }

        if (!empty($config['button_toggle_swap'])) {
            $container
                ->getDefinition(ToggleTypeExtension::class)
                ->addMethodCall('setButtonToggleSwap', [$config['button_toggle_swap']]);
            $container
                ->getDefinition(ToggleType::class)
                ->addMethodCall('setButtonToggleSwap', [$config['button_toggle_swap']]);
        } else {
            $container
                ->getDefinition(ToggleTypeExtension::class)
                ->addMethodCall('setButtonToggleSwap', []);
            $container
                ->getDefinition(ToggleType::class)
                ->addMethodCall('setButtonToggleSwap', []);
        }
    }
}