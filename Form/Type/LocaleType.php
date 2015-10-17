<?php

namespace Wucdbm\Bundle\LocaleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocaleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('locale', 'text', [
                'label'       => 'Locale',
                'attr'        => [
                    'placeholder' => 'Locale'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('name', 'text', [
                'label'       => 'Name',
                'attr'        => [
                    'placeholder' => 'Name'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('isEnabled', 'checkbox', [
                'label'       => 'Is Enabled',
                'required'    => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('currency', 'text', [
                'label'       => 'Currency',
                'attr'        => [
                    'placeholder' => 'Currency'
                ],
                'constraints' => [
                    new NotBlank()
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