//{namespace name=backend/plugins/mn_search_debug/main}
//{block name="backend/mn_search_debug/store/preview"}
Ext.define('Shopware.apps.MNSearchDebug.store.Preview', {
    extend: 'Shopware.store.Listing',

    configure: function () {
        return {
            controller: 'MNSearchDebugPreview'
        };
    },

    model: 'Shopware.apps.MNSearchDebug.model.Preview'
});
//{/block}
