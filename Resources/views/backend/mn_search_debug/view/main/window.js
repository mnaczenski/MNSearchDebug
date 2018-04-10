//{namespace name=backend/plugins/mn_search_debug/main}
//{block name="backend/mn_search_debug/view/main/window"}
Ext.define('Shopware.apps.MNSearchDebug.view.main.Window', {
    extend: 'Enlight.app.Window',

    alias: 'widget.MNSearchDebug-main-window',

    minWidth: 800,
    minHeight: 300,

    layout: 'border',

    overflowY: 'auto',

    listeners: {
        resize: function (window, width, height) {
            if (height < 570) {
                window.setBodyStyle('padding: 0 15px 0 0;');
                window.doLayout();
            } else {
                window.setBodyStyle('padding: 0;');
                window.doLayout();
            }
        }
    },

    initComponent: function () {
        var me = this,
            topToolbar = me.getTopToolbar(),
            tabPanel;

        me.title = '{s name=window/title}MNSearchDebug{/s}';

        tabPanel = Ext.create('Ext.tab.Panel', {
            items: [
                {
                    title: '{s name=window/tab/preview}Preview{/s}',
                    xtype: 'MNSearchDebug-main-preview'
                }
            ]
        });

        me.formPanel = Ext.create('Ext.form.Panel', {
            minHeight: 462,

            layout: 'fit',
            region: 'center',

            name: 'MNSearchDebug-form-panel',
            items: [tabPanel],
            border: false
        });

        me.dockedItems = [topToolbar];
        me.items = [me.formPanel];

        me.callParent(arguments);
    },

    /**
     * Creates the grid toolbar with the shop picker
     *
     * @return [Ext.toolbar.Toolbar] grid toolbar
     */
    getTopToolbar: function () {
        var me = this,
            shopStore = Ext.create('Shopware.apps.Base.store.Shop'),
            shopCombo,
            toolbar;

        shopStore.filters.clear();
        shopStore.load({
            callback: function (records) {
                shopCombo.setValue(records[0].get('id'));
            }
        });

        shopCombo = Ext.create('Ext.form.field.ComboBox', {
            fieldLabel: '{s name=window/chooseShop}Choose shop{/s}',
            store: shopStore,
            labelWidth: 80,
            name: 'shop-combo',
            margin: '3px 6px 3px 0',
            queryMode: 'local',
            valueField: 'id',
            editable: false,
            displayField: 'name',
            listeners: {
                'select': function () {
                    if (this.store.getAt('0')) {
                        me.fireEvent('changeShop');
                    }
                }
            }
        });

        toolbar = Ext.create('Ext.toolbar.Toolbar', {
            dock: 'top',
            ui: 'shopware-ui',
            items: [
                '->',
                shopCombo
            ]
        });

        return toolbar;
    },

});
//{/block}
