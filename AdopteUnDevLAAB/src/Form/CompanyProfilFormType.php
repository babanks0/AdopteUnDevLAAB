<?php

namespace App\Form;

use App\Entity\Dev;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompanyProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raisonSociale', TextType::class, [
                'label' => 'Raison sociale',
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'mapped' => false,
                'data' => $options['localisation'] ?? 'Paris'
            ]) 
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (optionnel)',
                'required' => false,  // Le champ n'est pas obligatoire
                'attr' => ['accept' => '.png, .jpeg, .jpg'], // Types de fichiers acceptés
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Modifier']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dev::class, // L'entité associée à ce formulaire
            'localisation' => 'Paris',
        ]);
    }
}
