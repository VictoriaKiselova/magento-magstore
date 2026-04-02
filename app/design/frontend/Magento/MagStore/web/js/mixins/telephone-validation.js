define(["jquery", "mage/translate"], function ($) {
    "use strict";

    return function (targetWidget) {
        $.validator.addMethod(
            "validate-ukraine-phone",
            function (value, element) {
                return (
                    this.optional(element) ||
                    /^\+38\(0\d{2}\)\s\d{3}-\d{2}-\d{2}$/.test(value)
                );
            },
            $.mage.__("Будь ласка, введіть номер у форматі +38(0XX) XXX-XX-XX"),
        );
        return targetWidget;
    };
});
