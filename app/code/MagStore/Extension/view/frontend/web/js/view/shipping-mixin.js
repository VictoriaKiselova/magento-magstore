define(["uiRegistry"], function (registry) {
    "use strict";

    return function (Component) {
        return Component.extend({
            validateShippingInformation: function () {
                var invalid = false,
                    componentPath =
                        "checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.";

                ["0", "1"].forEach(function (lineIndex) {
                    var streetLine = registry.get(componentPath + lineIndex);

                    if (!streetLine || !streetLine.visible()) {
                        return;
                    }

                    streetLine.validation = streetLine.validation || {};
                    streetLine.validation["min_text_length"] = 5;
                    streetLine.validation["max_text_length"] = 255;

                    if (lineIndex === "0") {
                        streetLine.validation["required-entry"] = true;
                    }

                    if (!streetLine.validate().valid) {
                        invalid = true;
                    }
                });

                if (invalid) {
                    this.focusInvalid();

                    return false;
                }

                return this._super();
            },
        });
    };
});
