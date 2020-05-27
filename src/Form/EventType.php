<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Repository\LocationRepository;

class EventType extends AbstractType
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
            ->add('name')
            ->add('address', LocationType::class, ['label' => false, 'required' => false])
            ->add('dateStart', DateTimeType::class, ['widget' => 'single_text', 'html5'=>false])
            ->add('dateFinish', DateTimeType::class, ['widget' => 'single_text', 'html5'=>false])
            ->add('imageUrl')
            ->add('description', TextareaType::class, ['required' => false])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'postSubmit']
            )
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'preSetData']
            )
        ;
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        if ( $data->getAddress() instanceof Location ) {
            $form->add('address', LocationType::class, ['disabled' => true]);
        }

        if ($data->getId()) {
            $form->add('imageUrl', TextType::class, ['disabled' => true]);
        }
    }

    public function postSubmit(FormEvent $event)
    {
        if ($event->getData()->getAddress()) {
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
            'data_class' => Event::class,
        ));
    }
}
