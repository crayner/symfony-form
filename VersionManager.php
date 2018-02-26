<?php
namespace Hillrange\Form;

class VersionManager
{
    const VERSION = '0.0.01';

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return VersionManager::VERSION;
    }
}