<?php
/**
 * Zikula Application Framework
 *
 * Weblinks
 *
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function smarty_function_countlinks($params, &$smarty)
{
    $totallinks = 0;
    $counter = 0;
    
    while ($counter < $params['days']) {
        $newlinkdayraw = (time()-(86400 * $counter));
        $newlinkdb = DateUtil::transformInternalDate($newlinkdayraw);
        $dbtable = DBUtil::getTables();
        $column = $dbtable['links_links_column'];
        $column2 = $dbtable['links_categories_column'];
        $where = "WHERE $column[date] LIKE '%$newlinkdb%' AND $column[cat_id] = $column2[cat_id]";
        $countlinks = DBUtil::selectObjectCount('links_links', $where);
        if ($countlinks) {
            $totallinks = $totallinks + $countlinks;
        }
        $counter++;
    }

    return $totallinks;
}