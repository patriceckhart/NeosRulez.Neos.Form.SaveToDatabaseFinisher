<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Repository;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class FormDataRepository extends Repository
{

    protected $defaultOrderings = [
        'created' => QueryInterface::ORDER_DESCENDING
    ];

}
