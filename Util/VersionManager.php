<?php
namespace Hillrange\Form\Util;

class VersionManager
{
    const VERSION = '0.0.04';

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return VersionManager::VERSION;
    }
}