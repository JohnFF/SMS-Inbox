<table>
    <tr>
        <th>Message</th><!--<th>To</th>--><th>From</th><th>Sent</th><th>Options</th>
    </tr>
    {foreach from=$inboundSmsMessages item=eachInboundSmsMessage}
    <tr>
        <td>{$eachInboundSmsMessage.details}</td>
        <!-- <td><a href="/civicrm/contact/view?reset=1&cid={$eachInboundSmsMessage.source_contact_id}">{$eachInboundSmsMessage.to}</a></td> -->
        <td><a href="/civicrm/contact/view?reset=1&cid={$eachInboundSmsMessage.source_contact_id}">{$eachInboundSmsMessage.from}</td>
        <td>{$eachInboundSmsMessage.activity_date_time}</td>
        <td><a href="">Mark as read </a>| <a href="">Mark as unread</a> | <a href="">Reply</a> | <a href="">Attach number to contact</a></td>
    </tr>
    {/foreach}
</table>
<input type="button" value="Mark all as read."></input>