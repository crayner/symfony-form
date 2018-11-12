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
 * Date: 12/11/2018
 * Time: 14:42
 */
namespace Hillrange\Form\Doctrine\Extension\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

/**
 * Class TimezoneDateTimeType
 * @package Hillrange\Form\Doctrine\Extension\Type
 */
class TimezoneDateTimeType extends DateTimeType
{
    /**
     * convertToDatabaseValue
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \DateTime) {
            $value->setTimezone($this->getDateTimezone());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * convertToPHPValue
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return bool|\DateTime|false|mixed
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof \DateTime) {
            return $value;
        }

        $converted = \DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            $this->getDateTimezone()
        );

        if (! $converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }
        return $converted;
    }

    /**
     * getTimezone
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return date_default_timezone_get();
    }

    /**
     * getDateTimezone
     *
     * @return \DateTimeZone
     */
    private function getDateTimezone(): \DateTimeZone
    {
        return new \DateTimeZone($this->getTimezone());
    }
}
