<div class="wl-topbox">
    <a href="{modurl modname='Weblinks' type='user' func='visit' lid=$weblinks.lid}" {if $modvars.Weblinks.targetblank == 1}target="_blank"{/if} >
        {$weblinks.title|safetext}
    </a>
    {newlinkgraphic time=$weblinks.date}{popgraphic hits=$weblinks.hits}
</div>

<div class="wl-centerbox z-clearfix">
    {if $modvars.Weblinks.thumber}
    <div class="wl-thumb">
        <a href="{modurl modname='Weblinks' type='user' func='visit' lid=$weblinks.lid}" {if $modvars.Weblinks.targetblank == 1}target="_blank"{/if} >
            <img src="http://image.thumber.de/?size={$modvars.Weblinks.thumbersize}&amp;url={$weblinks.url}" />
        </a>
    </div>
    {/if}

    {if $weblinks.description}
    <p>{$weblinks.description|notifyfilters:'weblinks.filter_hooks.linkfilter.filter'|safehtml}</p>
    {/if}

    {if $helper.showcat == 1}
    <p>{gt text="Category"}: {catpath cid=$weblinks.cat_id links=1 linkmyself=1}</p>
    {/if}

    <p>{gt text="Added on"}: {$weblinks.date|dateformat:"datebrief"} | {gt text="Hits"}: {$weblinks.hits}</p>

</div>

<div class="wl-bottombox">
    {linkbottommenu cid=$weblinks.cat_id lid=$weblinks.lid details=$helper.details}
</div>