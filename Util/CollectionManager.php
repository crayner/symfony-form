<?php
namespace Hillrange\Form\Util;

use App\Core\Manager\MessageManager;
use Doctrine\ORM\EntityManagerInterface;

abstract class CollectionManager implements CollectionInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array;
     */
    private $status;

    /**
     * CollectionManager constructor.
     * @param MessageManager $messageManager
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @param int $cid
     * @return bool
     */
    public function removeCollectionChild(int $id, int $cid): bool
    {
        $parent = $this->getEntityManager()->getRepository($this->getParentClass())->find($id);
        $name = $this->getParentClass();
        if (! $parent instanceof $name)
        {
            $this->addStatus('danger', 'The parent entity for the collection was not found!', ['%{name}' => $name]);
            return false;
        }

        $child =  $this->getEntityManager()->getRepository($this->getChildClass())->find($cid);
        $name = $this->getChildClass();
        if (! $child instanceof $name)
        {
            $this->addStatus('danger', 'The child entity for the collection was not found!', ['%{name}' => $name]);
            return false;
        }

        $method = $this->removeChildMethod();
        if (! method_exists($parent, $method))
        {
            $this->addStatus('danger', 'The parent entity does not have a remove child method.', ['%{class}' => $this->getParentClass(), '%{method}' => $method]);
            return false;
        }

        try {
            $parent->$method($child);
            if ($this->deleteChild())
                $this->getEntityManager()->remove($child);

            $this->getEntityManager()->persist($parent);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $this->addStatus('danger', 'A problem occurred removing the child from the parent.', ['%{message}' => $e->getMessage()]);
            return false;
        }
        $this->addStatus('success', 'The collection child was removed successfully.');
        return true;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return array
     */
    public function getStatus(): array
    {
        if (empty($this->status))
            $this->status = [];

        return $this->status;
    }

    /**
     * @param string $level         The level of the error (success, warning, danger,
     * @param string $message       The error message
     * @param array $options        The translation options for the error message
     * @return CollectionManager
     */
    public function addStatus(string $level, string $message, array $options = []): CollectionManager
    {
        $status                 = new \stdClass();
        $status->level          = $level;
        $status->options        = $options;
        $status->message        = $message;
        $this->getStatus();
        $this->status[]         = $status;
        return $this;
    }
}