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
 * Date: 26/09/2018
 * Time: 10:01
 */
namespace Hillrange\Form\Type\EventSubscriber;

use Hillrange\Form\Validator\Constraints\ColourValidator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class ColourSubscriber
 * @package Hillrange\Form\Type\EventSubscriber
 */
class ColourSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [
            FormEvents::PRE_SET_DATA => 'convertColour',
        ];
    }

    /**
     * preSetData
     *
     * @param FormEvent $event
     */
    public function convertColour(FormEvent $event)
    {
        if (empty($event->getData()))
            $event->setData('#000000');

        $event->setData(ColourValidator::isColour($event->getData()));
    }
}