var config = {
    paths: {
        inputmask: "js/jquery.inputmask.bundle",
        buttonTop: "js/back-to-top"
    },
    shim: {
        inputmask: {
            deps: ["jquery"]
        }
    },
    config: {
        mixins: {
            "mage/validation": {
                "js/mixins/cyrillicValidator": true,
                "js/mixins/age-range": true,
                "js/mixins/telephone-validation": true
            },
            "Magento_Search/js/form-mini": {
                "js/mixins/search-form-mixin": true
            }
        }
    }
};
