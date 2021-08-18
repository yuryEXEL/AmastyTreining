define([
    'uiComponent',
    'jquery',
    'mage/url'
], function (Component,$,urlBuilder) {
    return Component.extend({
        defaults: {
            searchText: '',
            minSearchValue: 3,
            searchUrl: urlBuilder.build('lool/ajax/autocoplate'),
            searchResult: [],
            availableSku: []
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText','searchResult']);
            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (searchValue) {
            if (searchValue.length >= this.minSearchValue)  {
                $.getJSON(this.searchUrl, {
                    q: searchValue
                }, function (data) {
                    this.searchResult(Object.values(data));
                }.bind(this));
            }else
                this.searchResult([]);
        }
    });
});

