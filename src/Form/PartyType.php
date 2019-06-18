<?php

namespace App\Form;

use App\Entity\Party;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPlayer')
            ->add('alreadySubscribed', IntegerType::class, ['data' => 0])
            ->add('date', DateTimeType::class, ['format'=>'dd-MM-yyyy H:m', 'data' => new \DateTime('now'),'widget' => 'single_text'] )
            ->add('minor', CheckboxType::class, ['required' => false])
            ->add('gameName')
            ->add('gameEdition', CheckboxType::class, ['required' => false])
            ->add('gameScenario', CheckboxType::class, ['required' => false])
            ->add('openedCampaign', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Party::class,
        ));
    }
}
