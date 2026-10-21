

document.addEventListener('DOMContentLoaded', function () {
    const calendarElement = document.getElementById('appointmentCalendar');

    if (!calendarElement) {
        console.error('Không tìm thấy phần tử appointmentCalendar.');
        return;
    }

    // Đọc dữ liệu và URL từ data-*; không đặt cú pháp Blade trong JavaScript.
    let appointments = [];

    try {
        appointments = JSON.parse(calendarElement.dataset.events || '[]');
    } catch (error) {
        console.error('Không thể đọc dữ liệu lịch hẹn:', error);
        appointments = [];
    }

    const confirmUrlTemplate = calendarElement.dataset.confirmUrl;
    const rejectUrlTemplate = calendarElement.dataset.rejectUrl;

    const modalElement = document.getElementById('appointmentDetailModal');
    const detailModal = new bootstrap.Modal(modalElement);

    const confirmForm = document.getElementById('confirmForm');
    const rejectForm = document.getElementById('rejectForm');
    const appointmentActions = document.getElementById('appointmentActions');
    const processedNotice = document.getElementById('processedNotice');

    function setText(id, value) {
        document.getElementById(id).textContent = value || '—';
    }

    function formatDate(dateString) {
        if (!dateString) return '—';

        const parts = dateString.split('-');

        if (parts.length !== 3) return dateString;

        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }

    function formatTime(timeString) {
        if (!timeString) return '—';

        return timeString.substring(0, 5);
    }

    function showAppointment(event) {
        const data = event.extendedProps || {};
        const appointmentId = event.id;

        setText('detailTenant', data.tenant);
        setText('detailRoom', data.room);
        setText('detailAddress', data.address);
        setText('detailPhone', data.phone);
        setText('detailDate', formatDate(data.date));
        setText('detailTime', formatTime(data.time));
        setText('detailMessage', data.message);

        const statusElement = document.getElementById('detailStatus');

        statusElement.textContent = data.statusLabel || 'Không xác định';
        statusElement.className = 'status-badge';

        const statusClassMap = {
            pending: 'status-pending',
            confirmed: 'status-confirmed',
            completed: 'status-completed',
            cancelled: 'status-cancelled'
        };

        statusElement.classList.add(
            statusClassMap[data.status] || 'status-completed'
        );

        if (data.status === 'pending') {
            confirmForm.action = confirmUrlTemplate.replace(
                '__ID__',
                encodeURIComponent(appointmentId)
            );

            rejectForm.action = rejectUrlTemplate.replace(
                '__ID__',
                encodeURIComponent(appointmentId)
            );

            appointmentActions.classList.remove('d-none');
            processedNotice.classList.add('d-none');
        } else {
            appointmentActions.classList.add('d-none');
            processedNotice.classList.remove('d-none');
        }

        detailModal.show();
    }

    const statusColors = {
        pending: '#d69e00',
        confirmed: '#23856d',
        completed: '#718096',
        cancelled: '#cf6254'
    };

    const events = appointments
        .filter(function (appointment) {
            return appointment && appointment.start;
        })
        .map(function (appointment) {
            const props = appointment.extendedProps || {};
            const color = statusColors[props.status] || '#718096';

            return {
                id: String(appointment.id),
                title: appointment.title || 'Lịch xem phòng',
                start: appointment.start,
                allDay: false,
                extendedProps: props,
                backgroundColor: color,
                borderColor: color,
                textColor: '#ffffff'
            };
        });

    const calendar = new FullCalendar.Calendar(calendarElement, {
        locale: 'vi',
        initialView: 'timeGridWeek',
        firstDay: 1,
        nowIndicator: true,
        height: 'auto',
        expandRows: true,
        allDaySlot: false,
        weekends: true,
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        slotDuration: '00:30:00',
        scrollTime: '08:00:00',

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay,dayGridMonth'
        },

        buttonText: {
            today: 'Hôm nay',
            week: 'Tuần',
            day: 'Ngày',
            month: 'Tháng'
        },

        noEventsContent: 'Không có lịch hẹn trong khoảng thời gian này',
        events: events,

        eventClick: function (info) {
            info.jsEvent.preventDefault();
            showAppointment(info.event);
        }
    });

    calendar.render();
});

