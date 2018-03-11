<?php
namespace Hillrange\Form\Type\Extension;

use Doctrine\ORM\EntityManagerInterface;
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
        ];
    }

    /**
     * @param $data
     * @return string
     */
    public function transformData($data, $unique_key): string
    {
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
}