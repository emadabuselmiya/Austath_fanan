/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 *
 **/

'use strict';

let direction = 'ltr';

if (isRtl) {
    direction = 'rtl';
}

document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const calendarEl = document.getElementById('calendar'),
            appCalendarSidebar = document.querySelector('.app-calendar-sidebar'),
            addEventSidebar = document.getElementById('addEventSidebar'),
            appOverlay = document.querySelector('.app-overlay'),
            offcanvasTitle = document.querySelector('.offcanvas-title'),
            btnToggleSidebar = document.querySelector('.btn-toggle-sidebar'),
            btnSubmit = document.querySelector('#btnSubmit'),
            btnDeleteEvent = document.querySelector('.btn-delete-event'),
            btnCancel = document.querySelector('.btn-cancel'),
            start_time = document.querySelector('#start_time'),
            end_time = document.querySelector('#end_time'),
            attend_id = $('#attend_id'),
            employee = $('#employee'),
            comment = document.querySelector('#comment'),
            filterInput = [].slice.call(document.querySelectorAll('.input-filter')),
            inlineCalendar = document.querySelector('.inline-calendar');

        let eventToUpdate,
            currentEvents = events, // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
            isFormValid = false,
            inlineCalInstance;

        // Init event Offcanvas
        const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

        //! TODO: Update Event label and guest code to JS once select removes jQuery dependency

        // Event start (flatpicker)
        if (start_time) {
            var start = start_time.flatpickr({
                enableTime: true,
                altFormat: 'Y-m-dTH:i:S',
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute('step', null);
                    }
                }
            });
        }

        // Event end (flatpicker)
        if (end_time) {
            var end = end_time.flatpickr({
                enableTime: true,
                altFormat: 'Y-m-dTH:i:S',
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute('step', null);
                    }
                }
            });
        }

        // Inline sidebar calendar (flatpicker)
        if (inlineCalendar) {
            inlineCalInstance = inlineCalendar.flatpickr({
                monthSelectorType: 'static',
                inline: true
            });
        }

        // Event click function
        function eventClick(info) {
            eventToUpdate = info.event;
            if (eventToUpdate.url) {
                info.jsEvent.preventDefault();
                window.open(eventToUpdate.url, '_blank');
            }
            bsAddEventSidebar.show();
            // For update event set offcanvas title text: Update Event
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = 'تعديل الدوام';
            }
            btnSubmit.innerHTML = 'تعديل';
            btnSubmit.classList.add('btn-update-event');
            btnSubmit.classList.remove('btn-add-event');
            btnDeleteEvent.classList.remove('d-none');

            start.setDate(eventToUpdate.start, true, 'Y-m-d');
            eventToUpdate.end !== null
                ? end.setDate(eventToUpdate.end, true, 'Y-m-d')
                : end.setDate(eventToUpdate.start, true, 'Y-m-d');

            attend_id.val(eventToUpdate.id);
            employee.val(eventToUpdate.extendedProps.employee).trigger('change');
            comment.val = eventToUpdate.extendedProps.comment;

            // // Call removeEvent function
            // btnDeleteEvent.addEventListener('click', e => {
            //   removeEvent(parseInt(eventToUpdate.id));
            //   // eventToUpdate.remove();
            //   bsAddEventSidebar.hide();
            // });
        }

        // Modify sidebar toggler
        function modifyToggler() {
            const fcSidebarToggleButton = document.querySelector('.fc-sidebarToggle-button');
            fcSidebarToggleButton.classList.remove('fc-button-primary');
            fcSidebarToggleButton.classList.add('d-lg-none', 'd-inline-block', 'ps-0');
            while (fcSidebarToggleButton.firstChild) {
                fcSidebarToggleButton.firstChild.remove();
            }
            fcSidebarToggleButton.setAttribute('data-bs-toggle', 'sidebar');
            fcSidebarToggleButton.setAttribute('data-overlay', '');
            fcSidebarToggleButton.setAttribute('data-target', '#app-calendar-sidebar');
            fcSidebarToggleButton.insertAdjacentHTML('beforeend', '<i class="ti ti-menu-2 ti-sm text-heading"></i>');
        }

        // Filter events by calender
        function selectedCalendars() {
            let selected = [],
                filterInputChecked = [].slice.call(document.querySelectorAll('.input-filter:checked'));

            filterInputChecked.forEach(item => {
                selected.push(item.getAttribute('data-value'));
            });

            return selected;
        }

        // --------------------------------------------------------------------------------------------------
        // AXIOS: fetchEvents
        // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
        // --------------------------------------------------------------------------------------------------
        function fetchEvents(info, successCallback) {
            // Fetch Events from API endpoint reference
            /* $.ajax(
              {
                url: '../../../assets/data/app-calendar-events.js',
                type: 'GET',
                success: function (result) {
                  // Get requested calendars as Array
                  var calendars = selectedCalendars();

                  return [result.events.filter(event => calendars.includes(event.extendedProps.calendar))];
                },
                error: function (error) {
                  console.log(error);
                }
              }
            ); */

            let calendars = selectedCalendars();
            // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
            // You should make an API call, look into above commented API call for reference
            let selectedEvents = currentEvents.filter(function (event) {
                // console.log(event.extendedProps.calendar.toLowerCase());
                return calendars.includes(event.extendedProps.calendar.toLowerCase());
            });
            // if (selectedEvents.length > 0) {
            successCallback(selectedEvents);
            // }
        }

        // Init FullCalendar
        // ------------------------------------------------
        let calendar = new Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: fetchEvents,
            plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
            editable: true,
            dragScroll: true,
            dayMaxEvents: 2,
            eventResizableFromStart: true,
            customButtons: {
                sidebarToggle: {
                    text: 'Sidebar'
                }
            },
            headerToolbar: {
                start: 'sidebarToggle, prev,next, title',
                end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            direction: direction,
            initialDate: new Date(),
            navLinks: true, // can click day/week names to navigate views
            eventClassNames: function ({event: calendarEvent}) {
                const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];
                // Background Color
                return ['fc-event-' + colorName];
            },
            dateClick: function (info) {
                let date = moment(info.date).format('YYYY-MM-DD');
                resetValues();
                bsAddEventSidebar.show();

                // For new event set offcanvas title text: Add Event
                if (offcanvasTitle) {
                    offcanvasTitle.innerHTML = 'اضافة دوام';
                }
                btnSubmit.innerHTML = 'اضافة';
                btnSubmit.classList.remove('btn-update-event');
                btnSubmit.classList.add('btn-add-event');
                btnDeleteEvent.classList.add('d-none');
                start_time.value = date;
                end_time.value = date;
            },
            eventClick: function (info) {
                eventClick(info);
            },
            datesSet: function () {
                modifyToggler();
            },
            viewDidMount: function () {
                modifyToggler();
            }
        });

        // Render calendar
        calendar.render();
        // Modify sidebar toggler
        modifyToggler();

        const eventForm = document.getElementById('attendForm');
        const fv = FormValidation.formValidation(eventForm, {
            fields: {
                employee: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter event title '
                        }
                    }
                },
                start_time: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter start date '
                        }
                    }
                },
                end_time: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter end date '
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    eleValidClass: '',
                    rowSelector: function (field, ele) {
                        // field is the field name & ele is the field element
                        return '.mb-3';
                    }
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        })
            .on('core.form.valid', function () {
                // Jump to the next step when all fields in the current step are valid
                isFormValid = true;
            })
            .on('core.form.invalid', function () {
                // if fields are invalid
                isFormValid = false;
            });

        // Sidebar Toggle Btn
        if (btnToggleSidebar) {
            btnToggleSidebar.addEventListener('click', e => {
                btnCancel.classList.remove('d-none');
            });
        }

        // Add Event
        // ------------------------------------------------
        function addEvent() {
            // ? Add new event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response

            var formData = new FormData($('#attendForm')[0]);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '/admin/attendance',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (eventData) {
                    $('#loading').hide();
                    if (eventData.errors) {
                        for (var i = 0; i < eventData.errors.length; i++) {
                            toastr.error(eventData.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('تم الاضافة بنجاح', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        currentEvents.push(eventData);
                        calendar.refetchEvents();
                    }
                }
            });

            // ? To add event directly to calender (won't update currentEvents object)
            // calendar.addEvent(eventData);
        }

        // Update Event
        // ------------------------------------------------
        function updateEvent() {
            // ? Update existing event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response
            var formData = new FormData($('#attendForm')[0]);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '/admin/attendance',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (eventData) {
                    $('#loading').hide();
                    if (eventData.errors) {
                        for (var i = 0; i < eventData.errors.length; i++) {
                            toastr.error(eventData.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('تم التعديل بنجاح', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        eventData.id = parseInt(eventData.id);
                        currentEvents[currentEvents.findIndex(el => el.id === eventData.id)] = eventData; // Update event by id
                        calendar.refetchEvents();
                    }
                }
            });

            // ? To update event directly to calender (won't update currentEvents object)
            // let propsToUpdate = ['id', 'title', 'url'];
            // let extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];

            // updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
        }

        // Remove Event
        // ------------------------------------------------

        function removeEvent(eventId) {
            // ? Delete existing event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '/admin/attendance/destroy/'+eventId,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (eventData) {
                    $('#loading').hide();
                    if (eventData.errors) {
                        for (var i = 0; i < eventData.errors.length; i++) {
                            toastr.error(eventData.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('تم الحذف بنجاح', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        currentEvents = currentEvents.filter(function (event) {
                            return event.id != eventId;
                        });
                        calendar.refetchEvents();
                    }
                }
            });

            // ? To delete event directly to calender (won't update currentEvents object)
            // removeEventInCalendar(eventId);
        }

        // (Update Event In Calendar (UI Only)
        // ------------------------------------------------
        const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
            const existingEvent = calendar.getEventById(updatedEventData.id);

            // --- Set event properties except date related ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setProp
            // dateRelatedProps => ['start', 'end', 'allDay']
            // eslint-disable-next-line no-plusplus
            for (var index = 0; index < propsToUpdate.length; index++) {
                var propName = propsToUpdate[index];
                existingEvent.setProp(propName, updatedEventData[propName]);
            }

            // --- Set date related props ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setDates
            existingEvent.setDates(updatedEventData.start, updatedEventData.end, {
                allDay: updatedEventData.allDay
            });

            // --- Set event's extendedProps ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
            // eslint-disable-next-line no-plusplus
            for (var index = 0; index < extendedPropsToUpdate.length; index++) {
                var propName = extendedPropsToUpdate[index];
                existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName]);
            }
        };

        // Remove Event In Calendar (UI Only)
        // ------------------------------------------------
        function removeEventInCalendar(eventId) {
            calendar.getEventById(eventId).remove();
        }

        // Add new event
        // ------------------------------------------------
        btnSubmit.addEventListener('click', e => {
            console.log(111);
            if (btnSubmit.classList.contains('btn-add-event')) {
                if (isFormValid) {
                    addEvent();
                    bsAddEventSidebar.hide();
                }
            } else {
                // Update event
                // ------------------------------------------------
                if (isFormValid) {
                    updateEvent();
                    bsAddEventSidebar.hide();
                }
            }
        });

        // Call removeEvent function
        btnDeleteEvent.addEventListener('click', e => {
            removeEvent(parseInt(eventToUpdate.id));
            // eventToUpdate.remove();
            bsAddEventSidebar.hide();
        });

        // Reset event form inputs values
        // ------------------------------------------------
        function resetValues() {
            attend_id.value = '-1';
            start_time.value = null;
            end_time.value = null;
            employee.val('').trigger('change');
            comment.value = '';
        }

        // When modal hides reset input values
        addEventSidebar.addEventListener('hidden.bs.offcanvas', function () {
            resetValues();
        });

        // Hide left sidebar if the right sidebar is open
        btnToggleSidebar.addEventListener('click', e => {
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = 'اضافة دوام';
            }
            btnSubmit.innerHTML = 'اضافة';
            btnSubmit.classList.remove('btn-update-event');
            btnSubmit.classList.add('btn-add-event');
            btnDeleteEvent.classList.add('d-none');
            appCalendarSidebar.classList.remove('show');
            appOverlay.classList.remove('show');
        });

        // Calender filter functionality
        // ------------------------------------------------
        if (selectAll) {
            selectAll.addEventListener('click', e => {
                if (e.currentTarget.checked) {
                    document.querySelectorAll('.input-filter').forEach(c => (c.checked = 1));
                } else {
                    document.querySelectorAll('.input-filter').forEach(c => (c.checked = 0));
                }
                calendar.refetchEvents();
            });
        }

        if (filterInput) {
            filterInput.forEach(item => {
                item.addEventListener('click', () => {
                    document.querySelectorAll('.input-filter:checked').length < document.querySelectorAll('.input-filter').length
                        ? (selectAll.checked = false)
                        : (selectAll.checked = true);
                    calendar.refetchEvents();
                });
            });
        }

        // Jump to date on sidebar(inline) calendar change
        inlineCalInstance.config.onChange.push(function (date) {
            calendar.changeView(calendar.view.type, moment(date[0]).format('YYYY-MM-DD'));
            modifyToggler();
            appCalendarSidebar.classList.remove('show');
            appOverlay.classList.remove('show');
        });
    })();
});
