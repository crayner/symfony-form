<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 27/06/2018
 * Time: 22:13
 */
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\EventSubscriber\ChainedChoiceSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChainedChoiceType
 * @package App\Form
 */
class ChainedChoiceType extends AbstractType
{
    /**
     * getParent
     *
     * @return string|null
     */
    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'hillrange_chained_choice';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                // Translations Prefix
                'choice_list_prefix' => null,
                'choice_list_class' => null,
                'choice_list_method' => null,
            ]
        );
        $resolver->setRequired(
            [
                'choice_data_chain',   // The property that has the chained group data.
                'parent_type',
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new ChainedChoiceSubscriber());
    }
}