<?php
namespace Hillrange\Form\Type\EventSubscriber;

use Hillrange\Form\Validator\Enum;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Choice;

class EnumSubscriber implements EventSubscriberInterface
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
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $options = $form->getConfig()->getOptions();
        $name    = $form->getName();

        $className = $options['choice_list_class'];
        $method = $options['choice_list_method'];
        $class = new $className();
        $raw = $class->$method();

        $choices = [];
        $validation = [];
        foreach($raw as $q=>$w)
            if (is_array($w))
            {
                $e = [];
                foreach($w as $r)
                    $e[strtolower($options['choice_list_prefix'].'.'.$r)] = $r;
                $choices[strtolower($options['choice_list_prefix'].'.'.$q)] = $e;
                $validation = array_merge($validation, $e);
            }
            else {
                $choices[strtolower($options['choice_list_prefix'] . '.' . $w)] = $w;
                $validation[] = $w;
            }
        $options['choices'] = $choices;

        unset($options['choice_list_class'], $options['choice_list_method'], $options['choice_list_prefix']);

        $options['constraints'] = [
            new Choice(['choices' => $validation])
        ];

        //Replace the existing form element.
        $form->getParent()->add($name, ChoiceType::class, $options);
    }
}