<?php
/**
 * Movie type.
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use App\Form\DataTransformer\CountryDataTransformer;
use App\Form\DataTransformer\DirectorDataTransformer;
use App\Form\DataTransformer\ScreenwriterDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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
     * Country data transformer.
     *
     * @var CountryDataTransformer|null
     */
    private $countryDataTransformer = null;
    /**
     * Director data transformer.
     *
     * @var DirectorDataTransformer|null
     */
    private $directorDataTransformer = null;
    /**
     * Screenwriter data transformer.
     *
     * @var ScreenwriterDataTransformer|null
     */
    private $screenwriterDataTransformer = null;

    /**
     * MovieType constructor.
     *
     * @param ActorsDataTransformer       $actorsDataTransformer
     * @param CountryDataTransformer      $countryDataTransformer
     * @param DirectorDataTransformer     $directorDataTransformer
     * @param ScreenwriterDataTransformer $screenwriterDataTransformer
     */
    public function __construct(ActorsDataTransformer $actorsDataTransformer, CountryDataTransformer $countryDataTransformer, DirectorDataTransformer $directorDataTransformer, ScreenwriterDataTransformer $screenwriterDataTransformer)
    {
        $this->actorsDataTransformer = $actorsDataTransformer;
        $this->countryDataTransformer = $countryDataTransformer;
        $this->directorDataTransformer = $directorDataTransformer;
        $this->screenwriterDataTransformer = $screenwriterDataTransformer;
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
                'attr' => ['maxlength' => 128],
                ]
            );

        $builder->add(
            'year',
            NumberType::class,
            [
                    'label' => 'label.year',
                    'required' => true,
                    'attr' => ['maxlength' => 4],
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
        $builder->add(
            'boxoffice',
            NumberType::class,
            [
                    'label' => 'label.boxoffice',
                    'required' => true,
                    'attr' => [
                    'max' => 3000000000,
                    ],
                ]
        );

        $builder->add(
            'country',
            TextType::class,
            [
                'label' => 'label.country',
                'required' => true,
                'attr' => ['maxlength' => 128],
                //'class' => Country::class,
                //'choice_label' => 'name',
                ]
        );

        $builder->add(
            'director',
            TextType::class,
            [
                    'label' => 'label.director',
                    'required' => true,
                'attr' => ['maxlength' => 128],
                    //'class' => Director::class,
                   // 'choice_label' => 'name',
                ]
        );

        $builder->add(
            'screenwriter',
            TextType::class,
            [
                    'label' => 'label.screenwriter',
                    'required' => true,
                'attr' => ['maxlength' => 128],
                  //  'class' => Screenwriter::class,
                   // 'choice_label' => 'name',
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
                        'maxlength' => 255,
                    ],
                ]
        );
        $builder->get('actors')->addModelTransformer(
            $this->actorsDataTransformer
        );

        $builder->get('country')->addModelTransformer(
            $this->countryDataTransformer
        );

        $builder->get('director')->addModelTransformer(
            $this->directorDataTransformer
        );

        $builder->get('screenwriter')->addModelTransformer(
            $this->screenwriterDataTransformer
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
