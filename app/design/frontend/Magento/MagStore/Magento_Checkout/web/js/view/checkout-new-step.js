define([
    "ko",
    "uiComponent",
    "underscore",
    "Magento_Checkout/js/model/step-navigator",
    "Magento_Checkout/js/model/full-screen-loader",
    "mage/storage",
    "Magento_Customer/js/model/customer",
    "Magento_Checkout/js/model/quote",
    "Magento_Checkout/js/model/shipping-rate-registry",
    "Magento_Checkout/js/action/get-totals",
    "Magento_Checkout/js/model/totals",
    "Magento_Checkout/js/model/cart/totals-processor/default",
    "Magento_Checkout/js/model/cart/cache",
], function (
    ko,
    Component,
    _,
    stepNavigator,
    fullScreenLoader,
    storage,
    customer,
    quote,
    rateRegistry,
    totals,
    getTotalsAction,
    defaultTotal,
    cartCache,
) {
    "use strict";

    return Component.extend({
        defaults: {
            template: "Magento_Checkout/check-new",
        },
        isVisible: ko.observable(true),
        isVisibleDrop: ko.observable(false),
        isLogedIn: customer.isLoggedIn(),
        stepCode: "newstep",
        stepTitle: "New Step",
        packagingTypeStandard: ko.observable(false),
        packagingTypeGift: ko.observable(false),
        packagingTypeError: ko.observable(""),

        initialize: function () {
            this._super();

            stepNavigator.registerStep(
                this.stepCode,
                null,
                this.stepTitle,
                this.isVisible,
                _.bind(this.navigate, this),
                15,
            );

            return this;
        },

        isStepDisplayed: function () {
            return true;
        },

        navigate: function () {},

        onPackagingChange: function () {
            if (this.hasPackagingSelected()) {
                this.packagingTypeError("");
            }
        },

        hasPackagingSelected: function () {
            return this.packagingTypeStandard() || this.packagingTypeGift();
        },

        validatePackaging: function () {
            if (this.hasPackagingSelected()) {
                this.packagingTypeError("");
                return true;
            }

            this.packagingTypeError("Select at least one packaging type.");
            return false;
        },

        navigateToNextStep: function () {
            if (!this.validatePackaging()) {
                return;
            }

            stepNavigator.next();
        },
    });
});
