{include file="user/header.tpl"}
<div class="wl-borderbox">
    <h3>{gt text="Main-Categories"}</h3>

    <div class="z-clearfix">
        <div class="wl-catrow z-clearfix">
            {assign var="count" value="0"}
            {foreach from=$categories item=category}
            {if $category.parent_id == "0"}

            {math equation="$count+1" assign="count"}

            <dl class="wl-cat">
                <dt class="wl-catname"><a href="{modurl modname=Weblinks type=user func=category cid=$category.cat_id}" class="wl-catmain">{$category.title|safetext}</a> ({countsublinks cid=$category.cat_id}){categorynewlinkgraphic cat=$category.cat_id}</dt>
                {if $category.cdescription ne ""}
                <dd class="wl-catdescr">{$category.cdescription|safehtml}</dd>
                {/if}
                {foreach from=$categories item=subcategory}
                {if $subcategory.parent_id == $category.cat_id}
                <dd class="wl-sub"><a href="{modurl modname=Weblinks type=user func=category cid=$subcategory.cat_id}" class="wl-catsub">{$subcategory.title|safetext}</a>{categorynewlinkgraphic cat=$subcategory.cat_id}</dd>
                {/if}
                {/foreach}
            </dl>

            {if $count == 2}
            {assign var="count" value="0"}
            {/if}
            {/if}
            {/foreach}
        </div>
    </div>

    <div class="wl-center wl-stats">{gt text="There are"} <strong>{$numrows}</strong> {gt text="link" plural="links" count=$numrows} {gt text="and"} <strong>{$catnum}</strong> {gt text="category" plural="categories" count=$catnum} {gt text="in the database"}</div>
</div>

{if $linkbox eq 1}
<div class="wl-borderbox z-clearfix">
    <div class="blocklast">
        <h4>{gt text="Last links"}</h4>
        {if $blocklast}
        <ol class="lastweblinks">
            {foreach from=$blocklast item=weblinks name=loop}
            <li>
                <a href="{modurl modname=Weblinks type=user func=visit lid=$weblinks.lid}"{if $helper.tb eq 1} target="_blank"{/if}>{$weblinks.title|safetext}</a>
            </li>
            {/foreach}
        </ol>
        {/if}
    </div>
    <div class="blockmostpopular">
        <h4>{gt text="Most-popular links"}</h4>
        {if $blockmostpop}
        <ol class="mostpopularweblinks">
            {foreach from=$blockmostpop item=weblinks name=loop}
            <li>
                <a href="{modurl modname=Weblinks type=user func=visit lid=$weblinks.lid}"{if $helper.tb eq 1} target="_blank"{/if}>{$weblinks.title|safetext}</a>
                <em>({$weblinks.hits|safetext})</em>
            </li>
            {/foreach}
        </ol>
        {/if}
    </div>
</div>
{/if}

{include file="user/footer.tpl"}