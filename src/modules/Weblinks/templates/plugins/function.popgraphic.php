<?php
/**
 * Zikula Application Framework
 *
 * Weblinks
 *
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function smarty_function_popgraphic($params, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('Weblinks');

    if ($params['hits'] >= ModUtil::getVar('Weblinks', 'popular')) {
        echo "&nbsp;<img src=\"images/icons/extrasmall/flag.gif\" alt=\"".DataUtil::formatForDisplay(__('Popular', $dom))."\" title=\"".DataUtil::formatForDisplay(__('Popular', $dom))."\" />";
    }

    return;
}