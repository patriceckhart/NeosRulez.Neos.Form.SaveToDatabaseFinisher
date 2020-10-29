<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Controller;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

class FormDataController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Repository\FormDataRepository
     */
    protected $formDataRepository;

    /**
     * @return void
     */
    public function indexAction()
    {
        $formData = $this->formDataRepository->findAll();
        $this->view->assign('formdata', $formData);
    }

    /**
     * @param \NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Model\FormData $data
     * @return void
     */
    public function showAction($data)
    {
        $props = json_decode($data->getProps(), true);
        $created = $data->getCreated();
        $form = $data->getForm();
        $result = ['created' => $created, 'props' => $props, 'form' => $form];
        $this->view->assign('data', $result);
    }

    /**
     * @param \NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Model\FormData $data
     * @return void
     */
    public function deleteAction($data)
    {
        $this->formDataRepository->remove($data);
        $this->persistenceManager->persistAll();
        $this->redirect('index');
    }

}
