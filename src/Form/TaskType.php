<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',  TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir le champs'])
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'height:30px;',
                    'placeholder' => 'Entrer un titre'
                ]
            ])
            ->add('list', EntityType::class, [
                'class' => TaskList::class,
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color:grey'
                ],
                'placeholder' => 'Choisir une liste',
                'choice_label' => fn (TaskList $taskList) => $taskList->getName(),
                'constraints' => new NotBlank(['message' => 'Veuillez choisir une liste']),
                'query_builder' => function (TaskListRepository $taskListRepository) {
                    return $taskListRepository->createQueryBuilder('t')->orderBy('t.name', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => 'btn btn-secondary mt-5',
                    'formnovalidate' => 'formnovalidate'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
