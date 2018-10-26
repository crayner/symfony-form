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
 * Date: 15/10/2018
 * Time: 12:37
 */
namespace Hillrange\Form\Util;

/**
 * Interface TemplateManagerInterface
 * @package Hillrange\Form\Util
 */
interface TemplateManagerInterface
{
    /**
     * getTranslationsDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string;

    /**
     * isLocale
     *
     * @return bool
     */
    public function isLocale(): bool;

    /**
     * getTargetDivision
     *
     * @return string
     */
    public function getTargetDivision(): string;
}