define([
    "Magento_Checkout/js/view/summary/abstract-total",
    "Magento_Checkout/js/model/totals",
], function (Component, totals) {
    "use strict";

    return Component.extend({
        defaults: {
            template: "MagStore_Extension/checkout/summary/custom-fee",
            title: "Custom Fee",
        },

        isDisplayed: function () {
            var segment = totals.getSegment("custom_fee");

            return this.isFullMode() && segment && segment.value !== 0;
        },

        getValue: function () {
            var segment = totals.getSegment("custom_fee");

            return this.getFormattedPrice(segment ? segment.value : 0);
        },
    });
});
