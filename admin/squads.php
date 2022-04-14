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

$_language->readModule('squads', false, true);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='squads'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($_language->module[ 'access_denied' ]);
}
}

if (isset($_GET['delete'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
        $squadID = $_GET['squadID'];
        $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "squads_members WHERE squadID='$squadID'");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $squads = mysqli_num_rows(
                safe_query(
                    "SELECT userID FROM " . PREFIX . "squads_members WHERE userID='$ds[userID]'"
                )
            );
            if ($squads < 2 && !issuperadmin($ds['userID'])) {
                safe_query("DELETE FROM " . PREFIX . "user_groups WHERE userID='$ds[userID]'");
            }
        }
        safe_query("DELETE FROM " . PREFIX . "squads_members WHERE squadID='$squadID' ");
        safe_query("DELETE FROM " . PREFIX . "squads WHERE squadID='$squadID' ");

        
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

if (isset($_POST['sortieren'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        $sort = $_POST['sort'];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "squads SET sort='$sorter[1]' WHERE squadID='$sorter[0]' ");
            }
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

if (isset($_POST['save'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(array('name'))) {
            $games = implode(";", $_POST['games']);
            safe_query(
                "INSERT INTO " . PREFIX . "squads ( gamesquad, games, name, info, sort ) VALUES ( '" .
                $_POST['gamesquad'] . "', '" . $games . "', '" . $_POST['name'] . "', '" . $_POST['message'] .
                "', '1' )"
            );

            $id = mysqli_insert_id($_database);
            $filepath = "../images/squadicons/";

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('icon');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $id . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "squads SET icon='" . $file . "' WHERE squadID='" . $id . "'"
                                );
                            }
                        } else {
                            $errors[] = $_language->module['broken_image'];
                        }
                    } else {
                        $errors[] = $_language->module['unsupported_image_type'];
                    }
                } else {
                    $errors[] = $upload->translateError();
                }
            }

            $upload = new \webspell\HttpUpload('icon_small');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $id . '_small' . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "squads SET icon_small='" . $file .
                                    "' WHERE squadID='" . $id . "'"
                                );
                            }
                        } else {
                            $errors[] = $_language->module['broken_image'];
                        }
                    } else {
                        $errors[] = $_language->module['unsupported_image_type'];
                    }
                } else {
                    $errors[] = $upload->translateError();
                }
            }

            if (count($errors)) {
                $errors = array_unique($errors);
                echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
            }
        } else {
            echo $_language->module['information_incomplete'];
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

if (isset($_POST['saveedit'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(array('name'))) {
            $games = implode(";", $_POST['games']);
            safe_query(
                "UPDATE " . PREFIX . "squads SET gamesquad='" . $_POST['gamesquad'] . "', games='" . $games .
                "', name='" . $_POST['name'] . "', info='" . $_POST['message'] . "' WHERE squadID='" .
                $_POST['squadID'] . "' "
            );
            $filepath = "../images/squadicons/";
            $id = $_POST['squadID'];

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('icon');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $id . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "squads SET icon='" . $file . "' WHERE squadID='" . $id . "'"
                                );
                            }
                        } else {
                            $errors[] = $_language->module['broken_image'];
                        }
                    } else {
                        $errors[] = $_language->module['unsupported_image_type'];
                    }
                } else {
                    $errors[] = $upload->translateError();
                }
            }

            $upload = new \webspell\HttpUpload('icon_small');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $id . '_small' . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "squads SET icon_small='" . $file .
                                    "' WHERE squadID='" . $id . "'"
                                );
                            }
                        } else {
                            $errors[] = $_language->module['broken_image'];
                        }
                    } else {
                        $errors[] = $_language->module['unsupported_image_type'];
                    }
                } else {
                    $errors[] = $upload->translateError();
                }
            }

            if (count($errors)) {
                $errors = array_unique($errors);
                echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
            }
        } else {
            echo $_language->module['information_incomplete'];
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

if ($action == "add") {

  echo'<div class="card">
        <div class="card-header">
            <i class="fas fa-user-friends"></i> '.$_language->module['squads'].'
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=squads">' . $_language->module['squads'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add_squad'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

	$filepath = "../images/squadicons/";
    $sql = safe_query("SELECT * FROM " . PREFIX . "settings_games ORDER BY name");
    $games = '<select class="form-control" name="games[]">';
    while ($db = mysqli_fetch_array($sql)) {
        $games .= '<option value="' . htmlspecialchars($db['name']) . '">' . htmlspecialchars($db['name']) .
        '</option>';
    }
    $games .= '</select>';
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<script>
		<!--
			function chkFormular() {
				if(!validbbcode(document.getElementById(\'message\').value, \'admin\')) {
					return false;
				}
			}
		-->
	</script>';
  
	echo '<form method="post" id="post" name="post" action="admincenter.php?site=squads" enctype="multipart/form-data"
onsubmit="return chkFormular();">
    <div class="row">
    <div class="col-md-6">


    <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['icon_upload'].':</label>
    <div class="col-md-8">
      <p class="form-control-static"><input class="btn btn-info" name="icon" type="file" size="40" /></p>
    </div>
    </div>

    <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['icon_upload_small'].':</label>
    <div class="col-md-8">
      <p class="form-control-static"><input class="btn btn-info" name="icon_small" type="file" size="40" /><br><small>('.$_language->module['icon_upload_info'].')</small></p>
    </div>
    </div>


  </div>
  <div class="col-md-6">

  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['squad_name'].':</label>
    <div class="col-md-8">
      <p class="form-control-static"><input class="form-control" type="text" name="name" /></p>
    </div>
  </div>

  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['squad_type'].':</label>
    <div class="col-md-8">
      <p class="form-control-static">
        <input onclick="document.getElementById(\'games\').style.display = \'block\'" type="radio" name="gamesquad" value="1" checked="checked" /> '.$_language->module['gaming_squad'].' &nbsp; 
        <input onclick="document.getElementById(\'games\').style.display = \'none\'" type="radio" name="gamesquad" value="0" /> '.$_language->module['non_gaming_squad'].'</p>
    </div>
  </div>

<div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['game'].':</label>
    <div class="col-md-8">
      <p class="form-control-static">'.$games.'</p>
    </div>
  </div>

</div>
 
<div class="col-sm-12">
        '.$_language->module['info'].'<br>
        '.$_language->module['squad_info'].':
        <textarea class="ckeditor" id="ckeditor" rows="5" cols="" name="message" style="width: 100%;"></textarea>
    </div>

<div class="col-sm-12"><br>
<input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="save" />'.$_language->module['add_squad'].'</button>
    </div>
  </div>
  
  </form></div>
  </div>';
} elseif ($action == "edit") {
    echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-user-friends"></i> '.$_language->module['squads'].'
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=squads">' . $_language->module['squads'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit_squad'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    $squadID = (int) $_GET['squadID'];
    $filepath = "../images/squadicons/";

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "squads WHERE squadID='$squadID'");
    $ds = mysqli_fetch_array($ergebnis);

    $games_array = explode(";", $ds['games']);
    $sql = safe_query("SELECT * FROM " . PREFIX . "settings_games ORDER BY name");
    $games = '<select class="form-control" name="games[]">';
    while ($db = mysqli_fetch_array($sql)) {
        $selected = '';
        if ($db['name'] == $ds['games']) {
            $selected = ' selected="selected"';
        }
        $games .= '<option value="' . htmlspecialchars($db['name']) . '"' . $selected . '>' .
        htmlspecialchars($db['name']) . '</option>';
    }
    $games .= '</select>';

    if ($ds['gamesquad']) {
        $type = '<input onclick="document.getElementById(\'games\').style.display = \'block\'"
type="radio" name="gamesquad" value="1" checked="checked" /> ' . $_language->module['gaming_squad'] . ' &nbsp;
<input onclick="document.getElementById(\'games\').style.display = \'none\'" type="radio"
            name="gamesquad" value="0" /> ' . $_language->module['non_gaming_squad'];
        $display = 'block';
    } else {
        $type = '<input onclick="document.getElementById(\'games\').style.display = \'block\'" type="radio"
            name="gamesquad" value="1" /> ' . $_language->module['gaming_squad'] . ' &nbsp;
            <input onclick="document.getElementById(\'games\').style.display = \'none\'" type="radio"
            name="gamesquad" value="0" checked="checked" /> ' . $_language->module['non_gaming_squad'];
        $display = 'none';
    }

    if (!empty($ds['icon'])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="' . $filepath . $ds['icon'] . '" alt="">';
    } else {
        $pic = $_language->module['no_icon'];
    }
    if (!empty($ds['icon_small'])) {
        $pic_small = '<img class="img-thumbnail" style="width: 100%; max-width: 200px" src="' . $filepath . $ds['icon_small'] . '" alt="">';
    } else {
        $pic_small = $_language->module['no_icon'];
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<script>
		<!--
			function chkFormular() {
				if(!validbbcode(document.getElementById(\'message\').value, \'admin\')) {
					return false;
				}
			}
		-->
	</script>';
  
	echo '<form method="post" id="post" name="post" action="admincenter.php?site=squads" enctype="multipart/form-data"
    onsubmit="return chkFormular();">
    
	<div class="row">
<div class="col-md-6">
    <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['current_icon'].':</label>
    <div class="col-md-8">
      <p class="form-control-static">'.$pic.'</p>
    </div>
  </div>
  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['current_icon'].':</label>
    <div class="col-md-8">
      <p class="form-control-static">'.$pic_small.'</p>
    </div>
  </div>
  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['icon_upload'].':</label>
    <div class="col-md-8">
      <p class="form-control-static"><input class="btn btn-info" name="icon" type="file" size="40" /></p>
    </div>
  </div>
  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['icon_upload_small'].':</label>
    <div class="col-md-8">
      <p class="form-control-static"><input class="btn btn-info" name="icon_small" type="file" size="40" /><br><small>('.$_language->module['icon_upload_info'].')</small></p>
    </div>
  </div>


</div>
<div class="col-md-6">


  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['squad_name'].':</label>
    <div class="col-md-8">
      <p class="form-control-static"><input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></p>
    </div>
  </div>

  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['squad_type'].':</label>
    <div class="col-md-8">
      <p class="form-control-static">'.$type.'</p>
    </div>
  </div>

  <div class="form-group row bt">
    <label class="col-md-4 control-label">'.$_language->module['game'].':</label>
    <div class="col-md-8">
      <p class="form-control-static">'.$games.'</p>
    </div>
  </div>


</div>
 
<div class="col-sm-12">
        '.$_language->module['info'].'<br>
        '.$_language->module['squad_info'].':
        <textarea class="ckeditor" id="ckeditor" rows="5" cols="" name="message" style="width: 100%;">'.getinput($ds['info']).'</textarea>
    </div>

<div class="col-sm-12"><br>
      <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="squadID" value="'.getforminput($squadID).'" /><button class="btn btn-warning" type="submit" name="saveedit" />'.$_language->module['edit_squad'].'</button>
    </div>
  </div>
  
  </form></div>
  </div>';
}

else {

  echo'<div class="card">
        <div class="card-header">
            <i class="fas fa-user-friends"></i> '.$_language->module['squads'].'
        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module[ 'squads' ] . '</li>
  </ol>
</nav>

<div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $_language->module['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=squads&amp;action=add" class="btn btn-primary" type="button">' . $_language->module[ 'new_squad' ] . '</a>
    </div>
  </div>';

	echo'<form method="post" action="admincenter.php?site=squads">
  <table class="table table-striped">
<thead>
    <tr>
      <th><b>'.$_language->module['squad_name'].'</b></th>
      <th><b>'.$_language->module['squad_type'].'</b></th>
      <th><b>'.$_language->module['squad_info'].'</b></th>
      <th><b>'.$_language->module['actions'].'</b></th>
      <th><b>'.$_language->module['sort'].'</b></th>
    </tr></thead>';

	$squads = safe_query("SELECT * FROM " . PREFIX . "squads ORDER BY sort");
    $anzsquads = mysqli_num_rows($squads);
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if ($anzsquads) {
        $i = 1;
        while ($db = mysqli_fetch_array($squads)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $games = explode(";", $db['games']);
            $games = implode(", ", $games);
            if ($games) {
                $games = "(" . $games . ")";
            }
            if ($db['gamesquad']) {
                $type = $_language->module['gaming_squad'] . '<br /><small>' . $games . '</small>';
            } else {
                $type = $_language->module['non_gaming_squad'];
            }

            $info = $db[ 'info' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($info);
            $info = $translate->getTextByLanguage($info);
            
        echo '<tr>
        <td><a href="../index.php?site=squads&amp;squadID='.$db['squadID'].'" target="_blank">'.getinput($db['name']).'</a></td>
        <td class="hidden-xs">'.$type.'</td>
        <td class="hidden-xs">'.$info.'</td>
        <td><a href="admincenter.php?site=squads&amp;action=edit&amp;squadID='.$db['squadID'].'" class="hidden-xs hidden-sm btn btn-warning" type="button">' . $_language->module[ 'edit' ] . '</a>

        <input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $_language->module['really_delete'] . '\', \'admincenter.php?site=squads&amp;delete=true&amp;squadID='.$db['squadID'].'&amp;captcha_hash='.$hash.'\')" value="' . $_language->module['delete'] . '" />   


</td>
        <td><select name="sort[]">';

            for ($j = 1; $j <= $anzsquads; $j++) {
                if ($db['sort'] == $j) {
                    echo '<option value="' . $db['squadID'] . '-' . $j . '" selected="selected">' . $j . '</option>';
                } else {
                    echo '<option value="' . $db['squadID'] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
        </td>
      </tr>';
      
      $i++;
		}
	}
	
  echo'<tr>
      <td colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input class="btn btn-primary" type="submit" name="sortieren" value="'.$_language->module['to_sort'].'" /></td>
    </tr>
  </table>
  </form></div></div>';
}
?>
