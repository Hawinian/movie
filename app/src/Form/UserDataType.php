<?php
/**
 * UserData type.
 */

namespace App\Form;

use App\Entity\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserDataType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'firstName',
            TextType::class,
            [
                'label' => 'label.firstName',
                'required' => false,
            ]
        );

        $builder->add(
            'age',
            IntegerType::class,
            [
                'label' => 'label.age',
                'required' => false,
            ]
        );
        $builder->add(
            'city',
            TextType::class,
            [
                'label' => 'label.city',
                'required' => true,
                'attr' => ['max_length' => 128],
            ]
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserData::class,
        ));
    }
}
