<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\EventSubscriber\EnumSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{
    /**
     * @return null|string
     */
    public function getParent()
    {
        return ChoiceType::class;
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
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'hillrange_enum_choice';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new EnumSubscriber());
    }
}