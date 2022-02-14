function imagePreview(fileInput) {
    if (fileInput.files && fileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
            $('.preview_image').html('<img src="'+event.target.result+'" width="100%" height="auto"/>');
        };
        fileReader.readAsDataURL(fileInput.files[0]);
    }
}

$(".file-upload-default").change(function () {
    imagePreview(this);
});

(function($) {
    'use strict';
    $(function() {
      $('.file-upload-browse').on('click', function() {
        var file = $(this).parent().parent().parent().find('.file-upload-default');
        file.trigger('click');
      });
      $('.file-upload-default').on('change', function() {
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
      });
    });
  })(jQuery);