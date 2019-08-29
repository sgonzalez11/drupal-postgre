(function (Drupal, $) {

    Drupal.behaviors.eauthority_athos = {
        attach: function (context, settings) {

        }
    };

}(Drupal, jQuery));

(function ($) {
    $(document).ready(function () {

        $("#edit-submit").removeClass('btn-primary');
        $('#edit-submit').attr('disabled', 'disabled');
        $("#edit-assessment-recommendation").click(function () {
            var wic = $('input[name=assessment_recommendation]:checked').val();
            if (wic !== "_none") {
                $("#edit-submit").addClass('btn-primary');
                $('#edit-submit').removeAttr('disabled');
            } else {
                $("#edit-submit").removeClass('btn-primary');
                $('#edit-submit').attr('disabled', 'disabled');
            }
        });
    });
}(jQuery));