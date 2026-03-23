define(["jquery"], function ($) {
    "use strict";

    return function (targetWidget) {
        $.widget("mage.SwatchRenderer", targetWidget, {
            _OnClick: function ($this, $widget) {
                this._super($this, $widget);
                var selectedText = "";

                this.element.find(".swatch-attribute").each(function () {
                    var label = $(this).find(".swatch-attribute-label").text();
                    var selectedOption = $(this).find(
                        ".swatch-option.selected",
                    );

                    if (selectedOption.length) {
                        var value =
                            selectedOption.attr("aria-label") ||
                            selectedOption.text();

                        selectedText +=
                            $.trim(label) + ": " + $.trim(value) + " ";
                    }
                });

                var $display = $(".custom-selection-display");
                if (!$display.length) {
                    $display = $(
                        '<div class="custom-selection-display" style="color:#e02b27; font-weight:bold; margin:15px 0; font-size: 16px; clear:both;"></div>',
                    );
                    this.element.after($display);
                }

                if (selectedText.trim() !== "") {
                    $display.text("Ви обрали: " + selectedText);
                    console.log("Magento Mixin Debug: " + selectedText);
                }
            },
        });
        return $.mage.SwatchRenderer;
    };
});
