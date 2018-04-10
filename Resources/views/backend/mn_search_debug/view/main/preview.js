//{namespace name=backend/plugins/mn_search_debug/main}
//{block name="backend/mn_search_debug/view/main/profiles"}
Ext.define('Shopware.apps.MNSearchDebug.view.main.Preview', {
    extend: 'Shopware.grid.Panel',
    alias: 'widget.MNSearchDebug-main-preview',

    store: Ext.create('Shopware.apps.MNSearchDebug.store.Preview'),

    configure: function () {
        var me = this;
        return {
            columns: {
                name: {
                    header: '{s name=preview/previewNameColumn}Product name{/s}'
                },
                number: {
                    header: '{s name=preview/numberColumn}Number{/s}',
                    flex: 0.5
                },
                keywords: {
                    header: '{s name=preview/keywordsColumn}Keywords (Factor * Relevance){/s}',
                    renderer: me.renderKeywords
                },
                ranking: {
                    header: '{s name=preview/rankingColumn}Ranking{/s}',
                    flex: 0.5
                }
            },
            addButton: false,
            deleteButton: false
        };
    },

    renderKeywords: function (value, meta, record) {
        var me = this,
            result = '',
            keywords = value.split(','),
            relevances = record.get('relevances').split(',');

        if (record.get('isTopSeller')) {
            result += '<strong>{s name=preview/isTopSeller}Topseller{/s}</strong>, ';
        }

        if (record.get('isNew')) {
            result += '<strong>{s name=preview/isNew}New{/s}</strong>, ';
        }

        Ext.Array.each(keywords, function(id, i) {
            var keyword = me.getKeyword(id);
            if (!keyword) {
                return;
            }
            var word = keyword.word;
            if (Ext.isObject(word)) {
                word = word.keyword;
            }
            result += word + ' (';
            result += keyword.relevance + ' * ';
            result += relevances[i] / keyword.relevance;
            result += ')';
            if (i + 1 < keywords.length) {
                result += ', ';
            }
        });

        return result;
    },

    getKeyword: function (id) {
        var keywords = this.store.proxy.reader.rawData.keywords;

        if (!keywords) {
            return;
        }

        for (var index = 0; index < keywords.length; ++index) {
            if (keywords[index].id == id) {
                return keywords[index];
            }
        }
    },

    createActionColumnItems: function () {
        var items = [];

        items.push(
            {
                action: 'edit',
                cls: 'editBtn',
                iconCls: 'sprite-pencil',
                handler: function (view, rowIndex, colIndex, item, opts, record) {
                    Shopware.app.Application.addSubApplication({
                        name: 'Shopware.apps.Article',
                        action: 'detail',
                        params: {
                            articleId: record.get('id')
                        }
                    });
                }
            }
        );

        return items;
    }
});
//{/block}
