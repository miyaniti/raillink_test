// webroot/js/ajax_example.js
$(document).ready(function() {
    $('#load-ajax').click(function() {
        $.ajax({
            url: '/comments/comment/:id', // Ajaxリクエストを処理するアクションのURL
            method: 'GET',
            success: function(data) {
                $('#ajax-content').html(data);
            }
        });
    });
});



// webroot/js/ajax_example.js
$(document).ready(function() {
    $('.border_btn08').click(function() {
        var itemId = $(this).data('id');
        $.ajax({
            url: '/comments/comment/'+itemId, // Ajaxリクエストを処理するアクションのURL
            method: 'GET',
            
            success: function(data) {
                $('#comment'+itemId).html(data);
            }
        });
    });
});