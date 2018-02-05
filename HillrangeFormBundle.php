<?php
namespace Hillrange\Form;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HillrangeFormBundle extends Bundle
{
	/**
	 * @param ContainerBuilder $container
	 */
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
	}
}
