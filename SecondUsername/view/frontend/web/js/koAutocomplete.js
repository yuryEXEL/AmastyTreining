define(['uiComponent'],function (Component,$) {
    var mixin = {
        defaults: {
            minSearchValue: 5,
        }
    };
    return function (target) {
        return target.extend(mixin);
    }
});

