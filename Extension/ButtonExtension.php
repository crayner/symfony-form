<?php
namespace Hillrange\Form\Extension;

use Hillrange\Form\Util\ButtonManager;
use Twig\Extension\AbstractExtension;

class ButtonExtension extends AbstractExtension
{
    /**
     * @var ButtonManager
     */
	private $buttonManager;

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'button_twig_extension';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('saveButton', array($this->buttonManager, 'saveButton')),
			new \Twig_SimpleFunction('cancelButton', array($this->buttonManager, 'cancelButton')),
			new \Twig_SimpleFunction('uploadButton', array($this->buttonManager, 'uploadButton')),
			new \Twig_SimpleFunction('addButton', array($this->buttonManager, 'addButton')),
			new \Twig_SimpleFunction('editButton', array($this->buttonManager, 'editButton')),
			new \Twig_SimpleFunction('proceedButton', array($this->buttonManager, 'proceedButton')),
			new \Twig_SimpleFunction('returnButton', array($this->buttonManager, 'returnButton')),
            new \Twig_SimpleFunction('deleteButton', array($this->buttonManager, 'deleteButton')),
            new \Twig_SimpleFunction('removeButton', array($this->buttonManager, 'removeButton')),
			new \Twig_SimpleFunction('miscButton', [$this->buttonManager, 'miscButton']),
			new \Twig_SimpleFunction('resetButton', array($this->buttonManager, 'resetButton')),
			new \Twig_SimpleFunction('closeButton', array($this->buttonManager, 'closeButton')),
			new \Twig_SimpleFunction('upButton', array($this->buttonManager, 'upButton')),
			new \Twig_SimpleFunction('downButton', array($this->buttonManager, 'downButton')),
			new \Twig_SimpleFunction('onButton', array($this->buttonManager, 'onButton')),
			new \Twig_SimpleFunction('offButton', array($this->buttonManager, 'offButton')),
			new \Twig_SimpleFunction('onOffButton', array($this->buttonManager, 'onOffButton')),
			new \Twig_SimpleFunction('upDownButton', array($this->buttonManager, 'upDownButton')),
			new \Twig_SimpleFunction('toggleButton', array($this->buttonManager, 'toggleButton')),
			new \Twig_SimpleFunction('duplicateButton', array($this->buttonManager, 'duplicateButton')),
		];
	}

    /**
     * ButtonExtension constructor.
     * @param ButtonManager $buttonManager
     */
    public function __construct(ButtonManager $buttonManager)
    {
        $this->buttonManager = $buttonManager;
    }
}