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

class DevProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenoms', TextType::class, [
                'label' => 'Prénoms',
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'mapped' => false,
                'data' => $options['localisation'] ?? 'Paris'
            ])
            ->add('salaireMin', TextType::class, [
                'label' => 'Salaire minimum',
            ])
            ->add('tech', ChoiceType::class, [
                'choices' => $options['technologies'],  // Liste des technologies
                'choice_label' => function ($technology) {
                    return $technology->getTitre(); // Affiche les titres des technologies
                },
                'choice_value' => 'id',  // Valeur à utiliser pour la technologie
                'multiple' => true,
                'expanded' => false,  // Choix sous forme de liste déroulante
                'mapped' => false,
                'data' => $options['default_technologies']
            ])
            ->add('experienceLevel', ChoiceType::class, [
                'choices' => [
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'label' => 'Niveau d\'expérience',
            ])
            ->add('bibliographie', TextareaType::class, [
                'label' => 'Bibliographie',
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (optionnel)',
                'required' => false,  // Le champ n'est pas obligatoire
                'attr' => ['accept' => '.png, .jpeg, .jpg'], // Types de fichiers acceptés
                'mapped' => false,
            ])
            ->add('visibilite', CheckboxType::class, [
                'label' => 'Rendre mes informations confidentielles',
                'required' => false,  // Si tu veux que ce champ soit optionnel
                'attr' => ['class' => 'custom-control-input'],  // Applique la classe de Bootstrap
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider mes informations']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dev::class, // L'entité associée à ce formulaire
            'technologies' => [], // Liste des technologies
            'localisation' => 'Paris',
            'default_technologies' => [],
            
        ]);
    }
}
