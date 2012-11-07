<?php
/**
 * Zikula Application Framework
 *
 * Weblinks
 *
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */
class Weblinks_Controller_User extends Zikula_AbstractController {
    /**
    * function main
    */
    public function main()
    {
        $this->redirect(ModUtil::url('Weblinks', 'user', 'view'));
    }

    /**
    * function view
    */
    public function view()
    {


        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // get all categories
        $categories = ModUtil::apiFunc('Weblinks', 'user', 'categories');

        // value of the function is checked
        if (!$categories) {
            return DataUtil::formatForDisplayHTML($this->__('No existing categories'));
        }


        // assign various useful template variables
        $this->view->assign('categories', $categories);
        $this->view->assign('numrows', ModUtil::apiFunc('Weblinks', 'user', 'numrows'));
        $this->view->assign('catnum', ModUtil::apiFunc('Weblinks', 'user', 'catnum'));
        $this->view->assign('helper', array('main' => 1, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        if (ModUtil::getVar('Weblinks', 'featurebox') == 1) {
            $this->view->assign('linkbox', ModUtil::getVar('Weblinks', 'featurebox'));
            $this->view->assign('blocklast', ModUtil::apiFunc('Weblinks', 'user', 'lastweblinks'));
            $this->view->assign('blockmostpop', ModUtil::apiFunc('Weblinks', 'user', 'mostpopularweblinks'));
        } else {
            $this->view->assign('linkbox', ModUtil::getVar('Weblinks', 'featurebox'));
        }

        // return the output that has been generated by this function
        return $this->view->fetch('user/view.tpl');
    }

    /**
    * function category
    */
    public function category()
    {
        // get parameters we need
        $cid = (int)FormUtil::getPassedValue('cid', null, 'GET');
        $orderby = FormUtil::getPassedValue('orderby', 'titleA', 'GET');
        $startnum = (int)FormUtil::getPassedValue('startnum', 1, 'GET');

        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // get category vars
        $category = ModUtil::apiFunc('Weblinks', 'user', 'category', array('cid' => $cid));

        // get subcategories in this category
        $subcategory = ModUtil::apiFunc('Weblinks', 'user', 'subcategory', array('cid' => $cid));

        // get links in this category
        $weblinks = ModUtil::apiFunc('Weblinks', 'user', 'weblinks', array('cid' => $cid,
                                                                    'orderbysql' => ModUtil::apiFunc('Weblinks', 'user', 'orderby', array('orderby' => $orderby)),
                                                                    'startnum' => $startnum,
                                                                    'numlinks' => ModUtil::getVar('Weblinks', 'perpage')));


        // assign various useful template variables
        $this->view->assign('orderby', $orderby);
        $this->view->assign('category', $category);
        $this->view->assign('subcategory', $subcategory);
        $this->view->assign('weblinks', $weblinks);
        $this->view->assign('helper', array('main' => 0, 'showcat' => 0, 'details' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        if (ModUtil::getVar('Weblinks', 'thumber') == 1) {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
            $this->view->assign('thumbersize', ModUtil::getVar('Weblinks', 'thumbersize'));
        } else {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
        }
        $this->view->assign('wlpager', array('numitems' => ModUtil::apiFunc('Weblinks', 'user', 'countcatlinks', array('cid' => $cid)),
                                        'itemsperpage' => ModUtil::getVar('Weblinks', 'perpage')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/category.tpl');
    }

    /**
    * function visit
    */
    public function visit()
    {


        // get parameters we need
        $lid = (int)FormUtil::getPassedValue('lid', null, 'GET');

        // get link
        $link = ModUtil::apiFunc('Weblinks', 'user', 'link', array('lid' => $lid));

        // the return value of the function is checked here
        if ($link == false) {
            return LogUtil::registerError($this->__('Link don\'t exist!'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Weblinks::Category', "::$link[cat_id]", ACCESS_READ)) {
            return LogUtil::registerPermissionError();
            System::redirect(ModUtil::url('Weblinks', 'user', 'view'));
        } else {
            // set the counter for the link +1
            ModUtil::apiFunc('Weblinks', 'user', 'hitcountinc', array('lid' => $lid, 'hits' => $link['hits']));

            // is the URL local?
            if (eregi('^http:|^ftp:|^https:', $link['url'])) {
                System::redirect($link['url']);
            } else {
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $link['url']);
            }
        }

        // return
        return true;
    }

    /**
    * function search
    */
    public function search()
    {
        // get parameters we need
        $query = FormUtil::getPassedValue('query', null, 'GETPOST');
        $orderby = FormUtil::getPassedValue('orderby', 'titleA', 'GETPOST');
        $startnum = (int)FormUtil::getPassedValue('startnum', 1, 'GETPOST');

        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // get categories with $query inside
        $categories = ModUtil::apiFunc('Weblinks', 'user', 'searchcats', array('query' => $query));

        // get weblinks with $query inside
        $weblinks = ModUtil::apiFunc('Weblinks', 'user', 'search_weblinks', array('query' => $query,
                                                                            'orderbysql' => ModUtil::apiFunc('Weblinks', 'user', 'orderby', array('orderby' =>$orderby)),
                                                                            'startnum' => $startnum,
                                                                            'numlinks' => ModUtil::getVar('Weblinks', 'linksresults')));


        // assign various useful template variables
        $this->view->assign('query', $query);
        $this->view->assign('categories', $categories);
        $this->view->assign('orderby', $orderby);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('weblinks', $weblinks);
        $this->view->assign('helper', array('main' => 0, 'showcat' => 1, 'details' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        $this->view->assign('wlpager', array('numlinks' => ModUtil::apiFunc('Weblinks', 'user', 'countsearchlinks', array('query' => $query)),
                                        'itemsperpage' => ModUtil::getVar('Weblinks', 'linksresults')));
        if (ModUtil::getVar('Weblinks', 'thumber') == 1) {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
            $this->view->assign('thumbersize', ModUtil::getVar('Weblinks', 'thumbersize'));
        } else {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('user/searchresults.tpl');
    }

    /**
    * function randomlink
    */
    public function randomlink()
    {
        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // get random link id an redirect to the visit function
        System::redirect(ModUtil::url('Weblinks', 'user', 'visit', array('lid' => ModUtil::apiFunc('Weblinks', 'user', 'random'))));

        return true;
    }

    /**
    * function viewlinkdetails
    */
    public function viewlinkdetails()
    {
        // get parameters we need
        $lid = (int)FormUtil::getPassedValue('lid', null, 'GET');

        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // get link details
        $weblink = ModUtil::apiFunc('Weblinks', 'user', 'link', array('lid' => $lid));


        // assign various useful template variables
        $this->view->assign('weblinks', $weblink);
        $this->view->assign('helper', array('main' => 0, 'showcat' => 1, 'details' => 1, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        if (ModUtil::getVar('Weblinks', 'thumber') == 1) {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
            $this->view->assign('thumbersize', ModUtil::getVar('Weblinks', 'thumbersize'));
        } else {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('user/details.tpl');
    }

    /**
    * function newlinks
    */
    public function newlinks()
    {
        // get parameters we need
        $newlinkshowdays = (int)FormUtil::getPassedValue('newlinkshowdays', '7', 'GET');

        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());



        // assign various useful template variables
        $this->view->assign('newlinkshowdays', $newlinkshowdays);
        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/newlinks.tpl');
    }

    /**
    * function newlinksdate
    */
    public function newlinksdate()
    {


        // get parameters we need
        $selectdate = (int)FormUtil::getPassedValue('selectdate', null, 'GET');

        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // count weblinks from the selected day
        $totallinks = ModUtil::apiFunc('Weblinks', 'user', 'totallinks', array('selectdate' => $selectdate));

        // get weblinks from the selected day
        $weblinks = ModUtil::apiFunc('Weblinks', 'user', 'weblinksbydate', array('selectdate' => $selectdate));


        // assign various useful template variables
        $this->view->assign('dateview', DateUtil::getDatetime($selectdate, 'datebrief'));
        $this->view->assign('totallinks', $totallinks);
        $this->view->assign('weblinks', $weblinks);
        $this->view->assign('helper', array('main' => 0, 'showcat' => 1, 'details' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        if (ModUtil::getVar('Weblinks', 'thumber') == 1) {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
            $this->view->assign('thumbersize', ModUtil::getVar('Weblinks', 'thumbersize'));
        } else {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('user/newlinksdate.tpl');
    }

    /**
    * function mostpopular
    */
    public function mostpopular()
    {
        // get parameters we need
        $ratenum = (int)FormUtil::getPassedValue('ratenum', null, 'GET');
        $ratetype = FormUtil::getPassedValue('ratetype', null, 'GET');

        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        $mostpoplinkspercentrigger = ModUtil::getVar('Weblinks', 'mostpoplinkspercentrigger');
        $mostpoplinks = ModUtil::getVar('Weblinks', 'mostpoplinks');
        $toplinkspercent = 0;
        $totalmostpoplinks = ModUtil::apiFunc('Weblinks', 'user', 'numrows');

        if ($ratenum != "" && $ratetype != "") {
            if (!is_numeric($ratenum)) {
                $ratenum = 5;
            }
            if ($ratetype != "percent") {
                $ratetype = "num";
            }
            $mostpoplinks = $ratenum;
            if ($ratetype == "percent") {
                $mostpoplinkspercentrigger = 1;
            }
        }

        if ($mostpoplinkspercentrigger == 1) {
            $toplinkspercent = $mostpoplinks;
            $mostpoplinks = $mostpoplinks / 100;
            $mostpoplinks = $totalmostpoplinks * $mostpoplinks;
            $mostpoplinks = round($mostpoplinks);
            $mostpoplinks = max(1, $mostpoplinks);
        }

        // get most popular weblinks
        $weblinks = ModUtil::apiFunc('Weblinks', 'user', 'mostpopularweblinks', array('mostpoplinks' => $mostpoplinks));


        // assign various useful template variables
        $this->view->assign('mostpoplinkspercentrigger', $mostpoplinkspercentrigger);
        $this->view->assign('toplinkspercent', $toplinkspercent);
        $this->view->assign('totalmostpoplinks', $totalmostpoplinks);
        $this->view->assign('mostpoplinks', $mostpoplinks);
        $this->view->assign('weblinks', $weblinks);
        $this->view->assign('helper', array('main' => 0, 'showcat' => 1, 'details' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        if (ModUtil::getVar('Weblinks', 'thumber') == 1) {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
            $this->view->assign('thumbersize', ModUtil::getVar('Weblinks', 'thumbersize'));
        } else {
            $this->view->assign('thumber', ModUtil::getVar('Weblinks', 'thumber'));
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('user/mostpopular.tpl');
    }
    /**
    * function brockenlink
    */
    public function brokenlink()
    {
        // get parameters we need
        $lid = (int)FormUtil::getPassedValue('lid', null, 'GET');

        // Security check
        if (!ModUtil::getVar('Weblinks', 'unregbroken') == 1 &&
            !SecurityUtil::checkPermission('Weblinks::', "::", ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }

        if (UserUtil::isLoggedIn()) {
            $submitter = UserUtil::getVar('uname');
        } else {
            $submitter = System::getVar("anonymous");
        }


        // assign various useful template variables
        
        $this->view->assign('lid', $lid);
        $this->view->assign('submitter', $submitter);
        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/brokenlink.tpl');
    }

    /**
    * function brockenlinks
    */
    public function brokenlinks()
    {
        // get parameters we need
        $lid = (int)FormUtil::getPassedValue('lid', null, 'POST');
        $submitter = FormUtil::getPassedValue('submitter', null, 'POST');

        // Security check
        if (!ModUtil::getVar('Weblinks', 'unregbroken') == 1 &&
            !SecurityUtil::checkPermission('Weblinks::', "::", ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }

        $this->checkCsrfToken();


        // add broken link
        ModUtil::apiFunc('Weblinks', 'user', 'addbrockenlink', array('lid' => $lid, 'submitter' => $submitter));

        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/brokenlinks.tpl');
    }

    /**
    * function modifylinkrequest
    */
    public function modifylinkrequest()
    {
        // get parameters we need
        $lid = (int)FormUtil::getPassedValue('lid', null, 'GET');

        // Security check
        if (!ModUtil::getVar('Weblinks', 'blockunregmodify') == 1 &&
            !SecurityUtil::checkPermission('Weblinks::', "::", ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }

        // get link vars
        $link = ModUtil::apiFunc('Weblinks', 'user', 'link', array('lid' => $lid));

        if (UserUtil::isLoggedIn()) {
            $submitter = UserUtil::getVar('uname');
        } else {
            $submitter = System::getVar("anonymous");
        }


        // assign various useful template variables
        $this->view->assign('blockunregmodify', ModUtil::getVar('Weblinks', 'blockunregmodify'));
        $this->view->assign('link', $link);
        $this->view->assign('submitter', $submitter);
        $this->view->assign('anonymous', System::getVar("anonymous"));
        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/modifylinkrequest.tpl');
    }

    /**
    * function modifylinkrequests
    */
    public function modifylinkrequests()
    {
        // get parameters we need
        $modlink = FormUtil::getPassedValue('modlink', array(), 'POST');

        // Security check
        if (!ModUtil::getVar('Weblinks', 'blockunregmodify') == 1 &&
            !SecurityUtil::checkPermission('Weblinks::', "::", ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }

        $this->checkCsrfToken();


        // add link request
        ModUtil::apiFunc('Weblinks', 'user', 'modifylinkrequest', array('lid' => $modlink['lid'],
                                                                    'cid' => $modlink['cid'],
                                                                    'title' => $modlink['title'],
                                                                    'url' => $modlink['url'],
                                                                    'description' => $modlink['description'],
                                                                    'submitter' => $modlink['submitter']));

        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/modifylinkrequests.tpl');
    }

    /**
    * function addlink
    */
    public function addlink()
    {
        // Security check
        if (!ModUtil::getVar('Weblinks', 'links_anonaddlinklock') == 1 &&
            !SecurityUtil::checkPermission('Weblinks::', "::", ACCESS_COMMENT)) {
                $addlink = false;
        } else {
            $addlink = true;
        }


        // assign various useful template variables
        
        $this->view->assign('addlink', $addlink);
        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));
        if (UserUtil::isLoggedIn()) {
            $this->view->assign('submitter', UserUtil::getVar('uname'));
            $this->view->assign('submitteremail', UserUtil::getVar('email'));
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('user/addlink.tpl');
    }

    /**
    * function add
    */
    public function add()
    {
        // get parameters we need
        $newlink = FormUtil::getPassedValue('newlink', array(), 'POST');

        // Security check
        if (!ModUtil::getVar('Weblinks', 'links_anonaddlinklock') == 1 &&
            !SecurityUtil::checkPermission('Weblinks::', "::", ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }

        $this->checkCsrfToken();


        // write the link to db and get a status message back
        $link = ModUtil::apiFunc('Weblinks', 'user', 'add', array('title' => $newlink['title'],
                                                            'url' => $newlink['url'],
                                                            'cat' => $newlink['cat'],
                                                            'description' => $newlink['description'],
                                                            'submitter' => $newlink['submitter'],
                                                            'submitteremail' => $newlink['submitteremail']));


        // assign various useful template variables
        $this->view->assign('submit', $link['submit']);
        $this->view->assign('text', $link['text']);
        $this->view->assign('helper', array('main' => 0, 'tb' => ModUtil::getVar('Weblinks', 'targetblank')));

        // Return the output that has been generated by this function
        return $this->view->fetch('user/add.tpl');
    }
}