<?php


namespace App\Form;


use FOS\UserBundle\Form\Type\ProfileFormType as BaseRegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array('label' => 'form.firstName', 'translation_domain' => 'FOSUserBundle'))
            ->add('lastName', null, array('label' => 'form.lastName', 'translation_domain' => 'FOSUserBundle'))
            ->add('about', TextareaType::class, ['row_attr'=>['rows'=>'5']])
            ->add('title', null, array('label' => 'form.title', 'translation_domain' => 'FOSUserBundle'))
            ->add('facebook', null, array('label' => 'form.facebook', 'translation_domain' => 'FOSUserBundle'))
            ->add('instagram', null, array('label' => 'form.instagram', 'translation_domain' => 'FOSUserBundle'))
            ->add('github', null, array('label' => 'form.github', 'translation_domain' => 'FOSUserBundle'))
            ->add('linkedin', null, array('label' => 'form.linkedin', 'translation_domain' => 'FOSUserBundle'))
            ->add('website', null, array('label' => 'form.website', 'translation_domain' => 'FOSUserBundle'))
            ->add('address', null, array('label' => 'form.address', 'translation_domain' => 'FOSUserBundle'))
            ->add('numTel', null, array('label' => 'form.numTel', 'translation_domain' => 'FOSUserBundle'))
            ->add('imageFile', FileType::class, ['required' => false]);

    }
    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}