/**
 * Ajax handling of forms
 */
(function ($) {

    var focused_element;
    $('.token-area').focus(function () {
        focused_element = $(this);
    });

    $('.replacement-token').click(function (e) {
        var token = $(this).data('input');
        // keep the focus
        if (focused_element.length != 0) {
            insertTextAtCursor(focused_element, token)
            e.preventDefault();
        }
    });

    $('.token-table .expander .switch').click(function () {
        var context = $(this).parent().parent();

        if ($(this).hasClass('closed')) {
            $('table', context).slideDown('fast');
            $(this).removeClass('closed');
            $(this).addClass('open');
        } else {
            $('table', context).slideUp('fast');
            $(this).removeClass('open');
            $(this).addClass('closed');
        }

    });

    function insertTextAtCursor(element, text) {
        //var element = document.getElementById(areaId);
        element = element[0];
        var scrollPos = element.scrollTop;
        var strPos = 0;
        var br = ((element.selectionStart || element.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") {
            element.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -element.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = element.selectionStart;

        var front = (element.value).substring(0, strPos);
        var back = (element.value).substring(strPos, element.value.length);
        element.value = front + text + back;
        strPos = strPos + text.length;
        if (br == "ie") {
            element.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -element.value.length);
            range.moveStart('character', strPos);
            range.moveEnd('character', 0);
            range.select();
        }
        else if (br == "ff") {
            element.selectionStart = strPos;
            element.selectionEnd = strPos;
            element.focus();
        }
        element.scrollTop = scrollPos;
    }


    // hierarchy select
    $('.field-widget-hierarchy_select_widget-wrapper').each(function () {
        var context = this;
        $('.hierarchy-level-2', context).chained($('.hierarchy-level-1', context));
        $('.hierarchy-level-3', context).chained($('.hierarchy-level-2', context));
    });

    $('.ajax-action').each(function() {
        $('#' + $(this).attr('id')).live($(this).data('action'), function (e) {
            var data_form = $(this).data('form');
            var data_submit = ($(this).data('submit'));
            var data_wrapper = ($(this).data('wrapper'));
            var trigger_element = $(this).attr('name');

            if ($("#" + data_form).length == 0) {
                data_form = 'post';
            }

            $.ajax({
                type: "POST",
                url: data_submit,
                data: $("#" + data_form).serialize() + "&trigger_element=" + trigger_element,
                success: function (data) {
                    $(data_wrapper).replaceWith(data);
                    $(data_wrapper).trigger('hydra_ajax');
                }
            });

            e.preventDefault();
        });
    });





})(jQuery);


var hydra = {
    media: null,
    image: null
};

(function ($) {
    // prototyping
    hydra.media = {
        div: null,
        frame: null,

        clear_frame: function () {

            // validate
            if (!this.frame) {
                return;
            }

            // detach
            this.frame.detach();
            this.frame.dispose();

            // reset var
            this.frame = null;

        },

        init: function () {
            // vars
            var _prototype = wp.media.view.AttachmentCompat.prototype;

            // orig
            _prototype.render();
        }
    };


    // reference
    var _media = hydra.media;

    hydra.image = {
        $el: null,

        o: {},

        set: function (o) {

            // merge in new option
            $.extend(this, o);
            // return this for chaining
            return this;

        },
        add: function (image) {

            // this function must reference a global div variable due to the pre WP 3.5 uploader
            // vars
            var div = this.$el;


            // set atts
            // Oh jesus !
            $('.hydra-image-url', _media.div).val(image.url);

            $('.hydra-image-url', _media.div).trigger('change');
            // set div class
            div.addClass('active');

            // validation
            div.closest('.field').removeClass('error');
        },
        popup: function () {

            // reference
            var t = this;


            // set global var
            _media.div = this.$el;


            // clear the frame
            _media.clear_frame();

            // Create the media frame
            _media.frame = wp.media({
                states: [
                    new wp.media.controller.Library({
                        library: wp.media.query(t.o.query),
                        multiple: t.o.multiple,
                        title: 'Insert image',
                        priority: 20,
                        filterable: 'all'
                    })
                ]
            });

            // When an image is selected, run a callback.
            hydra.media.frame.on('select', function () {

                // get selected images
                selection = _media.frame.state().get('selection');

                if (selection) {
                    var i = 0;
                    selection.each(function (attachment) {
                        // vars
                        var image = {
                            id: attachment.id,
                            url: attachment.attributes.url
                        };


                        // add image to field
                        hydra.image.add(image);


                    });
                }
            });

            hydra.media.frame.open();
            return false;
        }
    };

    $(document).on('click', '.hydra-remove-image', function (e) {
        var context = $(this).parent().parent();
        $('.hydra-image-url', context).val();
    });

    $(document).on('click', '.hydra-add-image', function (e) {
        e.preventDefault();
        hydra.image.set({ $el: $(this).parent().parent() }).popup();
    });


})(jQuery);