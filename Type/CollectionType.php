<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\EventSubscriber\CollectionSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    /**
     * @var CollectionSubscriber
     */
    private $collectionSubscriber;

    /**
     * CollectionType constructor.
     * @param CollectionSubscriber $collectionSubscriber
     */
    public function __construct(CollectionSubscriber $collectionSubscriber)
    {
        $this->collectionSubscriber = $collectionSubscriber;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $x = clone $this->collectionSubscriber;
        $x->setOptions($options);
        $builder->addEventSubscriber($x);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'sequence_manage' => false,
                'remove_manage' => false,
                'remove_key' => 'id',
            ]
        );
    }
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'hillrange_' . parent::getBlockPrefix();
    }
}