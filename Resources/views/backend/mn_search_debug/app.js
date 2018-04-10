

//{namespace name=backend/plugins/mn_search_debug/main}
//{block name="backend/mn_search_debug/view/application"}
Ext.define('Shopware.apps.MNSearchDebug', {
    extend: 'Enlight.app.SubApplication',

    name: 'Shopware.apps.MNSearchDebug',

    loadPath: '{url action=load}',

    bulkLoad: true,

    controllers: [
        'Main'
    ],

    views: [
        'main.Preview',
        'main.Window',
    ],

    models: [
        'Preview',
    ],

    stores: [
        'Preview',
    ],

    launch: function () {
        return this.getController('Main').mainWindow;
    }
});
//{/block}
