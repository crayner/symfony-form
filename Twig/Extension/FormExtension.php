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
 * Date: 30/08/2018
 * Time: 16:29
 */
namespace Hillrange\Form\Twig\Extension;

use Hillrange\Form\Util\FormManager;
use Twig\Extension\AbstractExtension;

class FormExtension extends AbstractExtension
{

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return 'form_extension';
    }

    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('formTranslations', array($this, 'formTranslations')),
            new \Twig_SimpleFunction('renderForm', [$this->formManager, 'renderForm'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * formTranslations
     *
     * @return array
     */
    public function formTranslations(): array
    {
        return [
            'form.required',
        ];
    }

    /**
     * @var FormManager
     */
    private $formManager;

    /**
     * FormExtension constructor.
     * @param FormManager $formManager
     */
    public function __construct(FormManager $formManager)
    {
        $this->formManager = $formManager;
    }
}