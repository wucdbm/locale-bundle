<?php

namespace Wucdbm\Bundle\LocaleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocaleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('locale', 'text', [
                'label' => 'Locale',
                'attr'  => [
                    'placeholder' => 'Locale'
                ]
            ])
            ->add('name', 'text', [
                'label' => 'Name',
                'attr'  => [
                    'placeholder' => 'Name'
                ]
            ])
            ->add('isEnabled', 'checkbox', [
                'label'    => 'Is Enabled',
                'required' => false
            ])
            ->add('currency', 'text', [
                'label' => 'Currency',
                'attr'  => [
                    'placeholder' => 'Currency'
                ]
            ]);
    }

    public function getName() {
        return 'wucdbm_locale';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wucdbm\Bundle\LocaleBundle\Locale\Locale',
        ));
    }

}