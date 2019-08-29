(function (Drupal, $) {

    Drupal.behaviors.eauthority_athos = {
        attach: function (context, settings) {

            $('#edit-accredit-flight-experience-wrapper').hide();
            if ($("#edit-entity-status").val() === 'DRF') {
                //$('#edit-training-record-attendee-actions-ief-add').css("display", "none");
            }
            if ($("#edit-entity-status").val() === 'SCH') {
                //$("#edit-training-course-target-id").attr("disabled", true);
            }
            if ($("#edit-entity-status").val() === 'CAN') {
                $('#edit-cancellation-date-wrapper').css("display", "block");
                $('#edit-cancellation-user-wrapper').css("display", "block");
                $('#edit-cancellation-reason-wrapper').css("display", "block");
            } else {
                $('#edit-cancellation-date-wrapper').css("display", "none");
                $('#edit-cancellation-user-wrapper').css("display", "none");
                $('#edit-cancellation-reason-wrapper').css("display", "none");
            }
            //$('#edit-prefix-wrapper').css("display", "none");
        }
    };

}(Drupal, jQuery));

(function ($) {
    $(document).ready(function () {
        $("#edit-aircraft-wrapper input").autocomplete(
                {select: function (event) {
                        target = $(event.delegateTarget);
                        idtarget = target.attr('id');
                        text = target.text();
                        targetId = event.target.id;
                        nweObj = $('#' + targetId);

                        console.info(idtarget);
                        console.info(text);
                        console.info(targetId);
                        console.info(nweObj.val());
                        $("#edit-aircraft-wrapper input").each(function () {
                            text2 = $(this).val();
                            position = text2.indexOf("(") - 1;
                            var textDefinitive = text2.substring(0, position);
                            if (text === textDefinitive) {
                                event.preventDefault();
                                nweObj.val('');
                                $(this).css("color", "black");
                                return false;
                            }
                            if ($(this).attr('id') !== 'edit-aircraft-add-more') {
                                $(this).css("color", "black");
                            }
                        });
                    }
                }
        );

        $("#edit-aircraft-wrapper input").keyup(function () {
            newInput = $(this);
            id = $(this).attr('id');
            stringToSearch = $(this).val();
            $(this).css("color", "black");
            $("#edit-aircraft-wrapper input").each(function () {
                if ($(this).attr('id') !== 'edit-aircraft-add-more' && id !== $(this).attr('id')) {
                    $(this).css("color", "black");
                }
                if (id !== $(this).attr('id') && $(this).attr('id') !== 'edit-aircraft-add-more') {
                    if ($(this).val().toLowerCase().indexOf(stringToSearch.toLowerCase()) >= 0 && stringToSearch !== '') {
                        $(this).css("color", "red");
                        newInput.css("color", "red");
                    }
                }
            });
        });

        $("#edit-training-course-wrapper input").autocomplete(
                {select: function (event, ui) {
                        var item = ui.item.value;
                        var regExp = /\(([^)]+)\)$/;
                        var matches = regExp.exec(item);
                        var TrainingCourseId = matches[1];
                        jQuery.ajax({
                            url: '/get/customer/customer_training_course/' + TrainingCourseId,
                            success: function (result) {
                                if (!$.trim(result)){
                                    return false;
                                }
                                var response = result[0].data;
                                if (response === 'THEORIC') {
                                    $('#edit-accredit-flight-experience-value').prop('checked', false);
                                    $('#edit-accredit-flight-experience-value').removeAttr('disabled');
                                    $('#edit-accredit-flight-experience-value').removeAttr('readonly');
                                    $('#edit-accredit-flight-experience-wrapper').hide();
                                } else if (response === 'BOTH') {
                                    $('#edit-accredit-flight-experience-value').prop('checked', false);
                                    $('#edit-accredit-flight-experience-value').removeAttr('disabled');
                                    $('#edit-accredit-flight-experience-value').removeAttr('readonly');
                                    $('#edit-accredit-flight-experience-wrapper').show();
                                } else if (response === 'PRACTICAL') {
                                    $('#edit-accredit-flight-experience-value').prop('checked', true);
                                    $('#edit-accredit-flight-experience-value').attr('disabled', 'disabled');
                                    $('#edit-accredit-flight-experience-value').attr('readonly', 'disabled');
                                    $('#edit-accredit-flight-experience-wrapper').show();
                                }
                            }
                        });
                    }
                }
        );
    });
}(jQuery));