<?php
/**
 * Created by PhpStorm.
 * User: darlington
 * Date: 14/11/16
 * Time: 16:36
 */

namespace BookReview\ReviewBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class APIBookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('authors')
            ->add('publisher')
            ->add('description', TextareaType::class, ['attr' => ['class' => 'text-area']])
            ->add('isbn10', TextType::class, ['label' => 'ISBN10'])
            ->add('isbn13', TextType::class, ['label' => 'ISBN13'])
            ->add('pageCount')
            ->add('printType')
            ->add('categories')
            ->add('thumbnail', FileType::class, ['label' => 'Image of Book Cover'])
            ->add('submit',SubmitType::class, ['label' => 'Add Book', 'attr' => ['class' => 'btn-primary']])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BookReview\ReviewBundle\Entity\Book',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bookreview_reviewbundle_book';
    }


}
