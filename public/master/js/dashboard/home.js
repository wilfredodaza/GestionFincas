'use strict';

let direction = 'ltr';

if (isRtl) {
  direction = 'rtl';
}

$(() => {
    const calendarEl = document.getElementById('calendar');

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
        eventClassNames: function ({ event: calendarEvent }) {
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
            offcanvasTitle.innerHTML = 'Add Event';
          }
          btnSubmit.innerHTML = 'Add';
          btnSubmit.classList.remove('btn-update-event');
          btnSubmit.classList.add('btn-add-event');
          btnDeleteEvent.classList.add('d-none');
          eventStartDate.value = date;
          eventEndDate.value = date;
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
})