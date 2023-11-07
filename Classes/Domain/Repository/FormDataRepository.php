<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Repository;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class FormDataRepository extends Repository
{

    protected $defaultOrderings = [
        'created' => \Neos\Flow\Persistence\QueryInterface::ORDER_DESCENDING
    ];

}
