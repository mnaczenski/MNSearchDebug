<?php

namespace MNSearchDebug\Bundle\SearchBundleDBAL\SearchTerm;

use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\Bundle\SearchBundleDBAL\SearchTermQueryBuilderInterface;

class DebugSearchTermQueryBuilder implements SearchTermQueryBuilderInterface
{
    /**
     * @var SearchTermQueryBuilderInterface
     */
    private $coreSearchTermQueryBuilder;

    /**
     * @param SearchTermQueryBuilderInterface $searchTermQueryBuilder
     */
    public function __construct(SearchTermQueryBuilderInterface $searchTermQueryBuilder)
    {
        $this->coreSearchTermQueryBuilder = $searchTermQueryBuilder;
    }

    /**
     * decorates the buildQuery method to add debug information
     *
     * {@inheritdoc}
     */
    public function buildQuery($term)
    {
        $searchTermQueryBuilder = $this->coreSearchTermQueryBuilder->buildQuery($term);

        if ($searchTermQueryBuilder === null) {
            return $searchTermQueryBuilder;
        }

        $this->addDebugKeywords($searchTermQueryBuilder);

        $searchTermQueryBuilder->addSelect('a.topseller as isTopSeller');

        return $searchTermQueryBuilder;
    }

    /**
     * @param QueryBuilder $searchTermQueryBuilder
     */
    private function addDebugKeywords(QueryBuilder $searchTermQueryBuilder)
    {
        $relevanceSelect = 'SUM(srd.relevance) as relevance';
        $additionalSelect = 'GROUP_CONCAT(srd.keywordID) as keywords, GROUP_CONCAT(srd.relevance) as relevances';
        $fromPart = $searchTermQueryBuilder->getQueryPart('from');
        $fromPart[0]['table'] = str_replace(
            $relevanceSelect,
            $relevanceSelect . ', ' . $additionalSelect,
            $fromPart[0]['table']
        );
        $searchTermQueryBuilder->resetQueryPart('from');
        $searchTermQueryBuilder->from($fromPart[0]['table'], $fromPart[0]['alias']);
        $searchTermQueryBuilder->addSelect('sr.keywords');
        $searchTermQueryBuilder->addSelect('sr.relevances');
    }
}
