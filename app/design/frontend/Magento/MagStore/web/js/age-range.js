define(["jquery", "mage/translate"], function ($) {
    "use strict";

    return function (targetWidget) {
        $.validator.addMethod(
            "numberVal",
            function (value, element) {
                return (
                    this.optional(element) ||
                    (/^\d+$/.test(value) && value >= 18 && value <= 120)
                );
            },
            $.mage.__(
                "Реєстрація доступна тільки для осіб віком від 18 до 120 років.",
            ),
        );
        return targetWidget;
    };
});
