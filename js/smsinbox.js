function updateSmsState(activity_id, read_status) {
    var updateParams = {
        "activity_id": activity_id,
        "read_status": read_status 
    };

    var rowId = '#row_activity_id_' + activity_id;
    var button = cj("#readStateChangeButton-" + activity_id);
    console.log(updateParams);
    CRM.api3('Sms', 'updatestate', updateParams).done(function(result) {
      if (result['values']['read_status'] == 1) {
        console.log("read status is 1");
        cj(rowId).removeClass('unread_message');
        button.removeClass('markAsReadButton');
        button.addClass('markAsUnreadButton');
        button.text('Mark as unread');
      } 
      else {
        console.log("read status is 0");
        cj(rowId).addClass('unread_message');
        button.removeClass('markAsUnreadButton');
        button.addClass('markAsReadButton');
        button.text('Mark as read');

      }
    });

}

cj(document).on('click', '.markAsReadButton', function() {
    updateSmsState(cj(this).attr('data-activity_id'), 1);
    return false;
});
cj(document).on('click', '.markAsUnreadButton', function() {
    updateSmsState(cj(this).attr('data-activity_id'), 0);
    return false;
});

cj(document).on('click', '#markAllAsReadButton', function() {
    cj('.unread_message').each(function (){
        updateSmsState(cj(this).attr('data-activity_id'), 1);
    });
});
