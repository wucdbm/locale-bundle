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

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $bag = $container->getParameterBag();

        $bag->set('wucdbm_locale.config', $config);

        $locales = $config['locales'];
        $localesSimple = array_keys($locales);

        $bag->set('wucdbm_locale.locales', $locales);
        $bag->set('wucdbm_locale.locales_simple', $localesSimple);

        $loader->load('services/managers.xml');

        if (isset($config['cookie_listener']) && $config['cookie_listener']['enabled']) {
            $bag->set('wucdbm_locale.cookie_listener', $config['cookie_listener']);
            $loader->load('services/listener/cookie.xml');
        }

        if (isset($config['disabled_locale_redirect_listener']) && $config['cookie_listener']['enabled']) {
            $bag->set('wucdbm_locale.disabled_locale_redirect_listener', $config['disabled_locale_redirect_listener']);
            $loader->load('services/listener/disabled_locale_redirect.xml');
        }

        if (isset($config['jms_integration']) && $config['jms_integration']) {
            $bag->set('jms_translation.locales', $localesSimple);
        }

        $bag->set('wucdbm_locale.locales_enabled.routing', implode('|', $localesSimple));
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