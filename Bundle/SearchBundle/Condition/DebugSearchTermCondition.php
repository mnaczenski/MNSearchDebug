<?php

namespace MNSearchDebug\Bundle\SearchBundle\Condition;

use Shopware\Bundle\SearchBundle\Condition\SearchTermCondition;

class DebugSearchTermCondition extends SearchTermCondition
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'debug_search';
    }
}
