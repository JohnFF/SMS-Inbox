{* Set the handle of the read_custom_field_id for use by JS API calls *}
<script type="text/javascript" id='mark_all_as_read'>var read_custom_field_id = "{$readCustomField}";</script>

{* Display inbound messages *}
<table>
    <tr>
        <th>Message</th><!--<th>To</th>--><th>From</th><th>Sent</th><th>Options</th>
    </tr>
    {foreach from=$inboundSmsMessages item=eachInboundSmsMessage}
    <tr id="row_activity_id_{$eachInboundSmsMessage.id}" {if 0 == $eachInboundSmsMessage.read}class="unread_message"{/if} data-activity_id="{$eachInboundSmsMessage.id}">
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
            <a class="replyButton crm-popup" href="/civicrm/smsinbox/sendsms">Reply</a> | 
            <a class="attachToContactButton" href="">Attach number to contact</a>
        </td>
    </tr>
    {/foreach}
</table>

{* Buttons at bottom of the page *}
<a id='markAllAsReadButton' class="button">Mark all as read</a>
<a class="button crm-popup" href="/civicrm/smsinbox/sendsms">Send SMS message</a>

{crmScript ext=com.civifirst.smsinbox file=js/smsinbox.js}
{crmStyle ext=com.civifirst.smsinbox file=css/smsinbox.css}