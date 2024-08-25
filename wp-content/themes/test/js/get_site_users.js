jQuery(document).ready(function($) {
    function updateTrafficCount() {
        $.ajax({
            url: ajax_object.ajax_url,
            method: 'GET',
            success: function(data) {
                $('#site-traffic').text('Общая посещаемость сайта: ' + data);
            },
            error: function() {
                $('#site-traffic').text('Ошибка при загрузке данных.');
            }
        });
    }
    setInterval(updateTrafficCount, 20000);
    updateTrafficCount();
});
