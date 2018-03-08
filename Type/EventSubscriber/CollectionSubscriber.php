<?php
namespace Hillrange\Form\Type\EventSubscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CollectionSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * CollectionSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [
            FormEvents::PRE_SUBMIT   => 'preSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();

        $parentData = $event->getForm()->getParent()->getData();
        $getName = 'get' . ucfirst($event->getForm()->getConfig()->getName());
        $setName = 'set' . ucfirst($event->getForm()->getConfig()->getName());
        $this->setCollection($parentData->$getName());
        if ($this->getOption('sequence_manage') === true)
        {
            $data = $this->manageSequence($data);
            $event->setData($data);
        }

        if ($this->getOption('remove_manage') === true) {
            if (empty($data) && $this->getCollection()->count() == 0)
                return ;

            if (! empty($data)) {
                $q = [];
                if (false === $this->getOption('remove_key')) {
                    $q = $data;
                    $func = 'getId';
                } else {
                    foreach ($data as $w)
                        $q[] = $w[$this->getOption('remove_key')];
                    $func = 'get' . $this->getOption('remove_key');
                }

                $remove =  $this->getCollection()->filter(function ($entry) use ($q, $func)
                    {
                        return !in_array($entry->$func(), $q);
                    }
                );
            } else
                $remove = $this->getCollection();

            foreach($remove->getIterator() as $entity)
            {
                $this->getCollection()->removeElement($entity);
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();

            $parentData->$setName($this->getCollection());

            $event->getForm()->getParent()->setData($parentData);
        }
    }

    /**
     * @param array $options
     * @return CollectionSubscriber
     */
    public function setOptions(array $options): CollectionSubscriber
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param $data
     * @return array|null
     */
    private function manageSequence($data): ?array
    {
        if (empty($data))
            return null;

        $needOrder = false;
        $s = 0;
        foreach($data as $q=>$w)
            if (! empty($w['sequence']) && $w['sequence'] > $s)
                $s = $w['sequence'];
            else
                $needOrder = true;

        if ($needOrder) {
            $s = $s > 500 ? 1 : 501;

            foreach ($data as $q => $w)
                if (isset($w['sequence']))
                    $data[$q]['sequence'] = strval($s++);

            return $this->reOrderForm($data);
        }
        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    private function reOrderForm($data): ?array
    {
        if (empty($data))
            return null;

        if ($this->getCollection()->count() === 0)
            return $data;

        $result = [];

        $func = 'get' . $this->options['remove_key'];

        foreach($this->getCollection()->getIterator() as $q=>$entity)
        {
            if (! empty($data))
                foreach($data as $w)
                {
                    if ($entity->$func() == $w[$this->options['remove_key']])
                    {
                        $result[$q] = $w;
                        break ;
                    }
                }
        }

        return $result;
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        if (empty($this->collection))
            $this->collection = new ArrayCollection();

        if ($this->collection instanceof PersistentCollection && ! $this->collection->isInitialized())
            $this->collection->initialize();

        return $this->collection;
    }

    /**
     * @param Collection $collection
     * @return CollectionSubscriber
     */
    public function setCollection(Collection $collection): CollectionSubscriber
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if (empty($this->options))
            $this->options = [];
        return $this->options;
    }

    /**
     * @return mixed
     */
    public function getOption($name)
    {
        if (isset($this->options[$name]))
            return $this->options[$name];
        return null;
    }
}