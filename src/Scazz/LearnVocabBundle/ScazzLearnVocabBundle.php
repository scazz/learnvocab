<?php

namespace Scazz\LearnVocabBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Scazz\LearnVocabBundle\DependencyInjection\Security\Factory\AuthFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ScazzLearnVocabBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$extension = $container->getExtension('security');
		$extension->addSecurityListenerFactory(new AuthFactory());
	}
}
