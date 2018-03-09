<?php
namespace Hillrange\Form\Util;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

class CollectionManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var object
     */
    private $parent;

    /**
     * @var object
     */
    private $child;

    /**
     * CollectionManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @param $parentClass
     * @param $pid
     * @param $childClass
     * @param $cid
     * @param $transDomain
     * @return JsonResponse
     */
    public function handle($parentClass, $pid, $childClass, $cid)
    {
        $class = "App\\Entity\\" . $parentClass;
        if (! class_exists($class))
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $this->translator->trans('collection.parent_class.missing', ['%{class}' => $parentClass]),
                ],
                200
            );
        $parentClass = $class;
        $class = "App\\Entity\\" . $childClass;
        if (! class_exists($class))
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $this->translator->trans('collection.child_class.missing', ['%{class}' => $childClass]),
                ],
                200
            );
        $childClass = $class;

        $this->parent = $this->entityManager->getRepository($parentClass)->find($pid);

        if (is_null($this->parent))
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $this->translator->trans('collection.parent_id.not_found', ['%{class}' => $parentClass, '%{id}' => $pid]),
                ],
                200
            );

        $this->child = $this->entityManager->getRepository($childClass)->find($cid);

        if (is_null($this->child))
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $this->translator->trans('collection.parent_id.not_found', ['%{class}' => $childClass, '%{id}' => $cid]),
                ],
                200
            );

        if (method_exists($this->child, 'canDelete'))
            if  (!$this->child->canDelete())
                return new JsonResponse(
                    [
                        'status' => 'warning',
                        'message' => $this->translator->trans('collection.child.delete.restricted', ['%{class}' => $childClass, '%{id}' => $cid]),
                    ],
                    200
                );

        $class = explode("\\", $childClass);

        $remove = 'remove' . ucfirst(array_pop($class));

        if (! method_exists($this->parent, $remove))
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $this->translator->trans('collection.parent.child.remove.missing', ['%{class}' => $parentClass, '%{remove}' => $remove]),
                ],
                200
            );

        $this->parent->$remove($this->child);

        $this->entityManager->remove($this->child);
        $this->entityManager->persist($this->parent);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'status' => 'success',
                'message' => $this->translator->trans('collection.child.removed', ['%{class}' => $this->child->__toString(), '%{id}' => $cid]),
            ],
            200
        );

    }
}