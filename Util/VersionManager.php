<?php
namespace Hillrange\Form\Util;

class VersionManager
{
    const VERSION = '0.1.65';

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return VersionManager::VERSION;
    }
}