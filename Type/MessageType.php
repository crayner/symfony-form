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
 * Date: 28/05/2018
 * Time: 09:43
 */
namespace Hillrange\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessageType
 * @package Hillrange\Form\Type
 */
class MessageType extends AbstractType
{
    /**
     * getParent
     *
     * @return null|string
     */
    public function getParent()
    {
        return FormType::class;
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'help',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'hillrange_message';
    }
}