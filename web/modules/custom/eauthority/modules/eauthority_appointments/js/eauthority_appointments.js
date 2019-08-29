(function ($) {
    $('#edit-unit').hide();
    $.fn.unitAjaxCallback = function (data) {
        if (data === null) {
            return false;
        }
        var length = Object.keys(data).length;
        var firstOption = $("#edit-unit option:first").text();
        $('#edit-unit').html('');
        $('#edit-unit').append(new Option(firstOption, ''));
        $.each(data, function (key, val) {
            $('#edit-unit').append(new Option(val, key));
        });
        if (length === 1) {
            $('#edit-unit').hide();
            $("#edit-unit option:eq(1)").attr('selected', 'selected');
            $('#edit-unit').trigger('change');
        }
        if (length > 1) {
            $('.calendar-set').fullCalendar('destroy');
            $("#edit-unit option:eq(0)").attr('selected', 'selected');
            $('#edit-unit').show();
        }
    };

    $.fn.EventAjaxCallback = function (data) {
        var slot_duration = data.slot_duration;
        var max_time = data.max_time;
        var min_time = data.min_time;
        var weekends = data.weekends;
        var eventList = data.events;
        $('.calendar-set').fullCalendar('destroy');
        $('.calendar-set').fullCalendar({
            allDaySlot: false,
            defaultView: 'agendaWeek',
            minTime: min_time,
            maxTime: max_time,
            nowIndicator: true,
            slotDuration: slot_duration + ':00',
            slotLabelInterval: slot_duration,
            slotLabelFormat: 'H:mm',
            weekends: weekends,
            events: eventList,
            selectable: false,
            selectHelper: false,
            selectOverlap: false,
            disableDragging: true,
            slotMinutes: 15,
            eventClick: function (calEvent) {
                var startDate = calEvent.start.format("YYYY-MM-DD HH:mm:ss");
                var now = moment().format("YYYY-MM-DD HH:mm:ss");
                if (now > startDate) {
                    return false;
                }
                if (calEvent.title !== 'Available') {
                    return false;
                }
                $('#edit-event-title').val(calEvent.title);
                $('#edit-event-end').val(calEvent.end);
                $('#edit-event-id').val(calEvent.id);
                $('#edit-event-start').val(calEvent.start).trigger('change');
            }
        });

    };

    Drupal.behaviors.eauthorityHelper = {
        attach: function (context, settings) {
            $('.confirm_appointment').once('eauthority_appointments').on('click', function () {
                $('#event_start_modal').dialog('close');
                $('#edit-submit').trigger('click');
            });
            $('.cancel_appointment').once('eauthority_appointments').on('click', function () {
                $('#event_start_modal').dialog('close');
            });
        }
    };


})(jQuery);