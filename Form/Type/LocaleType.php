<?php

namespace Wucdbm\Bundle\LocaleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wucdbm\Bundle\LocaleBundle\Locale\Locale;
use Wucdbm\Bundle\LocaleBundle\Manager\LocaleManager;

class LocaleType extends AbstractType {

    /**
     * @var LocaleManager
     */
    protected $manager;

    public function __construct(LocaleManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $locales = $this->manager->getLocales();
        $choices = [];
        /** @var Locale $locale */
        foreach ($locales as $locale) {
            if ($locale->getIsEnabled()) {
                $choices[$locale->getLocale()] = $locale->getName();
            }
        }
        $resolver->setDefaults([
            'choices' => $choices
        ]);
    }

    public function getParent() {
        return 'choice';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wucdbm_locale';
    }

}