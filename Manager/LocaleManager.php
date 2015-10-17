<?php

namespace Wucdbm\Bundle\LocaleBundle\Manager;

use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\LocaleBundle\Locale\Locale;
use Wucdbm\Bundle\WucdbmBundle\Manager\AbstractManager;

class LocaleManager extends AbstractManager {

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var array
     */
    protected $locales = [];

    protected $cookieName = null;

    public function __construct($config, $defaultLocale) {
        foreach ($config['locales'] as $locale => $data) {
            $this->locales[$locale] = new Locale($locale, $data['name'], $data['enabled'], $data['currency']);
        }
        $this->defaultLocale = $defaultLocale;
        if (isset($config['cookie_listener']) && $config['cookie_listener']['enabled']) {
            $this->cookieName = $config['cookie_listener']['name'];
        }
    }

    public function getCurrentLocale() {
        if ($this->container->has('request_stack')) {
            $stack = $this->container->get('request_stack');
            $request = $stack->getCurrentRequest();

            return $request->attributes->get('_locale');
        }

        return $this->container->getParameter('locale');
    }

    public function getPreferredLocale() {
        // return default locale if no request stack
        if (!$this->container->has('request_stack')) {
            return $this->getDefaultLocale();
        }

        $stack = $this->container->get('request_stack');
        $request = $stack->getCurrentRequest();

        // return default locale if there isn't any current request
        if (!($request instanceof Request)) {
            return $this->getDefaultLocale();
        }

        // If cookie listener is enabled
        if ($this->cookieName) {
            $cookieLocale = $request->cookies->get($this->cookieName);
            if ($cookieLocale && $this->supportsLocale($cookieLocale)) {
                return $cookieLocale;
            }
        }

        // because if the browser sent no locale headers, $request->getPreferredLanguage would return the first of the $locales parameter
        // avoid using the first locale defined in $this->getLocales() and use $this->getDefaultLocale() instead
        if (!$request->getLanguages()) {
            return $this->getDefaultLocale();
        }

        // return the first supported locale from the browser-supplied ones
        return $this->getPreferredLanguage($request->getLanguages(), array_keys($this->getLocales()), $this->getDefaultLocale());
    }

    /**
     * TODO: Pull Request @ Symfony
     *
     * This method is a part of symfony's Request object
     * The problem with it is, if no matches between Browser's Preferred languages and locales param were found, the first element from locales was always returned
     * This is not desired, there should be an option to specify a default locale different from the first element of the $locales parameter
     *
     * @param $preferredLanguages
     * @param array $locales
     * @param string $defaultLocale
     * @return null
     */
    private function getPreferredLanguage($preferredLanguages, array $locales = null, $defaultLocale = null) {

        if (empty($locales)) {
            // 1. if there are any preferred languages, return first
            // 2. if $defaultLocale, return it
            // 3. else still return null
            if (isset($preferredLanguages[0])) {
                return $preferredLanguages[0];
            }

            return $defaultLocale ?: null;
        }

        if (!$preferredLanguages) {
            // 1. If $defaultLocale, return it
            // 2. else - first of $locales
            return $defaultLocale ?: $locales[0];
        }

        $extendedPreferredLanguages = array();
        foreach ($preferredLanguages as $language) {
            $extendedPreferredLanguages[] = $language;
            if (false !== $position = strpos($language, '_')) {
                $superLanguage = substr($language, 0, $position);
                if (!in_array($superLanguage, $preferredLanguages)) {
                    $extendedPreferredLanguages[] = $superLanguage;
                }
            }
        }

        $preferredLanguages = array_values(array_intersect($extendedPreferredLanguages, $locales));

        // 1. If a match was found - return it
        // 2. If a $defaultLocale parameter was given, return it
        // 3. If neither match nor default locale - return first of locales

        if (isset($preferredLanguages[0])) {
            return $preferredLanguages[0];
        }

        return $defaultLocale ?: $locales[0];
    }

    /**
     * @param $locale
     * @return bool
     */
    public function supportsLocale($locale) {
        return isset($this->locales[$locale]);
    }

    /**
     * @param $locale
     * @return Locale
     */
    public function getLocale($locale) {
        return isset($this->locales[$locale]) ? $this->locales[$locale] : null;
    }


    public function isLocaleEnabled($locale) {
        if (!$this->supportsLocale($locale)) {
            return false;
        }

        if (!isset($this->locales[$locale])) {
            return false;
        }

        /** @var Locale $object */
        $object = $this->locales[$locale];

        return $object->getIsEnabled();
    }

    /**
     * @return array
     */
    public function getLocales() {
        return $this->locales;
    }

    /**
     * @return array
     */
    public function getLocalesSimple() {
        return array_keys($this->locales);
    }

    /**
     * @param array $locales
     */
    public function setLocales($locales) {
        $this->locales = $locales;
    }

    /**
     * @return string
     */
    public function getDefaultLocale() {
        return $this->defaultLocale;
    }

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale) {
        $this->defaultLocale = $defaultLocale;
    }

}