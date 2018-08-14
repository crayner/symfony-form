<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\Transform\ImageToStringTransformer;
use Hillrange\Form\Type\EventSubscriber\FileSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FileType extends AbstractType
{
	/**
	 * @var FileSubscriber
	 */
	private $fileSubscriber;

	/**
	 * FileSubscriber constructor.
	 *
	 * @param FileSubscriber $fileSubscriber
	 */
	public function __construct(FileSubscriber $fileSubscriber)
	{
		$this->fileSubscriber = $fileSubscriber;
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
			]
		);

		$resolver->setRequired(
			[
				'fileName',
			]
		);
	}

	/**
	 * @return string
	 */
	public function getBlockPrefix()
	{
		return 'hillrange_file';
	}

	/**
	 * @return mixed
	 */
	public function getParent()
	{
		return \Symfony\Component\Form\Extension\Core\Type\FileType::class;
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
}