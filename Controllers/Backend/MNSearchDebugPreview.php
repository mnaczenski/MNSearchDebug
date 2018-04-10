<?php

use Shopware\Bundle\SearchBundle\Condition\CategoryCondition;
use Shopware\Bundle\SearchBundle\Condition\CustomerGroupCondition;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\ProductSearchResult;
use Shopware\Bundle\SearchBundle\Sorting\SearchRankingSorting;
use Shopware\Bundle\SearchBundle\SortingInterface;
use Shopware\Bundle\SearchBundleDBAL\KeywordFinderInterface;
use Shopware\Bundle\SearchBundleDBAL\SearchTerm\Keyword;
use Shopware\Bundle\StoreFrontBundle\Struct\ProductContext;
use MNSearchDebug\Bundle\SearchBundle\Condition\DebugSearchTermCondition;

class Shopware_Controllers_Backend_MNSearchDebugPreview extends Shopware_Controllers_Backend_ExtJs
{
    /**
     * Assigns the search list to the view
     */
    public function listAction()
    {
        $filter = $this->Request()->getParam('filter', []);
        $search = '';
        foreach ($filter as $expression) {
            if ($expression['property'] == 'search') {
                $search = $expression['value'];
            }
        }

        $this->View()->assign(
            $this->getList(
                $search,
                $this->Request()->getParam('shopId', 1),
                $this->Request()->getParam('start', 0),
                $this->Request()->getParam('limit', 20)
            )
        );
    }

    /**
     * @param string $search
     * @param int    $shopId
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     */
    private function getList($search, $shopId, $offset, $limit)
    {
        $criteria = new Criteria();

        $criteria->offset($offset);
        $criteria->limit($limit);

        $context = $this->createContext($shopId);
        if (!$context) {
            return [
                'success' => true,
                'data' => [],
                'total' => 0,
            ];
        }

        $customerGroupId = $context->getCurrentCustomerGroup()->getId();
        $criteria->addBaseCondition(new CustomerGroupCondition([$customerGroupId]));

        $categoryId = $context->getShop()->getCategory()->getId();
        $criteria->addBaseCondition(new CategoryCondition([$categoryId]));

        $criteria->addBaseCondition(new DebugSearchTermCondition($search));

        $criteria->addSorting(new SearchRankingSorting(SortingInterface::SORT_DESC));

        /** @var $result ProductSearchResult */
        $result = $this->get('shopware_search.product_search')->search($criteria, $context);

        $products = array_values($result->getProducts());

        $keywords = [];
        if (!empty($search)) {
            $keywords = $this->getKeywords($search);
        }

        return [
            'success' => true,
            'data' => $products,
            'keywords' => $keywords,
            'total' => $result->getTotalCount(),
        ];
    }

    /**
     * @param string $search
     *
     * @return array
     */
    private function getKeywords($search)
    {
        /** @var KeywordFinderInterface $keywordFinder */
        $keywordFinder = $this->get('shopware_searchdbal.keyword_finder_dbal');
        $keywords = $keywordFinder->getKeywordsOfTerm($search);

        $keywords = array_map(function ($keyword) {
            /* @var Keyword $keyword */
            return [
                'id' => $keyword->getId(),
                'term' => $keyword->getTerm(),
                'word' => $keyword->getWord(),
                'relevance' => $keyword->getRelevance(),
            ];
        }, $keywords);

        return $keywords;
    }

    /**
     * @param int $shopId
     *
     * @return ProductContext|null
     */
    private function createContext($shopId)
    {
        /** @var Shopware\Models\Shop\Repository $repo */
        $repo = $this->get('models')->getRepository(\Shopware\Models\Shop\Shop::class);
        $shop = $repo->getActiveById($shopId);

        if (!$shop) {
            return null;
        }

        $shop->registerResources($this->get('bootstrap'));

        return $this->get('shopware_storefront.context_service')->getProductContext();
    }
}
