<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            
            
            ->add('organization',TextType::class)
            
            ->add('fundingGoal',TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('fundingGoal',TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('title',TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('content',TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
             
            ->add('phone',TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])

            ->add('dateLimit', DateTimeType::class, [
                'time_label' => 'Starts On',
            ])
             
            ->add('fundingGoal',TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('location',ChoiceType::class, [
                'choices'  => [
                    'Ariana' =>  'Ariana','Kasserine' =>  'Kasserine','Sfax' =>  'Sfax',
                    'Béja' =>  'Béja','Kébili' =>  'Kébili','Sidi Bouzid' =>  'Sidi Bouzid',
                    'Ben Arous' =>  'Ben Arous','Le Kef' =>  'Le Kef','Siliana' =>  'Siliana',
                    'Bizerte' =>  'Bizerte','Mahdia' =>  'Mahdia','Sousse' =>  'Sousse',
                    'Gabès' =>  'Gabès','La Manouba' =>  'La Manouba','Tataouine' =>  'Tataouine',
                    'Gafsa' =>  'Gafsa','Médenine' =>  'Médenine','Tozeur' =>  'Tozeur',
                    'Jendouba' =>  'Jendouba','Monastir' =>  'Monastir','Tunis' =>  'Tunis',
                    'Kairouan' =>  'Kairouan','Nabeul' =>  'Nabeul','Zaghouan' =>  'Zaghouan',
                     
                ]
            ])
            ->add('thumbnail', FileType::class,[
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                 
                'multiple' => false,
                'expanded' => false
            ])
             
             
            
             
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
