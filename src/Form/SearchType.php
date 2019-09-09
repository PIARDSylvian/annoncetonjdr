<?php

namespace App\Form;

use App\Entity\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\GameRepository;

class SearchType extends AbstractType
{
    private $choiceGame;

    public function __construct(GameRepository $gameRepository)
    {
        $games = $gameRepository->findAll();
        $choiceGame = [];
        foreach($games as $game) {$choiceGame[$game->getName()] = $game->getId();}
        $this->choiceGame = $choiceGame;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search_lat', HiddenType::class, ['required' => false])
            ->add('search_lng', HiddenType::class, ['required' => false])
            ->add('search_address', TextType::class, ['required' => false])
            ->add('partyName', TextType::class, ['required' => false])
            ->add('distance', NumberType::class,['required' => false])
            ->add('gameName', ChoiceType::class, [
                'required' => false,
                'choices'  => $this->choiceGame,
            ])
            ->add('online', CheckboxType::class, ['required' => false])
            ->add('period', ChoiceType::class, [
                'choices'  => [
                    'toutes' => null,
                    '1 semaine' => '1 week',
                    '1 mois' => '1 month',
                    '3 mois' => '3 month'
                ],
            ])
            ->add('page', HiddenType::class, ['required' => false])
            ->add('nombres', ChoiceType::class, [
                'choices'  => [
                    '10' => 10,
                    '20' => 20,
                    '50' => 50,
                    '100' => 100
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
