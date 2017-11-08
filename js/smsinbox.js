function markAsRead(activity_id) {
    var updateParams = {
        "sequential": 1,
        "id": activity_id,
        "activity_type_id": "Inbound SMS"
    };

    updateParams[read_custom_field_id] = 1;

    var rowId = '#row_activity_id_' + activity_id;

    var button = cj(rowId).find('.markAsReadButton');

    CRM.api3('Activity', 'create', updateParams).done(function(result) {
        cj(rowId).removeClass('unread_message');
        button.addClass('markAsUnreadButton');
        button.removeClass('markAsReadButton');
        button.text('Mark as unread');
    });

    return false;
}

cj(document).on('click', '.markAsReadButton', function() {
    markAsRead(cj(this).attr('data-activity_id'));
    return false;
});

cj(document).on('click', '.markAsUnreadButton', function() {

    var updateParams = {
        "sequential": 1,
        "id": cj(this).attr('data-activity_id'),
        "activity_type_id": "Inbound SMS",
    };

    updateParams[read_custom_field_id] = 0;

    var activity_id = cj(this).attr('data-activity_id');
    var button = cj(this);

    CRM.api3('Activity', 'create', updateParams).done(function(result) {
        cj('#row_activity_id_' + activity_id).addClass('unread_message');
        button.text('Mark as read');
        button.removeClass('markAsUnreadButton');
        button.addClass('markAsReadButton');
    });

    return false;
});


cj(document).on('click', '#markAllAsReadButton', function() {
    cj('.unread_message').each(function (){
        markAsRead(cj(this).attr('data-activity_id'));
    });
});