<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 29/10/2018
 * Time: 14:44
 */
namespace Hillrange\Form\Type\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class ChildParentSubscriber
 * @package Hillrange\Form\Type\EventSubscriber
 */
class ChildParentSubscriber implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    /**
     * @var int
     */
    private $id;

    /**
     * ChildParentSubscriber constructor.
     * @param int|null $id
     */
    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    /**
     * preSubmit
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $event->setData(intval($this->id));
    }
}