<?php
namespace Hillrange\Form\Util;

/**
 * Class ScriptManager
 * @package App\Manager
 */
class ScriptManager
{
    /**
     * @var array
     */
    private static $scripts;

    /**
     * @var array
     */
    private static $options;

    /**
     * @return array
     */
    public static function getScripts(): array
    {
        return self::$scripts ?: [];
    }

    /**
     * @param array $scripts
     */
    public static function setScripts(array $scripts)
    {
        self::$scripts = $scripts;
    }

    /**
     * addScript
     *
     * @param string $name
     * @param array $options
     */
    public static function addScript(string $name, array $options = [])
    {
        if (! in_array($name, self::getScripts()))
            self::$scripts[] = $name;
        self::setOptions($name, $options);
    }

    /**
     * getOptions
     *
     * @param string $name
     * @return array
     */
    public static function getOptions(string $name): array
    {
        if (isset(self::$options) && is_array(self::$options))
            return self::$options[$name];
        return [];
    }

    /**
     * setOptions
     *
     * @param string $name
     * @param array $options
     */
    public static function setOptions(string $name, array $options)
    {
        if (isset(self::$options[$name]))
        {
            foreach(self::$options[$name] as $item)
                if ($item === $options)
                    return ;
        }
        self::$options[$name][] = $options;
    }
}