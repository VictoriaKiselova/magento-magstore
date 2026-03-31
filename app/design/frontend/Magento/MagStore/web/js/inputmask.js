define(["jquery", "inputmask"], function ($) {
    "use strict";

    var selector = 'input[name$="telephone"]';

    function applyMask(element) {
        if (!element || element.inputmask) {
            return;
        }

        $(element).inputmask({
            mask: "+38(099) 999 99 99",
        });
    }

    return function (Target) {
        return Target.extend({
            initialize: function () {
                this._super();

                $(document)
                    .off("focus.inputmaskTelephone", selector)
                    .on("focus.inputmaskTelephone", selector, function () {
                        applyMask(this);
                    });

                return this;
            },
        });
    };
});
