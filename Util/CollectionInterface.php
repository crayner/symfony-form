<?php
namespace Hillrange\Form\Util;

interface CollectionInterface
{
    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return string
     */
    public function getResetScripts(): string;
}