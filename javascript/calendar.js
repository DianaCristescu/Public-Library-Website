const months = [ "January", "February", "March", "April", "May", "June", 
           "July", "August", "September", "October", "November", "December" ];
const today = new Date();
const currentYear = today.getFullYear();
const currentMonth = today.getMonth();

let selectedYear = currentYear;
let selectedMonth = currentMonth;
let eventMap = {};

const calendarDates = document.querySelector('.calendar-dates');
const calendarMonthYear = document.querySelector('.calendar-month-year');
const nextBtn = document.querySelector('.calendar-next');
const prevBtn = document.querySelector('.calendar-prev');

setDatesAndTitle(selectedYear, selectedMonth);
fetchCalendarData();

nextBtn.addEventListener('click', function () {
    if (selectedMonth == 11) {
        selectedMonth = 0;
        selectedYear += 1;
    } else {
        selectedMonth += 1;
    }

    setDatesAndTitle(selectedYear, selectedMonth);
});

prevBtn.addEventListener('click', function () {
    if (selectedMonth == 0) {
        selectedMonth = 11;
        selectedYear -= 1;
    } else {
        selectedMonth -= 1;
    }

    setDatesAndTitle(selectedYear, selectedMonth);
});

function fetchCalendarData() {
    fetch('api/get_calendar_events.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) return;

            // Process Loans
            data.loans.forEach(loan => {
                markDateRange(loan.borrow_date, loan.due_date, loan.title, 'loan');
            });

            // Process Reservations
            data.reservations.forEach(reservation => {
                markDateRange(reservation.start_date, reservation.end_date, reservation.title, 'reservation');
            });

            // Render calendar
            setDatesAndTitle(selectedYear, selectedMonth);
        })
        .catch(err => console.error(err));
}

// Fix the Timezone/UTC
function getLocalISODate(dateObj) {
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
    const day = String(dateObj.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function markDateRange(startDateStr, endDateStr, title, type) {
    const startParts = startDateStr.split('-');
    const endParts = endDateStr.split('-');
    
    // Create dates at midnight local time
    let d = new Date(startParts[0], startParts[1] - 1, startParts[2]);
    let end = new Date(endParts[0], endParts[1] - 1, endParts[2]);

    while (d <= end) {
        const dateKey = getLocalISODate(d);

        if (!eventMap[dateKey]) {
            eventMap[dateKey] = [];
        }

        const isDuplicate = eventMap[dateKey].some(evt => 
            evt.type === type && evt.title === title
        );

        if (!isDuplicate) {
            eventMap[dateKey].push({ type: type, title: title });
        }
        
        d.setDate(d.getDate() + 1);
    }
}

function setDatesAndTitle(year, month) {
    let days = getEuropeanCalendarDays(year, month);
    let htmlDates = '';

    days.forEach(day => {
        const offset = day.getTimezoneOffset();
        const localDay = new Date(day.getTime() - (offset*60*1000));
        const dateKey = localDay.toISOString().split('T')[0];

        const isCurrentDate = today.toDateString() == day.toDateString() ? 'calendar-today' : '';
        const isGrayed = day.getMonth() == month ? '' : 'calendar-grayed-date';
        const dateNum = day.getDate();

        // Check for events
        let eventsHtml = '';
        if (eventMap[dateKey]) {
            eventMap[dateKey].forEach(evt => {
                if (evt.type === 'loan') {
                    eventsHtml += `<span class="calendar-event-loan"><i class="fa-regular fa-house"></i>${evt.title}</span>`;
                }
                if (evt.type === 'reservation') {
                    eventsHtml += `<span class="calendar-event-reservation"><i class="fa-regular fa-calendar-check"></i>${evt.title}</span>`;
                }
            });
        }

        // Add events to HTML
        htmlDates += `
            <div class="calendar-date ${isGrayed} ${isCurrentDate}">
                <span>${dateNum}</span>
                <div class="calendar-events">${eventsHtml}</div>
            </div>`;
    });

    calendarDates.innerHTML = htmlDates;
    calendarMonthYear.innerHTML = `${months[month]} ${year}`;
}

function getEuropeanCalendarDays(year, month) {
    const date = new Date(year, month, 1);
    const days = [];

    // Calculate how many days to move back to get to Monday
    // If getDay() is 0 (Sunday), we move back 6 days.
    // If getDay() is 1 (Monday), we move back 0 days.
    const dayOfWeek = date.getDay();
    const diff = (dayOfWeek === 0 ? 6 : dayOfWeek - 1);
    
    date.setDate(date.getDate() - diff);

    // Fill 42 days (6 rows of 7 days)
    for (let i = 0; i < 42; i++) {
        days.push(new Date(date));
        date.setDate(date.getDate() + 1);
    }

    return days;
}