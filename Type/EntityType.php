<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\Transform\EntityToStringTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityType
 * @package Hillrange\Form\Type
 */
class EntityType extends AbstractType
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

    /**
     * EntityType constructor.
     * @param EntityManagerInterface $manager
     */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->addModelTransformer(new EntityToStringTransformer($this->manager, $options));
	}

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
	public function getBlockPrefix()
	{
		return 'hillrange_entity';
	}

    /**
     * getParent
     *
     * @return null|string
     */
	public function getParent()
	{
		return \Symfony\Bridge\Doctrine\Form\Type\EntityType::class;
	}

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'expanded_attr' => [],
                'justify' => 'right',
            ]
        );
    }

    /**
     * buildView
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['expanded_attr'] = $options['expanded_attr'];
        $view->vars['justify'] = $options['justify'];
    }
}