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
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @param string $form
     * @return void
     */
    public function setForm(string $form): void
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
    public function getProps(): string
    {
        return $this->props;
    }

    /**
     * @param string $props
     * @return void
     */
    public function setProps(string $props): void
    {
        $this->props = $props;
    }

    /**
     * @var \DateTime
     */
    protected $created;


    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

}
