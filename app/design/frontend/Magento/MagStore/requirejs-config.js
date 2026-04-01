var config = {
    paths: {
        inputmask: "js/jquery.inputmask.bundle",
        buttonTop: "js/back-to-top",
    },
    shim: {
        inputmask: {
            deps: ["jquery"],
        },
    },
    config: {
        mixins: {
            "mage/validation": {
                "js/cyrillicValidator": true,
                "js/age-range": true,
                "js/telephone-validation": true,
            },
        },
    },
};
