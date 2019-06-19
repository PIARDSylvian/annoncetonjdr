<?php

namespace App\Form;

use App\Entity\Party;
use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;

class PartyType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('partyName')
            ->add('maxPlayer')
            ->add('alreadySubscribed', IntegerType::class, ['data' => 0])
            ->add('date', DateTimeType::class, ['format'=>'dd-MM-yyyy H:m', 'data' => new \DateTime('now'),'widget' => 'single_text'] )
            ->add('minor', CheckboxType::class, ['required' => false])
            ->add('gameName', EntityType::class, [
                'placeholder' => 'Select a game',
                'class' => Game::class,
                'choice_label' => 'name',
            ])
            ->add('gameEdition', CheckboxType::class, ['required' => false])
            ->add('nameScenario', TextType::class, ['required' => false])
            ->add('scenarioEdition', CheckboxType::class, ['required' => false])
            ->add('openedCampaign', CheckboxType::class, ['required' => false])
            ->add('gameDescription', TextareaType::class, ['required' => false])

            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'preSubmit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Party::class,
        ));
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $gameName = $data['gameName'];

        if( !is_numeric($gameName) && !empty($gameName) ) {
            $newGame = new game();
            $newGame->setName($gameName);
            $this->entityManager->persist($newGame);
            $this->entityManager->flush();

            $data['gameName'] = strval($newGame->getId());
            $event->setData($data);
        }
    }
}
