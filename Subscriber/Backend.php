<?php

namespace MNSearchDebug\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs as EventArgs;
use Enlight_Controller_Front;
use Shopware\Components\Model\ModelManager;

class Backend implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginPath;

    /**
     * @var Enlight_Controller_Front
     */
    private $front;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * Constructor of the subscriber. Initialises the path variable
     *
     * @param string                   $pluginPath
     * @param Enlight_Controller_Front $front
     * @param ModelManager             $modelManager
     */
    public function __construct($pluginPath, Enlight_Controller_Front $front, ModelManager $modelManager)
    {
        $this->pluginPath = $pluginPath;
        $this->front = $front;
        $this->modelManager = $modelManager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Backend' => 'onPreDispatchBackend',
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Index' => 'onPostDispatchIndex',
        ];
    }

    /**
     * @param EventArgs $args
     */
    public function onPreDispatchBackend(EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Index $subject */
        $subject = $args->getSubject();
        $view = $subject->View();

        $view->addTemplateDir($this->pluginPath . '/Resources/views/');
    }

    /**
     * provides the MNSearchDebug logo in the backend
     *
     * @param EventArgs $args
     */
    public function onPostDispatchIndex(EventArgs $args)
    {
        $subject = $args->getSubject();
        $view = $subject->View();

        $view->extendsTemplate('backend/mn_search_debug/menu_entry.tpl');
    }
}
