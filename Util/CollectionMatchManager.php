<?php
namespace Hillrange\Form\Util;

use Symfony\Component\Form\FormInterface;

class CollectionMatchManager
{
    /**
     * @param array $data
     * @param FormInterface $form
     * @param string $name
     * @return array
     */
    public static function matchCollectionFormData(array $data, FormInterface $form, string $name): array
    {
        uasort($data,
            function ($a, $b) use ($name) {
                return ($a['name'] < $b['name']) ? -1 : 1;
            }
        );

        $collection = $form->getData();
        $newData = [];
        $method = 'get' .ucfirst($name);

        foreach($collection->getIterator() as $k=>$item)
        {
            foreach($data as $q=>$w)
            {
                if ($w[$name] === $item->$method())
                {
                    $newData[$k] = $w;
                    unset($data[$q]);
                }
            }
        }

        foreach($data as $w)
            $newData[] = $w;

        $data = $newData;

        return $data;
    }
}