(function ($) {

    var NepaliChronoCraftManager = {
        init: function () {
            this.cacheDom();
            this.bind();
        },

        cacheDom: function () {
            this.$nepaliChronoCraftWrapper = $('.nepali-chrono-craft-form-wrapper');
            this.btnSubmit  = this.$nepaliChronoCraftWrapper.find('.nepali-chrono-craft-submit-form');
        },

        bind: function () {
            this.btnSubmit.on('click', this.xhr);
        },

        xhr: function (e) {
            e.preventDefault()

            var $this = $(this),
                parentEl = $this.parents('.nepali-chrono-craft-conversion'),
                formType = $this.val();

            if (formType === 'Reset') {
                parentEl.find('input:not([type="button"])').val('')
                return false;
            }

            $this.prop('disabled', true);

            $.ajax({
                url: nepali_chrono_craft_data.ajaxurl,
                type: 'POST',
                data: {
                    security: nepali_chrono_craft_data.nonce,
                    action: 'nepali_chrono_craft_xhr_action',
                    year: parentEl.find('input[name=year]').val(),
                    month: parentEl.find('input[name=month]').val(),
                    day: parentEl.find('input[name=day]').val(),
                    convertTo: formType === 'Convert BS to AD' ? 'convert-bs-to-ad' : 'convert-ad-to-bs'
                },
                success: function (response) {
                    $('.nepali-chrono-resul-response').html(response.body);
                    $this.prop('disabled', false);
                }
            });
        }
    }
    NepaliChronoCraftManager.init();
}) (jQuery);