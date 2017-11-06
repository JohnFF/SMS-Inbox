<h3>SMS Inbox</h3>
<table>
    <tr>
        <th>Message</th><th>To</th><th>From</th><th>Sent</th><th>Options</th>
    </tr>
    {foreach from=$inboundSmsMessages item=eachInboundSmsMessage}
    <tr>
        <td>{$eachInboundSmsMessage.details}</td>
        <td>{$eachInboundSmsMessage.to}</td>
        <td>{$eachInboundSmsMessage.from}</td>
        <td>{$eachInboundSmsMessage.datetime}</td>
        <td>Mark as read / Mark as unread / Reply / Attach number to contact</td>
    </tr>
    {/foreach}
</table>
