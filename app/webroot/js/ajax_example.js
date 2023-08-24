// 一応残す
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
// 一応残す
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

// ここから
$(document).ready(function() {
    $('.border_btn').click(function() {
        var itemId = $(this).data('id');
        $.ajax({
            url: '/comments/commentgood/'+itemId,    // Ajaxリクエストを処理するアクションのURL
            method: 'GET',
            
            success: function(data) {
                $('#comment'+itemId).html(data);
            }
        });
    });
});