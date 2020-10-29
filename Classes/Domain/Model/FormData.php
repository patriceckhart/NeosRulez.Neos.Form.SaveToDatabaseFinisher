<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Model;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class FormData
{

    /**
     * @var string
     */
    protected $form;

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param string $form
     * @return void
     */
    public function setForm($form)
    {
        $this->form = $form;
    }
    
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $props;

    /**
     * @return string
     */
    public function getProps()
    {
        return $this->props;
    }

    /**
     * @param string $props
     * @return void
     */
    public function setProps($props)
    {
        $this->props = $props;
    }

    /**
     * @var \DateTime
     */
    protected $created;


    public function __construct() {
        $this->created = new \DateTime();
    }

    /**
     * @return string
     */
    public function getCreated() {
        return $this->created;
    }

}
