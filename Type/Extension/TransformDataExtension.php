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
    public function transformData($data): string
    {
        if (is_object($data))
            if (method_exists($data, 'getId'))
                return strval($data->getId());
            else
                return '';

        return empty($data) ? '' : strval($data);
    }
}