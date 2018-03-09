<?php
namespace Hillrange\Form\Controller;

use Hillrange\Form\Util\CollectionManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CollectionController extends Controller
{
    /**
     * @param $parentClass
     * @param $pid
     * @param $childClass
     * @param $cid
     * @return JsonResponse
     * @Route("/hillrange/form/collection/{parentClass}/{pid}/element/{childClass}/{cid}/remove/", name="remove_collection_element")
     */
    public function removeElement($parentClass, $pid, $childClass, $cid, CollectionManager $collectionManager)
    {
        return $collectionManager->handle($parentClass, $pid, $childClass, $cid);
    }
}