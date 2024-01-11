document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    longPressDelay: 0,
    eventLongPressDelay: 0,
    selectLongPressDelay: 0,
    validRange: {
      start: '2024-01-01',
      end: '2024-02-01',
    },
    selectable: true,
    selectMirror: true,
    selectAllow: function (selectInfo) {
      var startDate = new Date(selectInfo.startStr);
      var endDate = new Date(selectInfo.endStr);
      var isAllowed = true;
      calendar.getEvents().forEach(function (event) {
        if (event.rendering === 'background') {
          var bookedStart = new Date(event.start);
          var bookedEnd = new Date(event.end);
          if (
            (startDate < bookedEnd && startDate >= bookedStart) ||
            (endDate > bookedStart && endDate <= bookedEnd) ||
            (startDate <= bookedStart && endDate >= bookedEnd)
          ) {
            isAllowed = false;
          }
        }
      });
      return isAllowed;
    },
    select: function (info) {
      if (!calendar.startSelection || calendar.endSelection) {
        clearSelections();
        calendar.startSelection = info.startStr;
        calendar.endSelection = null;
        markDate(calendar.startSelection);

        document.getElementById('startDate').value = formatDate(info.startStr);
      } else {
        let endDateStr = info.startStr;
        if (new Date(endDateStr) >= new Date(calendar.startSelection)) {
          calendar.endSelection = endDateStr;
          markRange(calendar.startSelection, calendar.endSelection);

          document.getElementById('endDate').value = formatDate(endDateStr);
        }
      }
    },
  });
  calendar.render();
  calendar.startSelection = null;
  calendar.endSelection = null;

  document.body.addEventListener('htmx:afterRequest', function (event) {
    var responseData = JSON.parse(event.detail.xhr.responseText);
    updateCalendarEvents(responseData);
    clearSelections();
  });

  function clearSelections() {
    document.querySelectorAll('.selected-range').forEach(function (el) {
      el.classList.remove('selected-range');
    });
    calendar.startSelection = null;
    calendar.endSelection = null;
  }

  function clearMarkedDates() {
    document.querySelectorAll('.selected-range').forEach(function (el) {
      el.classList.remove('selected-range');
    });
  }

  function markDate(dateStr) {
    let cell = document.querySelector(`[data-date='${dateStr}']`);
    if (cell) {
      cell.classList.add('selected-range');
    }
  }

  function markRange(startDateStr, endDateStr) {
    clearMarkedDates();
    let startDate = new Date(startDateStr);
    let endDate = new Date(endDateStr);
    endDate.setDate(endDate.getDate() + 1);
    while (startDate < endDate) {
      markDate(startDate.toISOString().split('T')[0]);
      startDate.setDate(startDate.getDate() + 1);
    }
  }

  function handleDatePickerChange() {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    let startDate = startDateInput.value;
    let endDate = endDateInput.value;

    if (startDate) {
      calendar.startSelection = startDate;
      markDate(startDate);
    }

    if (endDate) {
      calendar.endSelection = endDate;
      markRange(startDate, endDate);
    }
  }

  document
    .getElementById('startDate')
    .addEventListener('change', handleDatePickerChange);
  document
    .getElementById('endDate')
    .addEventListener('change', handleDatePickerChange);

  function updateCalendarEvents(eventsData) {
    calendar.removeAllEvents();
    calendar.addEventSource(
      eventsData.map(function (event) {
        let adjustedEndDate = new Date(event.end);
        adjustedEndDate.setDate(adjustedEndDate.getDate() + 1);

        return {
          ...event,
          end: adjustedEndDate.toISOString().split('T')[0],
          rendering: 'background',
          color: '#ff9f89',
        };
      })
    );
  }

  function formatDate(dateStr) {
    return new Date(dateStr).toISOString().split('T')[0];
  }

  document.querySelectorAll('[hx-get="getBookings.php"]').forEach((button) => {
    button.addEventListener('click', function () {
      document.getElementById('selectedRoomType').value =
        this.getAttribute('hx-vals');
    });
  });

  document
    .getElementById('bookingForm')
    .addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const data = {
        roomType: formData.get('roomType'),
        startDate: calendar.startSelection,
        endDate: calendar.endSelection,
        activities: formData.getAll('activity[]'),
      };

      fetch('calculatePrice.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      })
        .then((response) => response.json())
        .then((data) => {
          document.getElementById(
            'totalCostDisplay'
          ).textContent = `Total Price: $${data.totalPrice}`;
          document.getElementById('bookingModal').style.display = 'flex';
        })
        .catch((error) => {
          console.error('Error:', error);
        });
    });

  var closeBtn = document.getElementsByClassName('close')[0];
  closeBtn.onclick = function () {
    document.getElementById('bookingModal').style.display = 'none';
  };
  window.onclick = function (event) {
    if (event.target === document.getElementById('bookingModal')) {
      document.getElementById('bookingModal').style.display = 'none';
    }
  };

  document.getElementById('payButton').addEventListener('click', function () {
    var guestName = document.getElementById('guestName').value;
    var paymentKey = document.getElementById('paymentKey').value;

    submitBookingForm(guestName, paymentKey);
  });

  function submitBookingForm(guestName, paymentKey) {
    var formData = new FormData(document.getElementById('bookingForm'));
    var data = {
      guestName: guestName,
      paymentKey: paymentKey,
      activities: [],
    };

    formData.getAll('activity[]').forEach((activity) => {
      data.activities.push(activity);
    });

    data.startDate = calendar.startSelection;
    data.endDate = calendar.endSelection;

    data.roomType = document.getElementById('selectedRoomType').value;

    fetch('booking.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            'Network response was not ok: ' + response.statusText
          );
        }
        return response.json();
      })
      .then((responseData) => {
        console.log('Success:', responseData);
        console.log('vi kom också hit');
        if (responseData.status === 'success') {
          localStorage.setItem('bookingInfo', JSON.stringify(responseData));
          console.log('vi kom hit');
          window.location.href = '/public/bookingSuccesful.php';
        }
        document.getElementById('bookingModal').style.display = 'none';
      })
      .catch((error) => {
        console.log(responseData);
        console.error('Error:', error);
      });
  }

  //document.getElementById('cheapRoomButton').click();

  fetch('getPrices.php')
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 'success') {
        displayRoomPrices(data.roomPrices);
        displayHotelFeatures(data.hotelFeatures);
      } else {
        console.error('Error fetching data: ', data.message);
      }
    })
    .catch((error) => console.error('Error:', error));

  function displayRoomPrices(roomPrices) {
    roomPrices.forEach((room) => {
      let priceTagId;
      switch (room.room_type) {
        case 'Budget':
          priceTagId = 'cheapRoomPrice';
          break;
        case 'Standard':
          priceTagId = 'mediumRoomPrice';
          break;
        case 'Luxury':
          priceTagId = 'expensiveRoomPrice';
          break;
      }

      const priceTag = document.getElementById(priceTagId);
      if (priceTag) {
        priceTag.textContent = `Pris per natt: ${room.price} `;
      }
    });
  }

  function displayHotelFeatures(hotelFeatures) {
    console.log('Hotel Features:', hotelFeatures);
  }

  fetch('getActivities.php')
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 'success') {
        createActivityCheckboxes(data.activities);
      } else {
        console.error('Error fetching activities:', data.message);
      }
    })
    .catch((error) => console.error('Error:', error));
});

