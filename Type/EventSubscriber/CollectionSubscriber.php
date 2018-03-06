<?php
namespace Hillrange\Form\Type\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
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
     * @var string
     */
    private $name;

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

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $parentData = $event->getForm()->getParent()->getData();
        $this->name = ucfirst($event->getForm()->getConfig()->getName());
        $getName = 'get' . ucfirst($event->getForm()->getConfig()->getName());

        if ($this->options['sequence_manage'] === true) {
            $data = $this->manageSequence($data);
            $data = $this->reOrderForm($data, $parentData->$getName());
        }
        if ($this->options['remove_manage'] === true) {
            $q = [];
            foreach ($data as $w)
                $q[] = $w[$this->options['remove_key']];

            $func = 'get' . $this->options['remove_key'];
            $remove = $parentData->$getName()->filter(function($entry) use ($q, $func) {
                    return ! in_array($entry->$func(), $q);
                }
            );
            foreach($remove->getIterator() as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }

        $event->setData($data);
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
        $s = 0;
        foreach($data as $q=>$w)
            if (isset($w['sequence']) && $w['sequence'] > $s)
                $s = $w['sequence'];

        $s = $s > 100 ? 1 : 101 ;

        foreach($data as $q=>$w)
            if (isset($w['sequence']))
                $data[$q]['sequence'] = $s++;

        return $data;
    }

    /**
     * @param $data
     * @param $collection
     * @return array
     * @throws \Exception
     */
    private function reOrderForm($data, $collection)
    {
        if (! is_iterable($collection))
            throw new \Exception('The form data must be a Collection for ' . $this->name);

        $result = [];

        $func = 'get' . $this->options['remove_key'];

        foreach($collection->getIterator() as $q=>$entity)
        {
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
}