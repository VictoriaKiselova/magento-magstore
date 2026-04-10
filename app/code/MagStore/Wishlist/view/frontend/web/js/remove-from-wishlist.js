define(['jquery'], function ($) {
    'use strict';

    /**
     * Mixin for $.mage.wishlist widget.
     * Replaces the standard full-page-POST remove handler with an AJAX call,
     * so the page reloads only after a successful server response.
     */
    return function (widget) {
        $.widget('mage.wishlist', widget, {

            /** @inheritdoc */
            _create: function () {
                this._super();

                // Remove the standard remove handler added by the parent _create,
                // then attach our AJAX handler in its place.
                this.element.off('click', this.options.btnRemoveSelector);
                this.element.on(
                    'click',
                    this.options.btnRemoveSelector,
                    $.proxy(this._removeItemAjax, this)
                );
            },

            /**
             * Send an AJAX DELETE request and reload the page on success.
             *
             * @param {jQuery.Event} event
             * @private
             */
            _removeItemAjax: function (event) {
                event.preventDefault();

                var postData = $(event.currentTarget).data('post-remove');

                if (!postData || !postData.action) {
                    console.error('remove-from-wishlist: data-post-remove is missing or malformed');
                    return;
                }

                $.ajax({
                    url: postData.action,
                    type: 'POST',
                    data: $.extend({}, postData.data, {
                        form_key: $('input[name="form_key"]').val()
                    }),
                    success: function () {
                        location.reload();
                    },
                    error: function () {
                        console.error('remove-from-wishlist: server returned an error');
                    }
                });
            }
        });

        return $.mage.wishlist;
    };
});
