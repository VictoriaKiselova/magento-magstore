define(["jquery"], function ($) {
    "use strict";

    return function (widget) {
        $.widget("mage.quickSearch", widget, {
            _onPropertyChange: function () {
                var value;
                this._super();

                value = (this.element.val() || "").trim();
                if (value.length > 0) {
                    $(this.submitBtn).addClass("search-btn--active");
                } else {
                    $(this.submitBtn).removeClass("search-btn--active");
                }
            },
        });

        return $.mage.quickSearch;
    };
});
