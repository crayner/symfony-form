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
    const VERSION = '0.3.26';

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