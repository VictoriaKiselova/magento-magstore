define([
    "uiComponent",
    "knockout",
    "Magento_Checkout/js/model/payment/additional-validators",
    "mage/translate",
], function (Component, ko, additionalValidators, $t) {
    "use strict";

    return Component.extend({
        defaults: {
            template: "Magento_Checkout/checkout/custom-checkout-form",
        },

        packagingTypeStandard: ko.observable(false),
        packagingTypeGift: ko.observable(false),
        packagingTypeError: ko.observable(""),

        initialize: function () {
            this._super();

            additionalValidators.registerValidator({
                validate: this.validate.bind(this),
            });

            return this;
        },

        onFieldChange: function (fieldName) {
            if (
                fieldName === "packagingType" &&
                this.hasSelectedInGroup("packagingType")
            ) {
                this.packagingTypeError("");
            }
        },

        hasSelectedInGroup: function (groupName) {
            if (groupName === "packagingType") {
                return this.packagingTypeStandard() || this.packagingTypeGift();
            }

            return false;
        },

        validateRequiredGroup: function (groupName, errorField, message) {
            if (this.hasSelectedInGroup(groupName)) {
                errorField("");
                return true;
            }

            errorField(message);
            return false;
        },

        validate: function () {
            var isPackagingTypeValid = this.validateRequiredGroup(
                "packagingType",
                this.packagingTypeError,
                $t("Select at least one packaging type."),
            );

            return isPackagingTypeValid;
        },
    });
});
