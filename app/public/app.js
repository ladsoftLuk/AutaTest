
$(document).ready(function(){
    var ajax_call = function(){
            console.log('Ajax request received.');
            $.ajax({
            url: '/ajax',
            type: 'POST',
            dataType: 'json',
            async: true,
            success: function(data, status) {
            d = JSON.parse(data);
            $('#current').empty().append(d['url']);
        },
            error : function(xhr, textStatus, errorThrown) {
            console.log('Ajax request failed.');
        }
        });
    }
    setInterval(ajax_call, 500);
});
