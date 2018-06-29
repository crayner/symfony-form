<?php
namespace Hillrange\Form\Util;

/**
 * Class VersionManager
 * @package Hillrange\Form\Util
 */
class VersionManager
{
    /**
     * String
     */
    const VERSION = '0.1.89';

    /**
     * getVersion
     *
     * @return string
     */
    public function getVersion(): string
    {
        return VersionManager::VERSION;
    }
}