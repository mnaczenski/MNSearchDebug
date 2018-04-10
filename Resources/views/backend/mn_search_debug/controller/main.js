//{namespace name=backend/plugins/mn_search_debug/main}
//{block name="backend/mn_search_debug/controller/main"}
Ext.define('Shopware.apps.MNSearchDebug.controller.Main', {
    extend: 'Enlight.app.Controller',

    refs: [
        { ref: 'shopCombo', selector: 'MNSearchDebug-main-window field[name=shop-combo]' },
        { ref: 'previewGrid', selector: 'MNSearchDebug-main-preview' },
    ],

    init: function () {
        var me = this;

        me.mainWindow = me.getView('main.Window').create({}).show();

        me.onChangeShop();

        me.control({
            'MNSearchDebug-main-window': {
                changeShop: me.onChangeShop,
            },
        });
    },

    onChangeShop: function () {
        var me = this,
            shopStore = me.getShopCombo().store,
            shopId = me.getShopCombo().getValue();

        if (shopStore.isLoading() && !shopId) {
            shopStore.load({
                callback: function (records) {
                    shopId = records[0].get('id');
                    me.setShopId(shopId);
                }
            });
        } else {
            me.setShopId(shopId);
        }
    },

    setShopId: function (shopId) {
        var me = this,
            previewStore = me.getPreviewGrid().getStore();

        previewStore.getProxy().extraParams.shopId = shopId;
        previewStore.load();

    },

});
//{/block}
