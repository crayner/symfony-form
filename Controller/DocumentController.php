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
 * Date: 7/09/2018
 * Time: 11:38
 */
namespace Hillrange\Form\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 * @package Hillrange\Form\Controller
 */
class DocumentController extends AbstractController
{
    /**
     * downloadFile
     *
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @Route("/document/{fileName}/download/", name="document_download")
     */
    public function downloadFile(string $fileName)
    {
        return $this->file(rawurldecode($fileName), null, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
