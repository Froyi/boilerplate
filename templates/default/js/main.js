function hideNotifications() {
    $('.js-notification-container').hide();
}

$(document).ready(function () {
    window.setTimeout(hideNotifications, 5000); // 5 seconds
});

$(document).on('click', '.js-notification', function () {
    hideNotifications();
});