function createActivityCheckboxes(activities) {
  const container = document.getElementById('activitiesContainer');
  activities.forEach((activity) => {
    const activityWrapper = document.createElement('div');
    activityWrapper.classList.add('activity-wrapper');

    const h2 = document.createElement('h2');
    h2.classList.add('activity-label');

    const activityInfo = document.createElement('div');
    activityInfo.classList.add('activity-info');

    const activityName = document.createElement('span');
    activityName.textContent = activity.name;
    activityInfo.appendChild(activityName);

    const activityPrice = document.createElement('span');
    activityPrice.textContent = `  $${activity.cost} `;
    activityInfo.appendChild(activityPrice);

    h2.appendChild(activityInfo);

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.name = 'activity[]';
    checkbox.value = activity.name;
    checkbox.classList.add('checkbox');

    const img = document.createElement('img');
    img.src = `./images/${activity.image_url}`;
    img.alt = activity.name;
    img.classList.add('activity-image');

    const description = document.createElement('p');
    description.classList.add('activity-description');
    description.textContent = activity.description;

    activityWrapper.addEventListener('click', () => {
      checkbox.checked = !checkbox.checked;
      activityWrapper.classList.toggle('checked-activity', checkbox.checked);
    });

    h2.appendChild(checkbox);
    h2.appendChild(img);
    h2.appendChild(description);

    activityWrapper.appendChild(h2);

    container.appendChild(activityWrapper);
  });
}

function markRange(startDateStr, endDateStr) {
  let startDate = new Date(startDateStr);
  let endDate = new Date(endDateStr);
  endDate.setDate(endDate.getDate() + 1);
  while (startDate < endDate) {
    markDate(startDate.toISOString().split('T')[0]);
    startDate.setDate(startDate.getDate() + 1);
  }
}
