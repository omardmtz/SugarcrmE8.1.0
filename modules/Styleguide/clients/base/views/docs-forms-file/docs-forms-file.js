/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
({
  // components dropdowns
  _renderHtml: function () {
    this._super('_renderHtml');

    /* Custom file upload overrides and avatar widget */
    var uobj = [],
        onUploadChange = function (e) {
          var status = $(this),
              opts = 'show';
          if (this.value) {
            var this_container = $(this).parent('.file-upload').parent('.upload-field-custom'),
              value_explode = this.value.split('\\'),
              value = value_explode[value_explode.length-1];

            if ($(this).closest('.upload-field-custom').hasClass('avatar')===true) { /* hide status for avatars */
              opts = "hide";
            }

            if (this_container.next('.file-upload-status').length > 0) {
              this_container.next('.file-upload-status').remove();
            }
            this.$('<span class="file-upload-status ' + opts + ' ">' + value + '</span>').insertAfter(this_container);
          }
        },
        onUploadFocus = function () {
          $(this).parent().addClass('focus');
        },
        onUploadBlur = function () {
          $(this).parent().addClass('focus');
        };

    this.$('.upload-field-custom input[type=file]').each(function() {
      // Bind events
      $(this)
        .bind('focus', onUploadFocus)
        .bind('blur', onUploadBlur)
        .bind('change', onUploadChange);

      // Get label width so we can make button fluid, 12px default left/right padding
      var lbl_width = $(this).parent().find('span strong').width() + 24;
      $(this)
        .parent().find('span').css('width',lbl_width)
        .closest('.upload-field-custom').css('width',lbl_width);

      // Set current state
      onUploadChange.call(this);

      // Minimizes the text input part in IE
      $(this).css('width', '0');
    });

    this.$('#photoimg').on('change', function() {
      $("#preview1").html('');
      $("#preview1").html('<span class="loading">Loading...</span>');
      $("#imageform").ajaxForm({
        target: '#preview1'
      }).submit();
    });

    this.$('.preview.avatar').on('click.styleguide', function(e){
        $(this).closest('.span10').find('label.file-upload span strong').trigger('click');
    });
  },

  _dispose: function(view) {
      this.$('#photoimg').off('change');
      this.$('.preview.avatar').off('click.styleguide');
  }
})
