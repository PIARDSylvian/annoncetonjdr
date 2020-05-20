<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'required' => false
            ))
            ->add('lastName', TextType::class, array(
                'required' => false
            ))
            ->add('pseudonym', TextType::class)
            ->add('email', EmailType::class, ['help' => 'Si modifié, vous deverez valider votre email, vous serez deconnecter.'])
            ->add('secretQ', ChoiceType::class, [
                'choices'  => [
                    'rq_1' => 'rq_1',
                    'rq_2' => 'rq_2',
                    'rq_3' => 'rq_3',
                ],
            ])
            ->add('secretR', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
