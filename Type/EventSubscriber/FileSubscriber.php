<?php
namespace Hillrange\Form\Type\EventSubscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSubscriber implements EventSubscriberInterface
{
	/**
	 * @var string
	 */
	private $targetDir;

    /**
     * @param string $targetDir
     */
    public function setTargetDir(string $targetDir = 'uploads')
	{
		$this->targetDir = $targetDir;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		// Tells the dispatcher that you want to listen on the form.pre_set_data
		// event and that the preSetData method should be called.
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::PRE_SUBMIT   => 'preSubmit',
		);
	}


	/**
	 * @param FormEvent $event
	 */
	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();

		if (!empty($data) && !file_exists($data))
		{
			$data = null;
			$event->setData($data);
		}

	}
	/**
	 * @param FormEvent $event
	 */
	public function preSubmit(FormEvent $event)
	{
		$form = $event->getForm();
		$data = $event->getData();

		if ($data instanceof UploadedFile)
		{

		    $extension = $data->guessExtension();
		    $original = pathinfo($data->getClientOriginalExtension());

            $original = $original["filename"];

		    if (strtolower($extension) === 'mpga' && strtolower($original) === 'mp3' )
		        $extension = 'mp3';

			$fName = $form->getConfig()->getOption('fileName') . '_' . mb_substr(md5(uniqid()), mb_strlen($form->getConfig()->getOption('fileName')) + 1) . '.' . $extension;

            $path = $this->targetDir;
			$data->move($path, $fName);

			$file = new File($path . DIRECTORY_SEPARATOR . $fName, true);

			$data = $file->getPathName();

			if (!empty($form->getData()) && file_exists($form->getData()))
				if (0 === strpos($form->getData(), 'uploads/'))
					unlink($form->getData());
		}

		if (!empty($form->getData()) && empty($data))
			$data = $form->getData();

		$event->setData($data);
	}
}