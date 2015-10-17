<?php

namespace Wucdbm\Bundle\LocaleBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CookieListener implements EventSubscriberInterface {

    protected $name = 'locale';

    protected $duration;

    protected $path = '/';

    public function __construct($config) {
        $this->duration = 60 * 60 * 24 * 365;

        if ($config['name']) {
            $this->name = $config['name'];
        }

        if ($config['duration']) {
            $this->duration = $config['duration'];
        }

        if ($config['path']) {
            $this->path = $config['path'];
        }
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::RESPONSE => [
                'setLocale',
                -255
            ]
        ];
    }

    public function setLocale(FilterResponseEvent $event) {
        $request = $event->getRequest();
        $localeCookie = $request->cookies->get('locale');
        $currentLocale = $request->getLocale();

        if ($localeCookie == $currentLocale) {
            return;
        }

        if ($request->isXmlHttpRequest()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->setCookie(new Cookie($this->name, $currentLocale, time() + $this->duration, $this->path));
    }

}