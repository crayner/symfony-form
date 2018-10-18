<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\EventSubscriber\CollectionSubscriber;
use Hillrange\Form\Util\ButtonManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    /**
     * @var CollectionSubscriber
     */
    private $collectionSubscriber;

    /**
     * @var ButtonManager
     */
    private $buttonManager;
    /**
     * CollectionType constructor.
     * @param CollectionSubscriber $collectionSubscriber
     */
    public function __construct(CollectionSubscriber $collectionSubscriber, ButtonManager $buttonManager)
    {
        $this->collectionSubscriber = $collectionSubscriber;
        $this->buttonManager = $buttonManager;
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
                'unique_key'            => 'id',
                'sort_manage'           => false,
                'allow_up'              => false,
                'allow_down'            => false,
                'allow_duplicate'       => false,
                'route'                 => '',
                'redirect_route'        => '',
                'route_params'          => [],
                'display_script'        => false,
                'add_button'            => '',
                'remove_button'         => '',
                'up_button'             => '',
                'down_button'           => '',
                'duplicate_button'      => '',
                'button_merge_class'    => '',
                'removal_warning'       => null,
                'remove_element_route'  => null,
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\CollectionType::class;
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'hillrange_' . parent::getBlockPrefix();
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['button_merge_class'])
        {
            if (empty($options['add_button']))
                $options['add_button'] = $this->buttonManager->addButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['remove_button']))
                $options['remove_button'] = $this->buttonManager->removeButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['up_button']))
                $options['up_button'] = $this->buttonManager->upButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['down_button']))
                $options['down_button'] = $this->buttonManager->downButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['duplicate_button']))
                $options['duplicate_button'] = $this->buttonManager->duplicateButton(['mergeClass' => $options['button_merge_class']]);
        }

        $view->vars['allow_up']             = $options['sort_manage'];
        $view->vars['allow_down']           = $options['sort_manage'];
        $view->vars['allow_duplicate']      = $options['allow_duplicate'];
        $view->vars['unique_key']           = $options['unique_key'];
        $view->vars['route']                = $options['route'];
        $view->vars['redirect_route']       = $options['redirect_route'];
        $view->vars['route_params']         = $options['route_params'];
        $view->vars['display_script']       = $options['display_script'];
        $view->vars['add_button']           = $options['add_button'];
        $view->vars['remove_button']        = $options['remove_button'];
        $view->vars['up_button']            = $options['up_button'];
        $view->vars['down_button']          = $options['down_button'];
        $view->vars['removal_warning']      = $options['removal_warning'];
        $view->vars['allow_add']            = $options['allow_add'];
        $view->vars['allow_delete']         = $options['allow_delete'];
        $view->vars['remove_element_route'] = $options['remove_element_route'];
        $view->vars['button_merge_class']   = $options['button_merge_class'];
    }
}