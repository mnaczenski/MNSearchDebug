<?php

namespace MNSearchDebug;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;


class MNSearchDebug extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);

    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(UninstallContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);

    }

    /**
     * {@inheritdoc}
     */
    public function update(UpdateContext $context)
    {

        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }
}
