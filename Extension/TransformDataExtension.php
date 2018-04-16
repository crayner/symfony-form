<?php
namespace Hillrange\Form\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;

class TransformDataExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ViewExtension constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('transformData', [$this, 'transformData']),
            new \Twig_SimpleFunction('getObject', [$this, 'getObject']),
            new \Twig_SimpleFunction('getObjectId', [$this, 'getObjectId']),
            new \Twig_SimpleFunction('getValueData', [$this, 'getValueData']),
        ];
    }

    /**
     * @param $data
     * @return string
     */
    public function transformData($data, $unique_key = 'id'): string
    {
        if (is_object($data) && $data instanceof FormView)
            $data = $data->vars;

        if (is_array($data)) {
            if (! empty($data['value']) && is_string($data['value']))
                return strval($data['value']);
            if (! empty($data['value']))
                $data = $data['value'];
            if (is_array($data)) {
                if (!empty($data['data']))
                    $data = $data['data'];
                else
                    return '';
            }
        }

        if (is_object($data)) {
            $method = 'get' .ucfirst($unique_key);
            if (method_exists($data, $method))
                return strval($data->$method());
            else
                return '';
        }

        return empty($data) ? '' : strval($data);
    }

    /**
     * @param FormView $form
     * @return object|null
     */
    public function getObject(FormView $form)
    {
        if (is_object($form->vars['value']))
            return $form->vars['value'];
        if (is_object($form->vars['data']))
            return $form->vars['data'];
        return null;
    }

    /**
     * @param FormView $form
     * @param string $name
     * @return mixed
     */
    public function getObjectId(FormView $form, $name = 'id')
    {
        $object = $this->getObject($form);

        $func = 'get' . ucfirst($name);

        if ( is_object($object) && method_exists($object, $func))
            return $object->$func();

        return '0';
    }

    /**
     * @param FormView $form
     * @return mixed
     */
    public function getValueData(FormView $form)
    {
        if (empty($form->vars['value']))
            return $form->vars['data'];

        return $form->vars['value'];
    }
}