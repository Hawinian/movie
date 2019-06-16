<?php
/**
 * Movie type.
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Director;
use App\Entity\Movie;
use App\Entity\Screenwriter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\ActorsDataTransformer;

/**
 * Class MovieType.
 */
class MovieType extends AbstractType
{
    /**
     * Actors data transformer.
     *
     * @var ActorsDataTransformer|null
     */
    private $actorsDataTransformer = null;

    /**
     * MovieType constructor.
     *
     * @param ActorsDataTransformer $actorsDataTransformer Actors data transformer
     */
    public function __construct(ActorsDataTransformer $actorsDataTransformer)
    {
        $this->actorsDataTransformer = $actorsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                'label' => 'label.title',
                'required' => true,
                'attr' => ['max_length' => 128],
                ]
            );

        $builder->add(
            'year',
            NumberType::class,
            [
                    'label' => 'label.year',
                    'required' => true,
                    'attr' => ['max_length' => 4],
                        ]
        );

        $builder->add(
            'rate',
            NumberType::class,
            [
            'label' => 'label.rate',
            'required' => true,
            'attr' => ['max_length' => 2],
            ]
        );
        $builder->add(
            'boxoffice',
            NumberType::class,
            [
                    'label' => 'label.boxoffice',
                    'required' => true,
                    'attr' => ['max_length' => 4],
                ]
        );


        $builder->add(
            'country',
            EntityType::class,
            [
                'label' => 'label.country',
                'required' => true,
                'class' => Country::class,
                'choice_label' => 'name',
                ]
        );

        $builder->add(
            'director',
            EntityType::class,
            [
                    'label' => 'label.director',
                    'required' => true,
                    'class' => Director::class,
                    'choice_label' => 'name',
                ]
        );

        $builder->add(
            'screenwriter',
            EntityType::class,
            [
                    'label' => 'label.screenwriter',
                    'required' => true,
                    'class' => Screenwriter::class,
                    'choice_label' => 'name',
                ]
        );

        $builder->add(
            'category',
            EntityType::class,
            [
            'label' => 'label.category',
            'required' => true,
                'class' => Category::class,
                'choice_label' => 'name',
            ]
        );

        $builder->add(
            'actors',
            TextType::class,
            [
                    'label' => 'label.actors',
                    'required' => false,
                    'attr' => [
                        'max_length' => 255,
                    ],
                ]
        );

        $builder->get('actors')->addModelTransformer(
            $this->actorsDataTransformer
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Movie::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'movie';
    }
}