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
      calendarsColor = {
        Business: 'primary',
        Holiday: 'success',
        Personal: 'danger',
        Family: 'warning',
        ETC: 'info'
      },
      offcanvasTitle = document.querySelector('.offcanvas-title'),
      btnToggleSidebar = document.querySelector('.btn-toggle-sidebar'),
      btnSubmit = document.querySelector('#addEventBtn'),
      btnDeleteEvent = document.querySelector('.btn-delete-event'),
      btnEditEvent = document.querySelector('.btn-edit-event'),
      btnCancel = document.querySelector('.btn-cancel'),
      eventTitle = document.querySelector('#eventTitle'),
      eventStartDate = document.querySelector('#eventStartDate'),
      eventEndDate = document.querySelector('#eventEndDate'),
      eventUrl = document.querySelector('#eventURL'),
      eventLabel = $('#eventLabel'), // ! Using jquery vars due to select2 jQuery dependency
      eventGuests = $('#eventGuests'), // ! Using jquery vars due to select2 jQuery dependency
      eventLocation = document.querySelector('#eventLocation'),
      eventDescription = document.querySelector('#eventDescription'),
      allDaySwitch = document.querySelector('.allDay-switch'),
      selectAll = document.querySelector('.select-all'),
      filterInput = [].slice.call(document.querySelectorAll('.input-filter')),
      inlineCalendar = document.querySelector('.inline-calendar'),
      movements = [];

    let eventToUpdate,
      currentEvents = events, // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
      isFormValid = false,
      inlineCalInstance;

    console.log(addEventSidebar)

    // Init event Offcanvas
    const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

    //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
    // Event Label (select2)
    if (eventLabel.length) {
      function renderBadges(option) {
        if (!option.id) {
          return option.text;
        }
        var $badge =
          "<span class='badge badge-dot bg-" + $(option.element).data('label') + " me-2'> " + '</span>' + option.text;

        return $badge;
      }
      select2Focus(eventLabel);
      eventLabel.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: eventLabel.parent(),
        templateResult: renderBadges,
        templateSelection: renderBadges,
        minimumResultsForSearch: -1,
        escapeMarkup: function (es) {
          return es;
        }
      });
    }

    // Event Guests (select2)
    if (eventGuests.length) {
      function renderGuestAvatar(option) {
        if (!option.id) {
          return option.text;
        }
        var $avatar =
          "<div class='d-flex flex-wrap align-items-center'>" +
          "<div class='avatar avatar-xs me-2'>" +
          "<img src='" +
          assetsPath +
          'img/avatars/' +
          $(option.element).data('avatar') +
          "' alt='avatar' class='rounded-circle' />" +
          '</div>' +
          option.text +
          '</div>';

        return $avatar;
      }
      select2Focus(eventGuests);
      eventGuests.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: eventGuests.parent(),
        closeOnSelect: false,
        templateResult: renderGuestAvatar,
        templateSelection: renderGuestAvatar,
        escapeMarkup: function (es) {
          return es;
        }
      });
    }

    // Event start (flatpicker)
    if (eventStartDate) {
      var start = eventStartDate.flatpickr({
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
    if (eventEndDate) {
      var end = eventEndDate.flatpickr({
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

      const m = movements.find(m => m.id == eventToUpdate.id);
      
      const info_movement = `
        <div class="demo-inline-spacing mt-4">
          <div class="list-group list-group-flush">
            ${m.title ? `<span href="javascript:void(0);" class="list-group-item waves-effect"><b>Actividad: </b>${m.title}</span>` : ""}
            <span href="javascript:void(0);" class="list-group-item waves-effect"><b>Fecha: </b>${m.date}</span>
            <span href="javascript:void(0);" class="list-group-item waves-effect"><b>Valor: </b>${formatPrice(parseFloat(m.value))}</span>
            <span href="javascript:void(0);" class="list-group-item waves-effect"><b>Finca: </b>${m.farm.name}</span>
            ${m.provider_id ? `<span href="javascript:void(0);" class="list-group-item waves-effect"><b>Proveedor: </b>${m.provider.name}</span>` : ""}
            ${m.number_bill ? `<span href="javascript:void(0);" class="list-group-item waves-effect"><b># Factura: </b>${m.number_bill}</span>` : ""}
            ${m.support ? `<a href="${base_url(['uploads', m.support])}" target="_blank" class="list-group-item list-group-item-action waves-effect"><b>Soporte: </b><i class="ri-eye-2-line"></i></a>` : ""}
            ${m.seller ? `<span href="javascript:void(0);" class="list-group-item waves-effect"><b>Pagado por: </b>${m.seller}</span>` : ""}
          </div>
        </div>
      `;

      btnEditEvent.addEventListener("click", e => {
        window.location.href = base_url(['dashboard/movements/edit', m.id]);
      })

      $('.info_movement').html(info_movement)

      bsAddEventSidebar.show();
      // For update event set offcanvas title text: Update Event
      if (offcanvasTitle) {
        offcanvasTitle.innerHTML = `${m.type.name} #${m.resolution}`;
      }
      // btnSubmit.innerHTML = 'Update';
      // btnSubmit.classList.add('btn-update-event');
      // btnSubmit.classList.remove('btn-add-event');
      // btnDeleteEvent.classList.remove('d-none');

      // eventTitle.value = eventToUpdate.title;
      // start.setDate(eventToUpdate.start, true, 'Y-m-d');
      // eventToUpdate.allDay === true ? (allDaySwitch.checked = true) : (allDaySwitch.checked = false);
      // eventToUpdate.end !== null
      //   ? end.setDate(eventToUpdate.end, true, 'Y-m-d')
      //   : end.setDate(eventToUpdate.start, true, 'Y-m-d');
      // eventLabel.val(eventToUpdate.extendedProps.calendar).trigger('change');
      // eventToUpdate.extendedProps.location !== undefined
      //   ? (eventLocation.value = eventToUpdate.extendedProps.location)
      //   : null;
      // eventToUpdate.extendedProps.guests !== undefined
      //   ? eventGuests.val(eventToUpdate.extendedProps.guests).trigger('change')
      //   : null;
      // eventToUpdate.extendedProps.description !== undefined
      //   ? (eventDescription.value = eventToUpdate.extendedProps.description)
      //   : null;

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
      const fcPrevButton = document.querySelector('.fc-prev-button');
      const fcNextButton = document.querySelector('.fc-next-button');
      const fcHeaderToolbar = document.querySelector('.fc-header-toolbar');
      fcPrevButton.classList.add('btn', 'btn-sm', 'btn-icon', 'btn-outline-secondary', 'me-2');
      fcNextButton.classList.add('btn', 'btn-sm', 'btn-icon', 'btn-outline-secondary', 'me-4');
      fcHeaderToolbar.classList.add('row-gap-4', 'gap-2');
      fcSidebarToggleButton.classList.remove('fc-button-primary');
      fcSidebarToggleButton.classList.add('d-lg-none', 'd-inline-block', 'ps-0');
      while (fcSidebarToggleButton.firstChild) {
        fcSidebarToggleButton.firstChild.remove();
      }
      fcSidebarToggleButton.setAttribute('data-bs-toggle', 'sidebar');
      fcSidebarToggleButton.setAttribute('data-overlay', '');
      fcSidebarToggleButton.setAttribute('data-target', '#app-calendar-sidebar');
      fcSidebarToggleButton.insertAdjacentHTML('beforeend', '<i class="ri-menu-line ri-24px text-body"></i>');
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
    async function fetchEvents(info, successCallback) {

      const start = info.start; // fecha inicio
      const end = info.end;     // fecha fin

      
      const data = {
        start: toYMD(start), end: toYMD(end)
      }
      const url = base_url(['dashboard/calendar']);

      const response = await fetchHelper.post(url, data, {}, 500);

      let calendars = selectedCalendars();

      movements.length = 0;
      movements.push(...response.movements);

      currentEvents = response.movements.reduce((acc, m) => {
        const event = {
          id: m.id,
          url: '',
          title: m.title ? m.title : m.type.name,
          start: m.date,
          end: m.date,
          allDay: true,
          extendedProps: {
            movement_type: m.movement_type_id,
            state_id: m.state_id
          }
        };
        acc.push(event);
        return acc;
      }, []);

      const total_compras = response.movements.reduce((acc, m) => {
        if(m.type.id == 1)
          acc += parseFloat(m.value);
        return acc;
      }, 0);

      const total_jornal = response.movements.reduce((acc, m) => {
        if(m.type.id == 3 && m.state_id == 3)
          acc += parseFloat(m.value);
        return acc;
      }, 0);

      $('#detail_1').html(`<b>${data.start} / ${data.end}:</b> ${formatPrice(total_compras)}`)
      $('#detail_3').html(`<b>${data.start} / ${data.end}:</b> ${formatPrice(total_jornal)}`)



      // Calcular los calendarios seleccionados
      // let calendars = selectedCalendars();
      // Fetch Events from API endpoint reference
      /* $.ajax(
        {
          url: '../../../app-assets/data/app-calendar-events.js',
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
      // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
      // You should make an API call, look into above commented API call for reference
      let selectedEvents = currentEvents.filter((function (event) {
        // console.log(event.extendedProps.calendar.toLowerCase());
        return calendars.includes(event.extendedProps.movement_type);
      }));
      // if (selectedEvents.length > 0) {
      successCallback(selectedEvents);
      // }
    }

    // Init FullCalendar
    // ------------------------------------------------
    let calendar = new Calendar(calendarEl, {
      initialView: 'listMonth',
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
      eventClassNames: function ({ event: calendarEvent }) {
        const state = states.find(s => s.id == calendarEvent._def.extendedProps.state_id);

        // Background Color
        return [`${state.color_background} ${state.color_font}`];
      },
      dateClick: function (info) {

        Swal.fire({
          title: 'Crear nuevo movimiento',
          confirmButtonText: 'Compra',
          cancelButtonText: 'Actividad',
          customClass: {
              confirmButton: 'btn btn-primary waves-effect',
              cancelButton: 'btn btn-danger waves-effect',
          },
        }).then((result) => {
          if(result.isConfirmed){
            localStorage.setItem("dateMovement", info.dateStr);
            window.location.href = base_url(['dashboard/movements/new/1']);
          }else if(result.isDismissed && result.dismiss != "backdrop"){
            localStorage.setItem("dateMovement", info.dateStr);
            window.location.href = base_url(['dashboard/movements/new/2']);
          }
        })
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

    const eventForm = document.getElementById('eventForm');
    const fv = FormValidation.formValidation(eventForm, {
      fields: {
        eventTitle: {
          validators: {
            notEmpty: {
              message: 'Please enter event title '
            }
          }
        },
        eventStartDate: {
          validators: {
            notEmpty: {
              message: 'Please enter start date '
            }
          }
        },
        eventEndDate: {
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
            return '.mb-5';
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
    function addEvent(eventData) {
      // ? Add new event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response

      currentEvents.push(eventData);
      calendar.refetchEvents();

      // ? To add event directly to calender (won't update currentEvents object)
      // calendar.addEvent(eventData);
    }

    // Update Event
    // ------------------------------------------------
    function updateEvent(eventData) {
      // ? Update existing event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response
      eventData.id = parseInt(eventData.id);
      currentEvents[currentEvents.findIndex(el => el.id === eventData.id)] = eventData; // Update event by id
      calendar.refetchEvents();

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
      currentEvents = currentEvents.filter(function (event) {
        return event.id != eventId;
      });
      calendar.refetchEvents();

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
    // btnSubmit.addEventListener('click', e => {
    //   if (btnSubmit.classList.contains('btn-add-event')) {
    //     if (isFormValid) {
    //       let newEvent = {
    //         id: calendar.getEvents().length + 1,
    //         title: eventTitle.value,
    //         start: eventStartDate.value,
    //         end: eventEndDate.value,
    //         startStr: eventStartDate.value,
    //         endStr: eventEndDate.value,
    //         display: 'block',
    //         extendedProps: {
    //           location: eventLocation.value,
    //           guests: eventGuests.val(),
    //           calendar: eventLabel.val(),
    //           description: eventDescription.value
    //         }
    //       };
    //       if (eventUrl.value) {
    //         newEvent.url = eventUrl.value;
    //       }
    //       if (allDaySwitch.checked) {
    //         newEvent.allDay = true;
    //       }
    //       addEvent(newEvent);
    //       bsAddEventSidebar.hide();
    //     }
    //   } else {
    //     // Update event
    //     // ------------------------------------------------
    //     if (isFormValid) {
    //       let eventData = {
    //         id: eventToUpdate.id,
    //         title: eventTitle.value,
    //         start: eventStartDate.value,
    //         end: eventEndDate.value,
    //         url: eventUrl.value,
    //         extendedProps: {
    //           location: eventLocation.value,
    //           guests: eventGuests.val(),
    //           calendar: eventLabel.val(),
    //           description: eventDescription.value
    //         },
    //         display: 'block',
    //         allDay: allDaySwitch.checked ? true : false
    //       };

    //       updateEvent(eventData);
    //       bsAddEventSidebar.hide();
    //     }
    //   }
    // });

    // Call removeEvent function
    btnDeleteEvent.addEventListener('click', e => {
      removeEvent(parseInt(eventToUpdate.id));
      // eventToUpdate.remove();
      bsAddEventSidebar.hide();
    });

    // Reset event form inputs values
    // ------------------------------------------------
    function resetValues() {
      eventEndDate.value = '';
      eventUrl.value = '';
      eventStartDate.value = '';
      eventTitle.value = '';
      eventLocation.value = '';
      allDaySwitch.checked = false;
      eventGuests.val('').trigger('change');
      eventDescription.value = '';
    }

    // When modal hides reset input values
    addEventSidebar.addEventListener('hidden.bs.offcanvas', function () {
      resetValues();
    });

    // Hide left sidebar if the right sidebar is open
    btnToggleSidebar.addEventListener('click', e => {
      if (offcanvasTitle) {
        offcanvasTitle.innerHTML = 'Add Event';
      }
      btnSubmit.innerHTML = 'Add';
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
    // inlineCalInstance.config.onChange.push(function (date) {
    //   calendar.changeView(calendar.view.type, moment(date[0]).format('YYYY-MM-DD'));
    //   modifyToggler();
    //   appCalendarSidebar.classList.remove('show');
    //   appOverlay.classList.remove('show');
    // });
  })();
});
