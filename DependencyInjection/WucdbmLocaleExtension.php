<?php

namespace Wucdbm\Bundle\LocaleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WucdbmLocaleExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        var_dump($config);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $bag = $container->getParameterBag();
        $bag->set('wucdbm_locale.locales', $config['locales']);

        $loader->load('services/managers.xml');

        if (isset($config['cookie_listener']) && $config['cookie_listener']['enabled']) {
            $bag->set('wucdbm_locale.cookie_listener', $config['cookie_listener']);
            $loader->load('services/listener/cookie.xml');
        }

        if (isset($config['disabled_locale_redirect_listener']) && $config['cookie_listener']['enabled']) {
            $bag->set('wucdbm_locale.disabled_locale_redirect_listener', $config['disabled_locale_redirect_listener']);
            $loader->load('services/listener/disabled_locale_redirect.xml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container) {
        return new Configuration();
    }

    public function getXsdValidationBasePath() {
        return __DIR__ . '/../Resources/config/';
    }

    public function getNamespace() {
        return 'http://www.example.com/symfony/schema/';
    }

}