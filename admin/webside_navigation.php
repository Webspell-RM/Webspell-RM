<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */

$_language->readModule('webnavi', false, true);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='ac_webnavi'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($_language->module[ 'access_denied' ]);
}
}

if (isset($_GET[ 'delete' ])) {
    $snavID = $_GET[ 'snavID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "navigation_website_sub WHERE snavID='$snavID' ");
    } else {
        echo $_language->module[ 'transaction_invalid' ];
        redirect("admincenter.php?site=webside_navigation",3);
    return false;
    }
} elseif (isset($_GET[ 'delcat' ])) {
    $mnavID = $_GET[ 'mnavID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("UPDATE " . PREFIX . "navigation_website_sub SET mnavID='0' WHERE mnavID='$mnavID' ");
        safe_query("DELETE FROM " . PREFIX . "navigation_website_main WHERE mnavID='$mnavID' ");
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'sortieren' ])) {
    if(isset($_POST[ 'sortcat' ])) { $sortcat = $_POST[ 'sortcat' ]; } else { $sortcat="";}
    $sortlinks = $_POST[ 'sortlinks' ];

    if (is_array($sortcat) AND !empty($sortcat)) {
        foreach ($sortcat as $sortstring) {
            $catsorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "navigation_website_main SET sort='$catsorter[1]' WHERE mnavID='$catsorter[0]' ");
        }
    }
    if (is_array($sortlinks)) {
        foreach ($sortlinks as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "navigation_website_sub SET sort='$sorter[1]' WHERE snavID='$sorter[0]' ");
        }
    }
} elseif (isset($_POST[ 'save' ])) {
    $CAPCLASS = new \webspell\Captcha;

    $url = $_POST[ 'link' ];

    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $anz = mysqli_num_rows(
            safe_query("SELECT snavID FROM " . PREFIX . "navigation_website_sub WHERE mnavID='" . $_POST[ 'mnavID' ] . "'")
        );
        $url = $_POST[ 'link' ];
        safe_query(
            "INSERT INTO " . PREFIX . "navigation_website_sub ( mnavID, name, url, sort )
            values (
            '" . $_POST[ 'mnavID' ] . "',
            '" . $_POST[ 'name' ] . "',
            '" . $url . "',

            '1'
            )"
        );
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }



} elseif (isset($_POST[ 'savecat' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])
    ) {

    $url = $_POST[ 'link' ];
    $windows = $_POST[ "windows" ];

    if (isset($_POST[ 'isdropdown' ])) {
        $isdropdown = 1;
    } else {
        $isdropdown = 0;
    }
    if (!$isdropdown) {
        $isdropdown = 0;
    }
        $anz = mysqli_num_rows(safe_query("SELECT mnavID FROM " . PREFIX . "navigation_website_main"));
        safe_query(
            "INSERT INTO " . PREFIX . "navigation_website_main ( mnavID, name, url, windows, isdropdown, sort )
            values( '', '" . $_POST[ 'name' ] . "', '" . $url . "', '" . $windows . "', '" . $isdropdown . "', '1' )"
        );
        $id = mysqli_insert_id($_database);
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }

} elseif (isset($_POST[ 'saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    $url = $_POST[ 'link' ];

    
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE " . PREFIX . "navigation_website_sub
            SET mnavID='" . $_POST[ 'mnavID' ] . "', name='" . $_POST[ 'name' ] . "', url= '" . $url . "' 
            WHERE snavID='" . $_POST[ 'snavID' ] . "'"
        );
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }

} elseif (isset($_POST[ 'saveeditcat' ])) {
    $CAPCLASS = new \webspell\Captcha;

        $url = $_POST[ "link" ];
        #$windows = $_POST[ "windows" ];
    if (isset($_POST[ "isdropdown" ])) {
        $isdropdown = 1;
    } else {
        $isdropdown = 0;
    }
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

    

        safe_query(
            "UPDATE " . PREFIX . "navigation_website_main SET name='" . $_POST[ 'name' ] . "', url='" . $url . "', windows='" . $_POST[ "windows" ] . "', isdropdown='" . $isdropdown . "' WHERE mnavID='" . $_POST[ 'mnavID' ] . "' "
        );

        $id = $_POST[ 'mnavID' ];
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {
    echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-map-marked"></i> ' . $_language->module[ 'dashnavi' ] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=webside_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add_link'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_website_main ORDER BY sort");
    $cats = '<select class="form-control" name="mnavID">';
    while ($ds = mysqli_fetch_array($ergebnis)) {
        if ($ds[ 'default' ] == 0) {
            $name = $_language->module[ 'cat_' . getinput($ds[ 'name' ]) ];
        } else {
            $name = getinput($ds[ 'name' ]);
            
        }
        $cats .= '<option value="' . $ds[ 'mnavID' ] . '">' . $name . '</option>';
    }
    $cats .= '</select>';

    
    
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=webside_navigation">
    <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      ' . $cats . '</em></span>
    </div>
    </div>
    <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="60"></em></span>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="link" size="60"/></td></em></span>
    </div>
  </div>

    
  <div class="form-group row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="' . $hash . '">
      <input class="btn btn-success" type="submit" name="save" value="' . $_language->module[ 'add_link' ] . '">
    </div>
  </div>
   
          </form></div></div>';
} elseif ($action == "edit") {
    echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-map-marked"></i> ' . $_language->module[ 'dashnavi' ] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=webside_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit_link'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    $snavID = $_GET[ 'snavID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_website_sub WHERE snavID='$snavID'");
    $ds = mysqli_fetch_array($ergebnis);

    $category = safe_query("SELECT * FROM " . PREFIX . "navigation_website_main ORDER BY sort");
    $cats = '<select class="form-control" name="mnavID">';
    while ($dc = mysqli_fetch_array($category)) {
        if ($dc[ 'default' ] == 1) {
            $name = getinput($dc[ 'name' ]);
        }
        if ($ds[ 'mnavID' ] == $dc[ 'mnavID' ]) {
            $selected = " selected=\"selected\"";
        } else {
            $selected = "";
        }
        $cats .= '<option value="' . $dc[ 'mnavID' ] . '"' . $selected . '>' . $name . '</option>';
    }
    $cats .= '</select>';

    
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=webside_navigation">

    <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      ' . $cats . '</em></span>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="' . getinput($ds[ 'name' ]) . '" size="60"></em></span>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" value="' . getinput($ds[ 'url' ]) . '" size="60"></em></span>
    </div>
  </div>

  
