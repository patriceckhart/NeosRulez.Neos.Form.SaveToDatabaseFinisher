<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Finishers;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Form\Core\Model\AbstractFinisher;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations;

/**
 * This finisher saves the form in the database
 */
class SaveToDatabaseFinisher extends AbstractFinisher
{

    /**
     * @Flow\Inject
     * @var Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\Inject
     * @var \NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Repository\FormDataRepository
     */
    protected $formDataRepository;

    /**
     * Executes this finisher
     * @return void
     * @throws FinisherException
     * @see AbstractFinisher::execute()
     *
     */
    protected function executeInternal()
    {

        $formRuntime = $this->finisherContext->getFormRuntime();
        $formValues = $formRuntime->getFormState()->getFormValues();
        $context = $this->contextFactory->create();
        $formData = [];
        foreach ($formValues as $i => $value) {
            $node = $context->getNodeByIdentifier($i);
            $formData[] = ['key' => $node->getProperty('label'), 'value' => $value];
        }
        $json = json_encode($formData);

        $formIdentifier = $this->parseOption('formIdentifier');
        if(!$formIdentifier) {
            $formIdentifier = $formRuntime->getIdentifier();
        }

        $formData = new \NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Model\FormData();
        $formData->setForm($formIdentifier);
        $formData->setProps($json);
        $this->formDataRepository->add($formData);

    }

}
