{include file="admin/header.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=folder.gif set=icons/large __alt="Modify a category" __title="Modify a category"}</div>
    <form class="z-form" action="{modurl modname=Weblinks type=admin func=savemodcategory}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <fieldset>
                <legend>{gt text="Modify a category"}</legend>
                <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Weblinks"}" />
                <input type="hidden" name="cid" value="{$category.cat_id}" />
                <div class="z-formrow">
                    <label>{gt text="Name"}</label>
                    <input id="title" type="text" name="title" value="{$category.title|safetext}" size="51" maxlength="50" />
                </div>
                <div class="z-formrow">
                    <label>{gt text="Parent category"}</label>
                    <select id="pid" name="pid"><option value="0">{gt text="None"}</option>{catlist scat=0 sel=$category.parent_id}</select>
                </div>
                <div class="z-formrow">
                    <label>{gt text="Description"}</label>
                    <textarea id="description" name="cdescription" cols="50" rows="10">{$category.cdescription|safetext}</textarea>
                </div>
            </fieldset>
            <div class="z-formbuttons">
                {button src=button_ok.gif set=icons/small __alt="Modify category" __title="Modify category"}
                <a href="{modurl modname=Weblinks type=admin func=catview}">{img modname=core src=button_cancel.gif set=icons/small __alt="Back" __title="Back"}</a>
            </div>
        </div>
    </form>
</div>
{include file="admin/footer.tpl"}