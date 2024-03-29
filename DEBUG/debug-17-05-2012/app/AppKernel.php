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
            new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Scube\BaseBundle\ScubeBaseBundle(),
			new Scube\ManageFileBundle\ScubeManageFileBundle(),
			new Scube\AdminAppsBundle\ScubeAdminAppsBundle(),
            new Scube\InstallBundle\ScubeInstallBundle(),
            new Scube\AdminUserBundle\ScubeAdminUserBundle(),
			new Scube\AdminSettingsBundle\ScubeAdminSettingsBundle(),
			new Scube\AdminLogsBundle\ScubeAdminLogsBundle(),
			new Scube\ConnectionsBundle\ScubeConnectionsBundle(),
			new Scube\AccountBundle\ScubeAccountBundle(),
			new Scube\CalendarBundle\ScubeCalendarBundle(),
			new Scube\MyAppsBundle\ScubeMyAppsBundle(),
			new Widgets\ConnectionsWidgetBundle\WidgetsConnectionsWidgetBundle(),
			new Scube\ProfileViewerBundle\ScubeProfileViewerBundle(),
			new Scube\MailboxBundle\ScubeMailboxBundle(),
			/*APP_DELIMITER*/
   
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
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
