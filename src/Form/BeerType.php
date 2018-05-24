<?php

namespace App\Form;

use App\Entity\Beer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'app.beer.form.name'])
            ->add('alcoholContent', null, ['label' => 'app.beer.form.alcoholContent'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Beer::class,
        ]);
    }
}
