define(["jquery", "underscore", "mage/tabs"], function ($, _) {
    "use strict";

    return function (config, element) {
        var $tabs = $(element);

        var refreshSlider = _.debounce(function () {
            $(window).trigger("resize");
            $("body").trigger("contentUpdated");
        }, 100);

        $tabs.on("dimensionsChanged", function () {
            refreshSlider();
        });

        $tabs.on("beforeOpen", function (event) {
            if ($(event.target).attr("id") === "tab-slider") {
                $(event.target).show();
            }
        });
    };
});

