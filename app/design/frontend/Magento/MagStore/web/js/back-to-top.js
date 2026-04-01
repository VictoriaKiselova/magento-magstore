define(["jquery"], function ($) {
    "use strict";

    let btnTop = document.querySelector(".button-arrow-footer");

    return function (config, element) {
        $(element);

        $(window).on("scroll", function () {
            if (window.scrollY > 400) {
                btnTop.classList.add("activeBtnTop");
            } else {
                btnTop.classList.remove("activeBtnTop");
            }
        });

        $(btnTop).on("click", function () {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: "smooth",
            });
        });
    };
});
