<?php
/**
 * Review type.
 */

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class ReviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'content',
            TextareaType::class,
            [
                'label' => 'label.content',
                'required' => true,
            ]
        );

        $builder->add(
            'rate',
            RangeType::class,
            [
                'label' => 'label.rate',
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                ],
            ]
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
