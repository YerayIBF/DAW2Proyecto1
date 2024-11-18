$(document).ready(function() {
    $('#countrySelect').select2({
        templateResult: function(data) {
            if (!data.id) { return data.text; }
            var imageUrl = $(data.element).data('image');
            return $('<span><img src="' + imageUrl + '" class="me-2" style="width: 20px; height: 14px;" />' + data.text + '</span>');
        },
        templateSelection: function(data) {
            var imageUrl = $(data.element).data('image');
            return $('<span><img src="' + imageUrl + '" class="me-2" style="width: 20px; height: 14px;" />' + data.text + '</span>');
        }
    });
});
