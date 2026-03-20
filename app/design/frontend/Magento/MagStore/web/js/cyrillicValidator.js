define(["jquery", "mage/translate"], function ($) {
    "use strict";

    return function (targetWidget) {
        $.validator.addMethod(
            "cyrillicVal",
            function (value, element) {
                return (
                    this.optional(element) ||
                    /^[А-Яа-яІіЇїЄєҐґ-\s]+$/.test(value)
                );
            },
            $.mage.__(
                "Будь ласка, використовуйте лише літери українського алфавіту.",
            ),
        );
        return targetWidget;
    };
});
