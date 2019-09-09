<?php

namespace App\Form;

use App\Entity\Party;
use App\Entity\Game;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Repository\LocationRepository;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class PartyType extends AbstractType
{
    private $entityManager;
    private $locationRepository;

    public function __construct(EntityManagerInterface $entityManager, LocationRepository $locationRepository)
    {
        $this->entityManager = $entityManager;
        $this->locationRepository = $locationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('partyName')
            ->add('online', CheckboxType::class, ['required' => false])
            ->add('address', LocationType::class, ['label' => false, 'required' => false])
            ->add('maxPlayer')
            ->add('alreadySubscribed', IntegerType::class, ['data' => 0])
            ->add('date', DateTimeType::class, ['format'=>'dd-MM-yyyy H:m','widget' => 'single_text'])
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
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'postSubmit']
            )
        ;
    }

    public function postSubmit(FormEvent $event)
    {
        if ($event->getData()->getAddress() && !$event->getData()->getOnline()) {
            $data = $event->getData();
            $req = $this->locationRepository->findOneBy(['address' => $event->getData()->getAddress()->getAddress(), 'lat' => $event->getData()->getAddress()->getLat(), 'lng' => $event->getData()->getAddress()->getLng()]);
            if($req) {
                $data->setAddress($req);
                $event->setData($data);
            }
        }
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
