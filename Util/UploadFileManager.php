<?php
namespace Hillrange\Form\Util;

use Symfony\Component\Filesystem\Filesystem;

class UploadFileManager extends Filesystem
{
    /**
     * @var string
     */
    private $uploadPath;

    /**
     * @var string
     */
    private $fileName;

    /**
     * UploadFileManager constructor.
     * @param string $uploadPath
     */
    public function __construct(string $uploadPath)
    {
        $this->setUploadPath($uploadPath ?: 'uploads');
    }

    /**
     * @return string
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @param string $uploadPath
     * @return UploadFileManager
     */
    public function setUploadPath(string $uploadPath): UploadFileManager
    {
        $this->uploadPath = $uploadPath;

        return $this;
    }

    /**
     * @param string $core
     * @param string $type
     * @return string
     */
    public function createFileName(string $core, string $type): string
    {
        $this->setFileName($core . '_' . mb_substr(md5(uniqid()), mb_strlen($core) + 1) . '.' . $type);

        return $this->getFileName();
    }

    /**
     * @param string $fileName
     * @return UploadFileManager
     */
    public function setFileName(string $fileName): UploadFileManager
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}