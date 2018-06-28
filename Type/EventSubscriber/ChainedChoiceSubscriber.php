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
 * Date: 27/06/2018
 * Time: 22:50
 */
namespace Hillrange\Form\Type\EventSubscriber;

use Hillrange\Form\Util\ScriptManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class ChainedChoiceSubscriber
 * @package Hillrange\Form\Type\EventSubscriber
 */
class ChainedChoiceSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * preSetData
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $options = $form->getConfig()->getOptions();
        $name    = $form->getName();
        $className = $options['choice_list_class'];
        $method = $options['choice_list_method'];
        $parent = $options['choice_data_chain'];

        $parentForm = $form->getParent()->get($parent);
        $parentOptions = $parentForm->getConfig()->getOptions();

        $baseId = '';
        $ff = clone $form;
        while ($ff->getParent() instanceof FormInterface)
        {
            $ff = $ff->getParent();
            $baseId = $ff->getName() . '_' . $baseId;
        }
        $baseId = trim($baseId, '_');

        $parentId = $baseId . '_'. $parentForm->getName();
        $childId = $baseId . '_'. $name;

        $parentType = $options['parent_type'];
        $list = $className::$method();

        $values = [];
        $choicesAttr = [];

        foreach($list as $optGroup=>$items) {
            foreach ($items as $item) {
                $values[$optGroup][$item['label']] = $item['value'];
                $choicesAttr[$item['value']] = ['data-chained' => $item['data-chained']];
            }
        }

        $options['choices'] = $values;
        $options['choice_attr'] = function($choiceValue, $key, $value) use ($choicesAttr) {
            // adds a class like attending_yes, attending_no, etc
            return $choicesAttr[$choiceValue];
        };
        unset($options['choice_list_class'], $options['choice_list_method'], $options['choice_list_prefix'], $options['choice_data_chain'], $options['parent_type']);

        ScriptManager::addScript('@HillrangeForm/Script/chained_choice.html.twig');
        ScriptManager::addScript('@HillrangeForm/Script/chained_script.html.twig', ['parentId' => $parentId, 'childId' => $childId]);

        $form->getParent()->add($parent, $parentType, $parentOptions);
        $form->getParent()->add($name, ChoiceType::class, $options);
    }
}