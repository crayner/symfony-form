<?php
namespace Hillrange\Form\Type\Transform;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class EntityToStringTransformer implements DataTransformerInterface
{
	/**
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	private $om;

    /**
     * @var string
     */
	private $entityClass;

    /**
     * @var
     */
	private $entityType;

    /**
     * @var ServiceEntityRepositoryInterface
     */
	private $entityRepository;

	/**
	 * @var bool
	 */
	private $multiple;

	/**
	 * @param ObjectManager $om
	 */
	public function __construct(EntityManagerInterface $om, array $options)
	{
		$this->om = $om;
		$this->setEntityClass($options['class']);
		$this->setMultiple($options['multiple']);
	}

    /**
     * @param $entityClass
     */
    public function setEntityClass($entityClass)
	{
		$this->entityClass = $entityClass;
		$this->setEntityRepository($entityClass);
	}

    /**
     * @param $entityClass
     */
    public function setEntityRepository($entityClass)
	{
		$this->entityRepository = $this->om->getRepository($entityClass);
	}

	/**
	 * @param mixed $entity
	 *
	 * @return string|array
	 */
	public function transform($entity)
	{
        if (!$this->isMultiple())
		{
			if (is_null($entity) || ! $entity instanceof $this->entityClass)
			{
				return '';
			}

			return strval($entity->getId());
		}

		if (is_array($entity))
		    $entity = new ArrayCollection($entity);
		if (is_iterable($entity))
			if ($entity->count() == 0)
				return [];
			else
			{
				return $entity->toArray();
			}


		if (is_object($entity) && $entity instanceof $this->entityClass)
		    return $entity->getId();

        if (is_null($entity))
            return null;

		throw new \Exception('What to do with: ' . json_encode($entity) . ' for class ' . $this->entityClass);
	}

	/**
	 * @param mixed $id
	 *
	 * @throws \Symfony\Component\Form\Exception\TransformationFailedException
	 *
	 * @return mixed|object
	 */
	public function reverseTransform($id)
	{
        if (!$id || $id === 'Add' || empty($id))
		{
			return null;
		}

		if (!is_array($id))
		{

			$entity = $this->entityRepository->find($id);
			if (null === $entity)
			{
				throw new TransformationFailedException(
					sprintf(
						'A %s with id "%s" does not exist!',
						$this->entityType,
						$id
					)
				);
			}

			return $entity;
		}

		return $id;
	}

	public function setEntityType($entityType)
	{
		$this->entityType = $entityType;
	}

	/**
	 * @return bool
	 */
	public function isMultiple(): bool
	{
		return $this->multiple;
	}

	/**
	 * @param bool $multiple
	 *
	 * @return EntityToStringTransformer
	 */
	public function setMultiple(bool $multiple): EntityToStringTransformer
	{
		$this->multiple = $multiple;

		return $this;
	}

    /**
     * @return null|string
     */
    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }
}