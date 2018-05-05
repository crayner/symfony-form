<?php
namespace Hillrange\Form\Util;

interface CollectionInterface
{
    /**
     * Get the class of the Parent to the Collection
     *
     * @return string
     */
    public function getParentClass(): string;

    /**
     * get the class of the Child to the Collection
     *
     * @return string
     */
    public function getChildClass(): string;

    /**
     * get the name of the method in the parent class to remove the child.
     * The method will need to reciprocate the removal on the child if necessary.
     *
     * @return string
     */
    public function removeChildMethod(): string;

    /**
     * Delete the child from the database.
     *
     * @return bool
     */
    public function deleteChild(): bool;
}