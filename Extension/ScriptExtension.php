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
 * Time: 22:20
 */
namespace Hillrange\Form\Extension;

use Hillrange\Form\Util\ScriptManager;
use Twig\Extension\AbstractExtension;

/**
 * Class ScriptExtension
 * @package Hillrange\Form\Extension
 */
class ScriptExtension extends AbstractExtension
{
    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('addScript', array($this, 'addScript')),
            new \Twig_SimpleFunction('getScripts', array($this, 'getScripts')),
            new \Twig_SimpleFunction('getScriptOptions', array($this, 'getScriptOptions')),
        ];
    }

    /**
     * getScripts
     *
     * @param $name
     * @return array
     */
    public function getScripts()
    {
        return ScriptManager::getScripts();
    }

    /**
     * getScriptOptions
     *
     * @param string $name
     * @return array
     */
    public function getScriptOptions(string $name)
    {
        return ScriptManager::getOptions($name);
    }

    /**
     * addScript
     *
     * @param string $name
     * @param array $options
     * @return string
     */
    public function addScript(string $name, array $options = [])
    {
        ScriptManager::addScript($name, $options);
        return '';
    }
}