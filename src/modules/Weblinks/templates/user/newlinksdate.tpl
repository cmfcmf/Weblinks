{include file="user/header.tpl"}
<div class="wl-borderbox">

    <h3>{$dateview|safetext} - {$totallinks|safetext} {gt text="new link" plural="new links" count=$totallinks}</h3>

    {foreach from=$weblinks item=weblinks}
    <div class="wl-linkbox">
        {include file="weblinks_user_linkbox.html"}
    </div>
    {/foreach}

</div>
{include file="user/footer.tpl"}