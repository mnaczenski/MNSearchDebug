<?php

namespace MNSearchDebug\Bundle\SearchBundleDBAL\ConditionHandler;

use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandler\SearchTermConditionHandler;
use MNSearchDebug\Bundle\SearchBundle\Condition\DebugSearchTermCondition;

class DebugSearchTermConditionHandler extends SearchTermConditionHandler
{
    /**
     * {@inheritdoc}
     */
    public function supportsCondition(ConditionInterface $condition)
    {
        return $condition instanceof DebugSearchTermCondition;
    }
}
