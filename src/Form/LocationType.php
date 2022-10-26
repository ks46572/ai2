<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CityName')
            ->add('CountryCode', ChoiceType::class, [
                'choices' => [
                    'Polska' => "PL",
                    'Niemcy' => "DE",
                    'Czechy' => "CZ",
                    'Norwegia' => "NO",
                ],
            ])
            ->add('Lon')
            ->add('Lat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
