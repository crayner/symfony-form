<?php
namespace Hillrange\Form\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;

class CollectionExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('collection_widget', array($this, 'renderCollectionWidget')),
            new \Twig_SimpleFunction('collection_row', array($this, 'renderCollectionRow')),
            new \Twig_SimpleFunction('collection_script', array($this, 'renderCollectionScript')),
            new \Twig_SimpleFunction('collections_manage', array($this, 'manageCollectionsScript')),
            new \Twig_SimpleFunction('collection_class_name', array($this, 'getCollectionClassName')),
            new \Twig_SimpleFunction('collection_prefix_name', array($this, 'getCollectionPrefixName')),
        );
    }

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * CollectionExtension constructor.
     * @param \Twig_Environment $twig
     * @param RouterInterface $router
     * @param RequestStack $stack
     */
    public function __construct(\Twig_Environment $twig, RouterInterface $router, RequestStack $stack)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->stack = $stack;
    }

    /**
     * @param FormView $collection
     * @param bool $replaceContent
     * @param bool $displayScript
     * @param string $callable
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderCollectionWidget(FormView $collection, bool $replaceContent = false, bool $displayScript = true, string $callable = '')
    {
        $x = '';
        if (! $replaceContent)
            $x .= '<section id="'.$collection->vars['id'].'_target">';
        $x .= $this->twig->render('@HillrangeForm/Script/collection_widget.html.twig',
            [
                'collection'        => $collection,
                'display_script'    => $displayScript,
                'callable'          => $callable,
            ]
        );
        if ($replaceContent)
            $x .= '';
        else
            $x .= '</section>';

        return new \Twig_Markup($x, 'html');
    }

    /**
     * @param FormView $collection
     * @param bool $replaceContent
     * @param bool $displayScript
     * @param string $callable
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderCollectionRow(FormView $collection, bool $replaceContent = false, bool $displayScript = true, string $callable = '')
    {
        $x = '';
        if (! $replaceContent)
            $x .= '<section id="'.$collection->vars['id'].'_target">';
        $x .= $this->twig->render('@HillrangeForm/Script/collection_row.html.twig',
            [
                'collection'        => $collection,
                'display_script'    => $displayScript,
                'callable'          => $callable,
            ]
        );
        if ($replaceContent)
            $x .= '';
        else
            $x .= '</section>';

        return new \Twig_Markup($x, 'html');
    }

    /**
     * @param FormView $collection
     * @param string $callable
     * @return \Twig_Markup
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderCollectionScript(FormView $collection, string $callable = '')
    {
        $x = $this->twig->render('@HillrangeForm/Script/collection_script.html.twig',
            [
                'collection' => $collection,
                'callable' => $callable,
            ]
        );

        return new \Twig_Markup($x, 'html');
    }

    /**
     * @param FormView $form
     * @param array $children
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function manageCollectionsScript(FormView $form, array $children): string
    {
        $request = $this->stack->getCurrentRequest();
        $xx = '';
        $default = ["id" => $request->get("id"), "cid" => "ignore"];

        foreach($children as $child) {
            if (isset($form->children[$child]))
            {
                $collection = $form->children[$child];
                if (!empty($collection->vars['route']))
                    $xx .= "manageCollection('" . $this->router->generate($collection->vars['route'], array_merge($default, $collection->vars['route_params'])) . "','" . $collection->vars['id'] . "_target','')\n";
            }
        }

        $x = $this->twig->render('@HillrangeForm/Script/collection_manage.html.twig',
            [
                'calls' => $xx,
            ]
        );

        return new \Twig_Markup($x, 'html');
    }

    /**
     * @param FormView|string $collection
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCollectionClassName($collection): string
    {
        if ($collection instanceof FormView)
            $id = $collection->vars['id'];
        elseif (is_string($collection))
            $id = $collection;
        else {
            dump($collection);
            throw new \InvalidArgumentException('Hey programmer,  You stuffed up this one...  Must be a valid collection in a form or the collection ID.');
        }
        $id = explode('_', $id);
        $class = '';
        foreach($id as $w)
            $class .= ucfirst(strtolower($w));

        if (empty($class)) {
            dump($collection);
            throw new \InvalidArgumentException('Hey programmer,  You stuffed up this one...  Must be a valid collection in a form or the collection ID.');
        }
        return 'class'.$class;
    }

    /**
     * @param FormView|string $collection
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCollectionPrefixName($collection): string
    {
        if ($collection instanceof FormView)
            $id = $collection->vars['full_name'];
        elseif (is_string($collection))
            $id = $collection;
        else {
            dump($collection);
            throw new \InvalidArgumentException('Hey programmer,  You stuffed up this one...  Must be a valid collection in a form or the collection ID.');
        }
        return 'prefix' . ucfirst(str_replace(['[', ']'], ['_', ''], $id));
    }
}