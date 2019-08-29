(function (Drupal, $) {

    Drupal.behaviors.eauthority_athos = {
        attach: function (context, settings) {

        }
    };

}(Drupal, jQuery));

(function ($) {
    $(document).ready(function () {
        if ($("#edit-submit").parents('.checkbox-active').length) {
            $("#edit-submit").removeClass('btn-primary');
            $('#edit-submit').attr('disabled', 'disabled');
        }
        $('#edit-submit').click(function (e) {
            if ($("#edit-submit").parents('.checkbox-active').length) {
                if (!$('#certify_checkbox').is(':checked')) {
                    e.preventDefault();
                    var returnVal = confirm("You must certify to proceed");
                    $(this).prop("checked", returnVal);
                }
            }
        });
        $('#certify_checkbox').change(function () {
            if (this.checked) {
                $("#edit-submit").addClass('btn-primary');
                $('#edit-submit').removeAttr('disabled');
            } else {
                $("#edit-submit").removeClass('btn-primary');
                $('#edit-submit').attr('disabled', 'disabled');
            }
        });
    });
}(jQuery));