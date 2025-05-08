<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Finishers;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Form\Core\Model\AbstractFinisher;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations;
use Neos\Form\Exception\FinisherException;
use NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Model\FormData;
use NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Repository\FormDataRepository;

/**
 * This finisher saves the form in the database
 */
class SaveToDatabaseFinisher extends AbstractFinisher
{

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\Inject
     * @var FormDataRepository
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
        $unrealIdentifiers = [];

        $node = $this->parseOption('node');
        $inputs = (new FlowQuery(array($node)))->find('[instanceof Neos.Form.Builder:FormElement]')->context(array('workspaceName' => 'live'))->sort('_index', 'ASC')->filter('[label != false]')->get();
        foreach ($inputs as $input) {
            if ($input->hasProperty('identifier')) {
                $unrealIdentifiers[$input->getProperty('identifier')] = $input->getIdentifier();
            }
        }

        foreach ($formValues as $i => $value) {
            if (array_key_exists($i, $unrealIdentifiers)) {
                $node = $context->getNodeByIdentifier($unrealIdentifiers[$i]);
            } else {
                $node = $context->getNodeByIdentifier($i);
            }
            $formData[] = ['key' => $node->getProperty('label'), 'value' => $value];
        }

        $json = json_encode($formData);

        $formIdentifier = $this->parseOption('formIdentifier');
        if (!$formIdentifier) {
            $formIdentifier = $formRuntime->getIdentifier();
        }

        $formData = new FormData();
        $formData->setForm($formIdentifier);
        $formData->setProps($json);
        $this->formDataRepository->add($formData);

    }

}
