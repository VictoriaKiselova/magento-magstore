var config = {
    paths: {
        inputmask: "js/jquery.inputmask.bundle",
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
