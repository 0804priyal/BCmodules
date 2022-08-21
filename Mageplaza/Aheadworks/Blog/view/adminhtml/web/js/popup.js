define(
    [
        'jquery',
        'Magento_Ui/js/lib/spinner',
        'mage/template',
        'Magento_Ui/js/modal/modal'
    ],
    function ($, spinner, mageTemplate, modal) {
        'use strict';

        return {
            /**
             * Get content
             *
             * @param previewUrl
             * @param postData
             * @returns {boolean}
             */
            create: function (previewUrl, postData) {
                var options = {
                        autoOpen: true,
                        responsive: true,
                        clickableOverlay: false,
                        innerScroll: true,
                        modalClass: 'email-preview-modal',
                        title: $.mage.__('Post Preview'),
                        buttons: [{
                            text: $.mage.__('Ok'),
                            class: '',
                        }],
                        templateSelector: '#aw-template',
                        frameSelector: '#aw-frame',

                    },
                    template = mageTemplate(options.templateSelector),
                    html,
                    popupContent;

                $.ajax({
                    url: previewUrl,
                    type: "POST",
                    dataType: 'json',
                    data: {
                        post_data: postData
                    },
                    success: function(response) {
                        html = template({
                            data: {
                                url: response.url
                            }
                        });
                        spinner.hide();

                        if (!response.error) {
                            $(options.frameSelector).remove();
                            popupContent = $(html).hide();
                            $('body').append(popupContent);
                            modal(options, $(options.frameSelector));
                            return true;
                        }
                        this.onError(response.message);
                        return false;
                    }
                });
            },

            /**
             * Ajax request error handler
             *
             * @param errorMessage
             */
            onError: function (errorMessage) {
                alert({
                    content: $.mage.__(errorMessage),
                });
            }
        };
    }
);
