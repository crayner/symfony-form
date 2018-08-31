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
 * Date: 28/08/2018
 * Time: 11:52
 */
namespace Hillrange\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class AutoCompleteType
 * @package App\Form\Type
 */
class AutoCompleteType extends AbstractType
{
    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'auto_complete';
    }

    /**
     * getParent
     *
     * @return null|string
     */
    public function getParent()
    {
        return EntityType::class;
    }

}