<div class="form-group row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="snavID" value="' . $snavID . '">
      <input class="btn btn-warning" type="submit" name="saveedit" value="' . $_language->module[ 'edit_link' ] . '">
    </div>
  </div>

    </form>
    </div></div>';

# new main navi
} elseif ($action == "addcat") {
    echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-map-marked"></i> ' . $_language->module[ 'dashnavi' ] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=webside_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add_category'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    
    
   echo '<form class="form-horizontal" method="post" action="admincenter.php?site=webside_navigation">

    <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" size="60"></em></span>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60"></em></span><br>
      <select id="windows" name="windows" class="form-control">
  <option value="0">' . $_language->module['_blank'] . '</option>
  <option value="1">' . $_language->module['_self'] . '</option>
</select>
    </div>
  </div>
  
  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['dropdown'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="checkbox" name="isdropdown" id="isdropdown" checked="checked" /></em></span>
    </div>
  </div>
  

<div class="form-group row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input class="btn btn-success" type="submit" name="savecat" value="' . $_language->module[ 'add_category' ] . '">
    </div>
  </div>

    </form>
    </div></div>';
} elseif ($action == "editcat") {
    echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-map-marked"></i> ' . $_language->module[ 'dashnavi' ] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=webside_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit_category'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    $mnavID = $_GET[ 'mnavID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_website_main WHERE mnavID='$mnavID'");
    $ds = mysqli_fetch_array($ergebnis);

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if ($ds[ 'isdropdown' ] == 1) {
        $isdropdown = '<input type="checkbox" name="isdropdown" value="1" checked="checked" />';
    } else {
        $isdropdown = '<input type="checkbox" name="isdropdown" value="1" />';
    }

    if ($ds['windows'] == "1") {
                $windows_1 = '<option value="1" selected="selected">' . $_language->module['_self'] .
                    '</option><option value="0">' . $_language->module['_blank'] . '</option>';
            } else {
                $windows_1 = '<option value="1">' . $_language->module['_self'] .
                    '</option><option value="0" selected="selected">' . $_language->module['_blank'] . '</option>';
            }

    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=webside_navigation">
<input type="hidden" name="mnavID" value="' . $ds[ 'mnavID' ] . '" />
        <div class="form-group row">
    <label class="col-sm-2 control-label">' . $_language->module[ 'name' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="' . getinput($ds[ 'name' ]) . '" size="60"></em></span>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        
        <input class="form-control" id="link" rows="10" cols="" name="link" value="' . getinput($ds[ 'url' ]) .
        '" size="60"></em></span><br>
        <select id="windows" name="windows" class="form-control">'.$windows_1.'</select>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 control-label">'.$_language->module['dropdown'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <p class="form-control-static">'.$isdropdown.'</p></em></span>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" /><br>
      <input class="btn btn-warning" type="submit" name="saveeditcat" value="' . $_language->module[ 'edit_category' ] . '">
    </div>
  </div>
    </form></div></div>';
} else {
    echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-map-marked"></i> ' . $_language->module[ 'dashnavi' ] . '
        </div>
           <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module[ 'dashnavi' ] . '</li>
  </ol>
</nav>

<div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $_language->module['options'] . ':</label>
    <div class="col-md-8">
      <a class="btn btn-primary" href="admincenter.php?site=webside_navigation&amp;action=addcat" class="input">' .
        $_language->module[ 'new_category' ] . '</a>
        <a class="btn btn-primary" href="admincenter.php?site=webside_navigation&amp;action=add" class="input">' .
        $_language->module[ 'new_link' ] . '</a>
    </div>
  </div>';

    echo '<form method="post" action="admincenter.php?site=webside_navigation">
    <table class="table">
<thead>
    <tr>
      <th width="25%" ><b>' . $_language->module[ 'name' ] . '</b></th>
      <th width="25%" ><b>Link</b></th>
            <th width="20%" ><b>' . $_language->module[ 'actions' ] . '</b></th>
            <th width="8%" ><b>' . $_language->module[ 'sort' ] . '</b></th>
    </tr></thead>';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_website_main ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(mnavID) as cnt FROM " . PREFIX . "navigation_website_main"));
    $anz = $tmp[ 'cnt' ];
$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {

        $list = '<select name="sortcat[]">';
                for ($n = 1; $n <= $anz; $n++) {
                    $list .= '<option value="' . $ds[ 'mnavID' ] . '-' . $n . '">' . $n . '</option>';
                }
                $list .= '</select>';
                $list = str_replace(
                    'value="' . $ds[ 'mnavID' ] . '-' . $ds[ 'sort' ] . '"',
                    'value="' . $ds[ 'mnavID' ] . '-' . $ds[ 'sort' ] . '" selected="selected"',
                    $list
                );

        if ($ds[ 'default' ] == 0) {
            $list = '<b>' . $ds[ 'list' ] . '</b>';
            $catactions = '';
            $name = $_language->module[ 'cat_' . getinput($ds[ 'name' ]) ];
        } else {
            $sort = $list;
            $catactions =
                '<a class="btn btn-warning" href="admincenter.php?site=webside_navigation&amp;action=editcat&amp;mnavID=' . $ds[ 'mnavID' ] .
                '" class="input">' . $_language->module[ 'edit' ] . '</a>
                <input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $_language->module['really_delete_category'] . '\', \'admincenter.php?site=webside_navigation&amp;delcat=true&amp;mnavID=' . $ds[ 'mnavID' ] .
                '&amp;captcha_hash=' . $hash . '\')" value="' . $_language->module['delete'] . '" />



                ';

            $name = $ds['name'];
                $translate = new multiLanguage(detectCurrentLanguage());
                $translate->detectLanguages($name);
                $name = $translate->getTextByLanguage($name);
                
                $data_array = array();
                $data_array['$name'] = $ds['name'];
        }

        echo '<tr class="table-secondary">
            <td width="25%" class="td_head admin-nav-modal"><b>' . $name . '</b></td>
            <td width="25%" class="td_head admin-nav-modal"><small>' . $ds[ 'url' ] . '</small></td>
            <td width="25%" td_head">' . $catactions . '</td>
            <td width="15%" td_head">' . $sort . '</td>
        </tr>';
        
       
        
        $links = safe_query("SELECT * FROM " . PREFIX . "navigation_website_sub WHERE mnavID='" . $ds[ 'mnavID' ] . "' ORDER BY sort");
        $tmp = mysqli_fetch_assoc(safe_query("SELECT count(snavID) as cnt FROM " . PREFIX . "navigation_website_sub WHERE mnavID='" . $ds[ 'mnavID' ] . "'"));
        $anzlinks = $tmp[ 'cnt' ];

        $i = 1;
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        if (mysqli_num_rows($links)) {
            while ($db = mysqli_fetch_array($links)) {
                if ($i % 2) {
                    $td = 'td1';
                } else {
                    $td = 'td2';
                }

                $name = $db['name'];
                $translate = new multiLanguage(detectCurrentLanguage());
                $translate->detectLanguages($name);
                $name = $translate->getTextByLanguage($name);
                
                $data_array = array();
                $data_array['$name'] = $db['name'];

                $linklist = '<select name="sortlinks[]">';
                for ($n = 1; $n <= $anzlinks; $n++) {
                    $linklist .= '<option value="' . $db[ 'snavID' ] . '-' . $n . '">' . $n . '</option>';
                }
                $linklist .= '</select>';
                $linklist = str_replace(
                    'value="' . $db[ 'snavID' ] . '-' . $db[ 'sort' ] . '"',
                    'value="' . $db[ 'snavID' ] . '-' . $db[ 'sort' ] . '" selected="selected"',
                    $linklist
                );

                echo '<tr>
                     <td class="' . $td . '">&nbsp;-&nbsp;<b>' . $name . '</b></td>
                    <td class="' . $td . '"><small>' . $db[ 'url' ] . '</small></td>
                   
                   <td class="' . $td . '">
<a href="admincenter.php?site=webside_navigation&amp;action=edit&amp;snavID=' . $db[ 'snavID' ] .'" class="btn btn-warning">' . $_language->module[ 'edit' ] . '</a>
<input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $_language->module['really_delete_link'] . '\', \'admincenter.php?site=webside_navigation&amp;delete=true&amp;snavID=' . $db[ 'snavID' ] . '&amp;captcha_hash=' . $hash . '\')" value="' . $_language->module['delete'] . '" />


                    </td>
                    <td class="' . $td . '">' . $linklist . '</td>
                </tr>';
                $i++;
            }
        } else {
            echo '<tr>'.
                    '<td class="td1" colspan="4">' . $_language->module[ 'no_additional_links_available' ] . '</td>'.
                 '</tr>';
        }
    }
    
    echo '	<tr>
                <td class="td_head" colspan="4" align="right"><input class="btn btn-primary" type="submit" name="sortieren" value="' .
        $_language->module[ 'to_sort' ] . '"></td>
            </tr>
        </table>
    </form></div></div>';
}
