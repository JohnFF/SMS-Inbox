<style>
.unread_message {
    background-color: #98FB98;
    color: #98FB98;   
}
</style>

<table>
    <tr>
        <th>Message</th><!--<th>To</th>--><th>From</th><th>Sent</th><th>Options</th>
    </tr>
    {foreach from=$inboundSmsMessages item=eachInboundSmsMessage}
    <tr id="row_activity_id_{$eachInboundSmsMessage.id}" {if 0 == $eachInboundSmsMessage.read}class="unread_message"{/if}>
        <td>{$eachInboundSmsMessage.details}</td>
        <!-- <td><a href="/civicrm/contact/view?reset=1&cid={$eachInboundSmsMessage.source_contact_id}">{$eachInboundSmsMessage.to}</a></td> -->
        <td><a href="/civicrm/contact/view?reset=1&cid={$eachInboundSmsMessage.source_contact_id}">{$eachInboundSmsMessage.from}</td>
        <td>{$eachInboundSmsMessage.activity_date_time}</td>
        <td>
            {if $eachInboundSmsMessage.read}
                <a href="" class="markAsUnreadButton" data-activity_id="{$eachInboundSmsMessage.id}">Mark as unread</a>
            {else}
                <a href="" class="markAsReadButton" data-activity_id="{$eachInboundSmsMessage.id}">Mark as read </a>
            {/if}
             | 
            <a class="replyButton" href="">Reply</a> | 
            <a class="attachToContactButton" href="">Attach number to contact</a>
        </td>
    </tr>
    {/foreach}
</table>
<input type="button" value="Mark all as read."></input>

<script type="text/javascript">var read_custom_field_id = "{$readCustomField}";</script>

{crmScript ext=com.civifirst.smsinbox file=js/smsinbox.js}
{crmStyle ext=com.civifirst.smsinbox file=css/smsinbox.css}