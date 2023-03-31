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

/** ZENITH.Developments | GETSCHONNIK **/

$_language->readModule('template_installer', false, true);
include('../system/func/installer.php');
include('../system/func/update_base.php');

function curl_json2array($url){
$ssl = 0;
if (substr($url, 0, 7) == "http://") { $ssl=0; } else { $ssl=1;}  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$output = curl_exec($ch);
curl_close($ch);
return json_decode($output, true);
}
$getversion = $version;

if (!$getnew = @file_get_contents($updateserverurl.'/theme/style-base_v.'.$getversion.'/list.json')) {
  echo '<div class="card">
        <div class="card-header">
            <i class="fas fa-puzzle-piece"></i> Themes Installer
        </div>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Themes Installer</li>
        </ol>
      </nav>

    <div class="card-body">

      <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Template Installer</h4>
          '.$_language->module['info_error'].'
          <hr>
          <i><b>' . $_language->module[ 'error' ] . '</b></i>
        <a href="admincenter.php?site=update">
              <button class="btn btn-success" type="submit" name="submit">Webspell-RM Update</button>
          </a>
      </div></div></div>';
} else {

if(isset($_GET['deinstall'] )== 'plugin') {
  $dir = $_GET['dir'];
  $name = str_replace("/", "", $dir);
  require_once('../includes/themes'. $dir.'uninstall.php');
  recursiveRemoveDirectory('../includes/themes'. $dir); 
  header('Location: ?site=template_installer');
  exit;
} elseif(!empty($_GET['do'])) {
  $dir = $_GET['dir'];
  $dir = str_replace('/','',$dir);
  $id = $_GET['id'];
  echo rmmodinstall('temp','install',$dir,$id,$getversion);

} elseif(!empty($_GET['up'])) {
  $dir = $_GET['dir'];
  $dir = str_replace('/','',$dir);
  $id = $_GET['id'];
  echo rmmodinstall('temp','update',$dir,$id,$getversion);
 
} else {
try {
  $url = $updateserverurl.'/theme/style-base_v.'.$getversion.'/list.json';
  $imgurl = $updateserverurl.'/theme/style-base_v.'.$getversion.'';
  $result = curl_json2array($url);
  $anz = (count($result)-1);
  $output = "";
  $input = "";
  $install_datei ="";
  
   for($plug=1; $plug<=$anz; $plug++) {
      $installedversion = '';
      $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($result['item'.$plug]['description_de']);
            $result['item'.$plug]['description_de'] = $translate->getTextByLanguage($result['item'.$plug]['description_de']);
            $ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_themes` WHERE `modulname`='".$result['item'.$plug]['modulname']."'");
            if(mysqli_num_rows($ergebnis) == '1') {
                $row = mysqli_fetch_assoc($ergebnis);
                if($row['version'] !== ''){
                    $installedversion = $row['version'];
                }
            }
            $output .= '  <tr>';
      $output .= '<th>
      <div class="imageHold">
    <div><img class="featured-image img-thumbnail" src="'.$imgurl.''.$result['item'.$plug]['path'].$result['item'.$plug]['preview'].'" alt="{img}" /></div>
</div>';
      $output .= '<th><strong>'.$result['item'.$plug]['name'].'</strong><br /><small class="fontLight">'.$result['item'.$plug]['description_de'].'</small>
</th>';
	  $output .= '<th><small class="fontLight">Plugin Ver.: <span class="label label-success">'.$result['item'.$plug]['version_final'].'</span><br /><small class="fontLight">Inst. Plugin Ver.: '.$installedversion.'<span class="label label-warning">'.$result['item'.$plug]['version_beta'].'</span><span class="label label-danger">'.$result['item'.$plug]['version_test'].'</span><br />Req: webSpell | RM: <b>'.$result['item'.$plug]['req'].'</b><br />Language: '.$result['item'.$plug]['languages'].'<br />Update: <b>'.$result['item'.$plug]['update'].'</b></small><br>
    <small class="fontLight">Modified and Coding by: '.$result['item'.$plug]['author'].'</small></th>';
    
      include("../system/version.php");
      if(is_dir("../includes/themes/".$result['item'.$plug]['path'])) {
        $output .= '<th>';
          if($result['item'.$plug.'']['version_final'] === $installedversion) { 
              $output .='<a class="btn btn-success" style="width: 160px" href="?site=template_installer&id='.$plug.'&up=install&dir='.$result['item'.$plug]['path'].'">' . $_language->module['reinstall'] . '</a>';
          } else { 
              $output .='<a class="btn btn-warning" style="width: 160px" href="?site=template_installer&id='.$plug.'&up=install&dir='.$result['item'.$plug]['path'].'">' . $_language->module['update'] . ' to Ver. '.$result['item'.$plug]['version_final'].'</a>';  
          }


$output .='<button class="btn btn-danger" style="width: 160px" data-href="?site=template_installer&deinstall=plugin&dir='.$result['item'.$plug]['path'].'" data-toggle="modal" data-target="#confirm-delete">
            ' . $_language->module['template_deinstallieren'] . '</button></th>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="modalLabel">' . $_language->module['template_deinstallieren'] . '</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
               <p>' . $_language->module['delete_info'] . '</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">' . $_language->module['template_back'] . '</button>
                <a class="btn btn-danger btn-ok">' . $_language->module['template_deinstallieren'] . '</a>
            </div>
        </div>
    </div>
</div>';
        
        
        
      } else {
        if($result['item'.$plug]['req']==$version) {
          $output .= '<th><a class="btn btn-success" style="width: 160px" href="?site=template_installer&do=install&id='.$plug.'&dir='.$result['item'.$plug]['path'].'">' . $_language->module['installation'] . '</a></th>';
          $output .= '  </tr>';
        } else {
          $output.= '<th><button class="btn btn-info" style="width: 160px">' . $_language->module['incompatible'] . '</button></th>';
          $output .= '  </tr>';
        }

    } 

    
  

} 


} CATCH(Exception $e) {
  echo $e->message(); return false;
}

$_language->readModule('template_installer');
$_language->readModule('template_installer', false, true);
 
  echo'<div class="card">
        <div class="card-header">
            <i class="fas fa-puzzle-piece"></i> Themes Installer
        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Themes Installer</li>
  </ol>
</nav>

<div class="card-body">

<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Template Installer</h4>
  '.$_language->module['info'].'
  <hr>
  <i class="fas fa-info-circle"></i> '.$_language->module['all_templates_1'].' '.$anz.' '.$_language->module['all_templates_2'].'
</div>
    
    <!-- END -->
    <!-- template_installer_content -->
    <table id="plugini" class="table table-bordered table-striped dataTable">
      <thead>
        <tr>
          <th style="width: 22%"><b>'. $_language->module['preview'] .'</b></th>
          <th style="width: 49%"><b>'.$_language->module['description'] .'</b></th>
          <th style="width: 14%"><b>'.$_language->module['version'] .'</b></th>
          <th><b>'.$_language->module['options'] .'</b></th>
        </tr>
      </thead>
      <tbody>
        '.$output.'
        <tfoot>
          <tr>
            <th style="width: 22%"><b>'. $_language->module['preview'] .'</b></th>
            <th style="width: 49%"><b>'.$_language->module['description'] .'</b></th>
            <th style="width: 14%"><b>'.$_language->module['version'] .'</b></th>
            <th><b>'.$_language->module['options'].'</b></th>
          </tr>
        </tfoot>
       </table>
      </div>
      </div>
    ';
}
}
