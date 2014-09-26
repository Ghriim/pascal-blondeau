<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new FOS\UserBundle\FOSUserBundle(),

            new PBlondeau\Bundle\CommonBundle\PBlondeauCommonBundle(),
            new PBlondeau\Bundle\NewsBundle\PBlondeauNewsBundle(),
            new PBlondeau\Bundle\WorkBundle\PBlondeauWorkBundle(),
            new PBlondeau\Bundle\ExhibitionBundle\PBlondeauExhibitionBundle(),
            new PBlondeau\Bundle\PressBundle\PBlondeauPressBundle(),
            new PBlondeau\Bundle\BiographyBundle\PBlondeauBiographyBundle(),
            new PBlondeau\Bundle\ContactBundle\PBlondeauContactBundle(),
            new PBlondeau\Bundle\SlideShowBundle\PBlondeauSlideShowBundle(),
            new PBlondeau\Bundle\UserBundle\PBlondeauUserBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle();

            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
