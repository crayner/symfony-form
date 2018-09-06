<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\Transform\ImageToStringTransformer;
use Hillrange\Form\Type\EventSubscriber\FileSubscriber;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Routing\RouterInterface;

class ImageType extends AbstractType
{
	/**
	 * @var FileSubscriber
	 */
	private $fileSubscriber;

	/**
     * @var RouterInterface
     */
	private $router;

	/**
	 * FileSubscriber constructor.
	 *
	 * @param FileSubscriber $fileSubscriber
	 */
	public function __construct(FileSubscriber $fileSubscriber, RouterInterface $router)
	{
		$this->fileSubscriber = $fileSubscriber;
        $this->router = $router;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'compound'     => false,
				'multiple'     => false,
				'type'         => 'file',
				'deleteTarget' => '_self',
				'deleteParams' => null,
                'imageClass'   => null,
			]
		);

		$resolver->setRequired(
			[
				'deletePhoto',
				'fileName',
			]
		);
	}

	/**
	 * @return string
	 */
	public function getBlockPrefix()
	{
		return 'image';
	}

	/**
	 * @return mixed
	 */
	public function getParent()
	{
		return FileType::class;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new ImageToStringTransformer());
		$builder->addEventSubscriber($this->fileSubscriber);

	}

	/**
	 * @param FormView      $view
	 * @param FormInterface $form
	 * @param array         $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
	    if (is_array($options['deletePhoto'])) {
            $view->vars['deletePhoto'] = $this->router->getGenerator()->generate($options['deletePhoto'][0], is_array($options['deletePhoto'][1]) ? $options['deletePhoto'][1]: []);
        } else
		    $view->vars['deletePhoto']  = $options['deletePhoto'];
		$view->vars['deleteTarget'] = $options['deleteTarget'];
        $view->vars['deleteParams'] = $options['deleteParams'];
        $view->vars['imageClass']   = $options['imageClass'];
	}
}