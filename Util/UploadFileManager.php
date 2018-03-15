<?php
namespace Hillrange\Form\Util;

use App\Core\Manager\SettingManager;
use Symfony\Component\Filesystem\Filesystem;

class UploadFileManager extends Filesystem
{
    /**
     * @var string
     */
    private $uploadPath;

    /**
     * UploadFileManager constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->setUploadPath($settingManager->getParameter('upload_path', 'uploads'));
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
}