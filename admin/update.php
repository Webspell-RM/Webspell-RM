<?php
/*-----------------------------------------------------------------\
| _    _  ___  ___  ___  ___  ___  __    __      ___   __  __       |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )    (  ,) (  \/  )      |
| \    /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__    )  \  )    (       |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)      |
|                       ___          ___                            |
|                      |__ \        / _ \                           |
|                         ) |      | | | |                          |
|                        / /       | | | |                          |
|                       / /_   _   | |_| |                          |
|                      |____| (_)   \___/                           |
\___________________________________________________________________/
/                                                                   \
|        Copyright 2005-2018 by webspell.org / webspell.info        |
|        Copyright 2018-2019 by webspell-rm.de                      |
|                                                                   |
|        - Script runs under the GNU GENERAL PUBLIC LICENCE         |
|        - It's NOT allowed to remove this copyright-tag            |
|        - http://www.fsf.org/licensing/licenses/gpl.html           |
|                                                                   |
|               Code based on WebSPELL Clanpackage                  |
|                 (Michael Gruber - webspell.at)                    |
\___________________________________________________________________/
/                                                                   \
|                     WEBSPELL RM Version 2.0                       |
|           For Support, Mods and the Full Script visit             |
|                       webspell-rm.de                              |
\------------------------------------------------------------------*/


 /*
 * Webspell RM Updater
 * 
 * @author Blubber
 * @version: 1.0
 * 
 * @modified by T-Seven 03.01.2021 19:48
 * @version: 1.1
 */
$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='update'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($_language->module[ 'access_denied' ]);
}
}

function curl_json2array($url){
$ssl = 0;
if (substr($url, 0, 7) == "http://") { $ssl=0; } else { $ssl=1;}  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$output = curl_exec($ch);
curl_close($ch);
return json_decode($output, true);
}


$action = '';
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
}

$v = '';
if(isset($_GET['v'])) {
    $v = $_GET['v'];
}

$updateserver = "aHR0cHM6Ly93d3cuYmFzZS53ZWJzcGVsbC1ybS5ldS8=";
$updatedocroot = $_SERVER['DOCUMENT_ROOT'];
include("../system/version.php");
$_language->readModule('update', false, true);

if($action == 'update' && $v !== '') {
  if (!$getnew = @file_get_contents(base64_decode($updateserver) . "vupdate.php")) {
    $getserverstatus = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>'.$_language->module[ 'step1' ].'</h5>
        </div>
        <div class=\'card-body\'>
        <div class=\'alert alert-danger\' role=\'alert\'>
          <i><b>'.$_language->module[ 'error' ].'</b></i>
        </div>
        </div>
      </div>
    '; 

  } else {
    $getserverstatus = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>'.$_language->module[ 'step1' ].'</h5>
        </div>
        <div class=\'card-body\'>
        <div class=\'alert alert-success\' role=\'alert\'>
          <i><b>'.$_language->module[ 'updateserversuccess' ].'</b></i>
        </div>
        </div>
      </div>
    '; 
  }
  $settings = safe_query("SELECT * FROM " . PREFIX . "settings");
  $ds = mysqli_fetch_array($settings);
  $dir = $v / 18;
  $versionsplit = str_split($dir);
  $url = base64_decode($updateserver).$dir.'/setup.json';
  $updatepfad = base64_decode($updateserver).$dir;
  $filesgrant = array();
  $noinstall = ''.'' .$loadfiles1 = ''. '' .$loadfiles2 = ''. '' .$loadfiles3 = ''. '' .$instfileerr = ''. '' .$resulttable = ''. '' .$wsinstallcomplete = ''. '' .$loadinstaller = '';
  $wsinstall = '0'.'' .$filesgranted = '0'.''.$cal = '0';
  $updatestop = '';
  $newreupdateversion = '';

  $ftp['user'] = base64_decode($ds['ftpuser']);
  $ftp['passwd'] = base64_decode($ds['ftppw']);
  $ftp['host'] = base64_decode($ds['ftpip']);
  $ftp['pfad'] = $ds['ftppath'];
  $ftp['port'] = $ds['ftpport'] / 42;



  if($ssl == '1') {
    $conn_id = @ftp_ssl_connect($ftp['host'],$ftp['port']);
  } else {
    $conn_id = @ftp_connect($ftp['host'],$ftp['port']);
  }
  if(!$conn_id) {
    echo'
      <div class=\'card\'>
          <div class=\'card-header\'>
              <h5>'.$_language->module[ 'ftp_host_check' ].'</h5>
          </div>
          <div class=\'card-body\'>
          <div class=\'alert alert-danger\' role=\'alert\'>
                <i><b>'.$_language->module[ 'ftp_host_error' ].'</b></i><br /><br />
                <a href="admincenter.php?site=update">
                    <button class="btn btn-primary" type="submit" name="submit">'.$_language->module[ 'back_to_overview' ].'</button>
                </a>
          </div>
          </div>
      </div>
    ';
  }
    
  @ftp_login($conn_id, $ftp['user'], $ftp['passwd']) or die('
      <div class=\'card\'>
          <div class=\'card-header\'>
              <h5>'.$_language->module[ 'ftp_login_check' ].'</h5>
          </div>
          <div class=\'card-body\'>
          <div class=\'alert alert-danger\'>
                <i><b>'.$_language->module[ 'ftp_login_error' ].'</b></i><br /><br />
                <a href="admincenter.php?site=update">
                    <button class="btn btn-primary" type="submit" name="submit">'.$_language->module[ 'back_to_overview' ].'</button>
                </a>
             </div>   
          </div>
      </div>
  ');    
  @ftp_chdir($conn_id, ''.$ftp['pfad'].'') or die('
      <div class=\'card\'>
          <div class=\'card-header\'>
              <h5>'.$_language->module[ 'ftp_path_check' ].'</h5>
          </div>
          <div class=\'card-body\'>
          <div class=\'alert alert-danger\' role=\'alert\'>
                <i><b>'.$_language->module[ 'ftp_path_error' ].'</b></i><br /><br />
                <a href="admincenter.php?site=update">
                    <button class="btn btn-primary" type="submit" name="submit">'.$_language->module[ 'back_to_overview' ].'</button>
                </a>
          </div>
          </div>
      </div>
  ');

  try {
    echo'
    <div class="col-lg-12"><br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-upload" aria-hidden="true"></i> '.$_language->module[ 'webspell_update' ].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admincenter.php?site=update">'.$_language->module[ 'webspellupdater' ].'</a></li>
            <li class="breadcrumb-item"><a href="admincenter.php?site=update">'.$_language->module[ 'check_version' ].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$_language->module[ 'update' ].'</li>
          </ol>
        </nav>  
        <div class="card-body">';
    $result = curl_json2array($url);
    if($result!="NULL") {
      if(!(@file(''.$updatepfad.'/install.php.txt'))) {
        $noinstall = '
          <div class=\'card\'>
            <div class=\'card-header\'>
              <h5>'.$_language->module[ 'step2' ].'</h5>
            </div>
            <div class=\'card-body\'>
            <div class=\'alert alert-danger\' role=\'alert\'>
              <i><b>'.$_language->module[ 'error_step2_1' ].'</b></i>
            </div>
          </div>
          </div>
        ';
        $updatestop = '1'; 
      } else {  
        $noinstall = '
          <div class=\'card\'>
            <div class=\'card-header\'>
              <h5>'.$_language->module[ 'step2' ].'</h5>
            </div>
            <div class=\'card-body\'>
            <div class=\'alert alert-success\' role=\'alert\'>
              <i><b>'.$_language->module[ 'error_step2_2' ].'</b></i>
            </div>
          </div>
          </div>
        ';
      // load files
      $index = 0;
      $files = count($result['items'][$index])-1;
      if($files) {
        for($i=1; $i<=$files; $i++) {
          $cal++;
          try {
            $file = './../'.$result['items'][$index]['file'.$i];
            $content = ''.$updatepfad.'/'.$result['items'][$index]['file'.$i].'.txt';
            $ftp['file'] = ''.$result['items'][$index]['file'.$i].'';
            $url2  = "ftp://".$ftp['user'].":".$ftp['passwd']."@".$ftp['host']."".$ftp['pfad']."/".$ftp['file'].""; 
            $ch = curl_init();
            $localfile = $content;
            $conn_id = @ftp_connect($ftp['host'],$ftp['port']) or die ("Cannot connect to host");     
            ftp_login($conn_id, $ftp['user'], $ftp['passwd']) or die("Cannot login");
            ftp_pasv($conn_id, true);
            ftp_chdir($conn_id, './');
            if(!strstr($ftp['file'], '.')) {
              @ftp_mkdir($conn_id, ''.$ftp['pfad'].'/'.$ftp['file'].''); // create directories that do not yet exist
            }
            if(strstr($ftp['file'], '.php') || strstr($ftp['file'], '.png') || strstr($ftp['file'], '.jpg') || strstr($ftp['file'], '.js') || strstr($ftp['file'], '.html') || strstr($ftp['file'], '.css') || strstr($ftp['file'], '.md') || strstr($ftp['file'], '.json') || strstr($ftp['file'], '.zip')) {
              @$upload = ftp_put($conn_id, ''.$ftp['pfad'].'/'.$ftp['file'].'' , $content, FTP_BINARY);
            }
            if(file_exists($file)) {
              $filesgrant[] = ''.$_language->module[ 'file_loaded' ].': '.$ftp['file'].'<br />';
              $filesgranted++;
            } else {
              $filesgrant[] = '<span style="color: #ff0000;">'.$_language->module[ 'file_not_loaded' ].': '.$ftp['file'].'</span><br />';
            }
          } CATCH(Exception $s) {
            echo $s->message();
          }

        }
      } 
      // del files
      $index = 1;
      $files = count($result['items'][$index])-1;
      if($files) {
        for($i=1; $i<=$files; $i++) {
          $cal++;
          try {
            $delfile = ''.$result['items'][$index]['file'.$i];
            $delfiles = './../'.$result['items'][$index]['file'.$i];
            $url2  = "ftp://".$ftp['user'].":".$ftp['passwd']."@".$ftp['host']."".$ftp['pfad']."/".$delfile.""; 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url2);
            curl_setopt($ch, CURLOPT_QUOTE, array('DELE /' . $delfile)); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_PORT, "".$ftp['port']."");
            curl_setopt($ch, CURLOPT_USERNAME, "".$ftp['user']."");
            curl_setopt($ch, CURLOPT_PASSWORD, "".$ftp['passwd']."");
            $url = curl_exec($ch);
            curl_close($ch);
            if(@file('.$delfile.') == false) {
              $filesgrant[] = ''.$_language->module[ 'file_deleted' ].': '.$delfile.'<br />';
              $filesgranted++;
            } else {
              $filesgrant[] = '<span style="color: #ff0000;">'.$_language->module[ 'file_not_deleted' ].': '.$delfile.'</span><br />';
            }
          } CATCH(Exception $s) {
            echo $s->message();
          }

        }
      }
    }
   }
  } CATCH (Exception $e) {
    echo $e->message();
  }
  if($updatestop != '1') {
    if($cal - $filesgranted == '0') {
      $loadinstaller = '<i><b>'.$_language->module[ 'all_files_have_been_edited' ].':  '.$filesgranted.' '.$_language->module[ 'of' ].' '.$cal.' </b></i>';
      if(file_exists('../install.php')) {
        include('../install.php');
        $instfileerr = $resulttable;
        if($wsinstall == '1') {
          $wsinstallcomplete = '
            <div class="alert alert-success"><i>'.$_language->module[ 'installcomplete_1' ].': <strong>'.$versionsplit['0'].'.'.$versionsplit['1'].'.'.$versionsplit['2'].'</strong> '.$_language->module[ 'installcomplete_2' ].'</i></div>
            <a href="admincenter.php?site=update">
              <button class="btn btn-primary" type="submit" name="submit">'.$_language->module[ 'back_to_overview' ].'</button>
            </a>
          ';
          $delfile = 'install.php';
          $url2  = "ftp://".$ftp['user'].":".$ftp['passwd']."@".$ftp['host']."".$ftp['pfad']."/install.php"; 
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url2);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
          curl_setopt($ch, CURLOPT_QUOTE, array('DELE /' . $delfile)); 
          curl_setopt($ch, CURLOPT_PORT, "".$ftp['port']."");
          curl_setopt($ch, CURLOPT_USERNAME, "".$ftp['user']."");
          curl_setopt($ch, CURLOPT_PASSWORD, "".$ftp['passwd']."");
          $url = curl_exec($ch);
          curl_close($ch);
        } else {
          $wsinstallcomplete = '
            <div class=\'card\'>
              <div class=\'card-header\'>
                <h5>'.$_language->module[ 'step4' ].'</h5>
              </div>
              <div class=\'card-body\'>
              <div class=\'alert alert-danger\' role=\'alert\'>
                <i><b>'.$_language->module[ 'syq_error' ].'</b></i>
              </div>
            </div>
            </div>
          ';
 
        }
      }
    } else {
      $loadinstaller = '<br /><span style="color: #ff0000;"><i><b>'.$_language->module[ 'not_all_files_edited' ].'<br />Result:   '.$filesgranted.' '.$_language->module[ 'of' ].'  '.$cal.'</b></i></span>';
    }
  
    $loadfiles1 = '
          <div class=\'card\'>
            <div class=\'card-header\'>
              <h5>'.$_language->module[ 'step3' ].'</h5>
            </div>
            <div class=\'card-body\'>
            <div class=\'alert alert-info\' role=\'alert\'>
        ';
        foreach ($filesgrant as $filesgranted) {
          $loadfiles2 .= $filesgranted;
        }
        $loadfiles2 .= $loadinstaller;
        $loadfiles3 = '
             </div>
           </div>
           </div>
    ';
  } 
  
  echo'
            '.$getserverstatus.'<br />
            '.$noinstall.'<br />
            '.$loadfiles1.' '.$loadfiles2.' '.$loadfiles3.'
            '.$instfileerr.'
            '.$wsinstallcomplete.'
          </div>
        </div>
      </div>
   
  ';
} elseif($action == 'ftpcheck') {
  if (substr(getCurrentUrl(), 0, 7) == "http://") { $ssl = '0'; } else { $ssl = '1';}

  $settings = safe_query("SELECT * FROM " . PREFIX . "settings");
  $ds = mysqli_fetch_array($settings);

  $ftp['user'] = base64_decode($ds['ftpuser']);
  $ftp['passwd'] = base64_decode($ds['ftppw']);
  $ftp['host'] = base64_decode($ds['ftpip']);
  $ftp['pfad'] = $ds['ftppath'];
  $ftp['port'] = $ds['ftpport'] / 42;
  $dir = 'check';
  $noinstall = ''.'' .$loadfiles1 = ''. '' .$loadfiles2 = ''. '' .$loadfiles3 = ''. '' .$instfileerr = ''. '' .$resulttable = ''. '' .$wsinstallcomplete = ''. '' .$loadinstaller = '';
  $wsinstall = '0'.'' .$filesgranted = '0'.''.$cal = '0';

  $url = base64_decode($updateserver).$dir.'/setup.json';
  $updatepfad = base64_decode($updateserver).$dir;
  $filesgrant = array();

  //Scheck IP
  if($ssl == '1') {
    $conn_id = @ftp_ssl_connect($ftp['host'],$ftp['port']);
  } else {
    $conn_id = @ftp_connect($ftp['host'],$ftp['port']);
  }
  if($conn_id) {
    $hostcheck = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>IP / Servername</h5>
        </div>
        <div class=\'card-body\'>
          <div class=\'alert alert-success\' role=\'alert\'>
            <i><b>IP / Servername richtig</b></i><br /><br />
          </div>
        </div>
      </div>
    ';
  } else {
    $hostcheck = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>IP / Servername</h5>
        </div>
        <div class=\'card-body\'>
          <div class=\'alert alert-danger\' role=\'alert\'>
            <i><b>IP / Servername falsch</b></i><br /><br />
          </div>
        </div>
      </div>
    ';
  }

  if (@ftp_login($conn_id, $ftp['user'], $ftp['passwd']) == '') { 
    $logincheck = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>FTP-Login</h5>
        </div>
        <div class=\'card-body\'>
          <div class=\'alert alert-danger\' role=\'alert\'>
            <i><b>FTP-Login fehlgeschlagen</b></i><br /><br />
          </div>
        </div>
      </div>
    ';
  } else {
    $logincheck = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>FTP-Login</h5>
        </div>
        <div class=\'card-body\'>
          <div class=\'alert alert-success\' role=\'alert\'>
            <i><b>FTP-Login erfolgreich</b></i><br /><br />
          </div>
        </div>
      </div>
    ';
  }

  if (@ftp_chdir($conn_id, $ftp['pfad']) == '') { 
    $pfadcheck = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>Pfad&uuml;berpr&uuml;fung</h5>
        </div>
        <div class=\'card-body\'>
          <div class=\'alert alert-danger\' role=\'alert\'>
            <i><b>Pfad&uuml;berpr&uuml;fung fehlgeschlagen</b></i><br /><br />
          </div>
        </div>
      </div>
    ';
  } else {
    $pfadcheck = '
      <div class=\'card\'>
        <div class=\'card-header\'>
          <h5>Pfad&uuml;berpr&uuml;fung</h5>
        </div>
        <div class=\'card-body\'>
          <div class=\'alert alert-success\' role=\'alert\'>
            <i><b>Pfad&uuml;berpr&uuml;fung erfolgreich</b></i><br /><br />
          </div>
        </div>
      </div>
    ';
  }

  try {
    $result = curl_json2array($url);
    if($result!="NULL") {
      if(!(@file(''.$updatepfad.'/install.php.txt'))) {
        $noinstall = '
          <div class=\'card\'>
            <div class=\'card-header\'>
              <h5>'.$_language->module[ 'step2' ].'</h5>
            </div>
            <div class=\'card-body\'>
            <div class=\'alert alert-danger\' role=\'alert\'>
              <i><b>'.$_language->module[ 'error_step2_1' ].'</b></i>
            </div>
            </div>
          </div>
        ';
        $updatestop = '1'; 
      } else {  
        $noinstall = '
          <div class=\'card\'>
            <div class=\'card-header\'>
              <h5>'.$_language->module[ 'step2' ].'</h5>
            </div>
            <div class=\'card-body\'>
            <div class=\'alert alert-success\' role=\'alert\'>
              <i><b>'.$_language->module[ 'error_step2_2' ].'</b></i>
            </div>
            </div>
          </div>
        ';
      // load files
      $index = 0;
      $files = count($result['items'][$index])-1;
      if($files) {
        for($i=1; $i<=$files; $i++) {
          $cal++;
          try {
            $file = './../'.$result['items'][$index]['file'.$i];
            $content = ''.$updatepfad.'/'.$result['items'][$index]['file'.$i].'.txt';
            $ftp['file'] = ''.$result['items'][$index]['file'.$i].'';
            $url2  = "ftp://".$ftp['user'].":".$ftp['passwd']."@".$ftp['host']."".$ftp['pfad']."/".$ftp['file'].""; 
            $ch = curl_init();
            $localfile = $content;
            @ftp_login($conn_id, $ftp['user'], $ftp['passwd']);
            @ftp_pasv($conn_id, true);
            @ftp_chdir($conn_id, './');
            if(!strstr($ftp['file'], '.')) {
              ftp_mkdir($conn_id, ''.$ftp['pfad'].'/'.$ftp['file'].''); // create directories that do not yet exist
            }
            if(strstr($ftp['file'], '.php') || strstr($ftp['file'], '.png') || strstr($ftp['file'], '.jpg') || strstr($ftp['file'], '.js') || strstr($ftp['file'], '.html') || strstr($ftp['file'], '.css') || strstr($ftp['file'], '.md') || strstr($ftp['file'], '.json') || strstr($ftp['file'], '.zip')) {
              @$upload = ftp_put($conn_id, ''.$ftp['pfad'].'/'.$ftp['file'].'' , $content, FTP_BINARY);
            }
            
            if(file_exists($file)) {
              $filesgrant[] = ''.$_language->module[ 'file_loaded' ].': '.$ftp['file'].'<br />';
              $filesgranted++;
              $wsinstall = '1';
            } else {
              $filesgrant[] = '<span style="color: #ff0000;">'.$_language->module[ 'file_not_loaded' ].': '.$ftp['file'].'</span><br />';
            }
          } CATCH(Exception $s) {
            echo $s->message();
          }

        }
      } 
    }
   }
  } CATCH (Exception $e) {
    echo $e->message();
  }

  if($cal - $filesgranted == '0') {
    $loadinstaller = '<i><b>'.$_language->module[ 'all_files_have_been_edited' ].':  '.$filesgranted.' '.$_language->module[ 'of' ].' '.$cal.' </b></i>';
    if(file_exists('../install.php')) {
      //include('../install.php');
      $instfileerr = $resulttable;
      if($wsinstall == '1') {
        $wsinstallcomplete = '
          <div class="alert alert-success"><i>'.$_language->module[ 'installcomplete_1' ].': '.$_language->module[ 'installcomplete_2' ].'</i></div>
          <a href="admincenter.php?site=update">
            <button class="btn btn-primary" type="submit" name="submit">'.$_language->module[ 'back_to_overview' ].'</button>
          </a>
        ';
        $delfile = 'install.php';
        if($ssl == '1') {
          $url2  = "ftps://".$ftp['user'].":".$ftp['passwd']."@".$ftp['host']."".$ftp['pfad']."/install.php"; 
        } else {
          $url2  = "ftp://".$ftp['user'].":".$ftp['passwd']."@".$ftp['host']."".$ftp['pfad']."/install.php";           
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_QUOTE, array('DELE /' . $delfile)); 
        curl_setopt($ch, CURLOPT_PORT, "".$ftp['port']."");
        curl_setopt($ch, CURLOPT_USERNAME, "".$ftp['user']."");
        curl_setopt($ch, CURLOPT_PASSWORD, "".$ftp['passwd']."");
        $url = curl_exec($ch);
        curl_close($ch);
      } else {
        $wsinstallcomplete = '
          <div class=\'card\'>
            <div class=\'card-header\'>
              <h5>'.$_language->module[ 'step4' ].'</h5>
            </div>
            <div class=\'card-body\'>
              <div class=\'alert alert-danger\' role=\'alert\'>
                <i><b>'.$_language->module[ 'syq_error' ].'</b></i>
              </div>
            </div>
          </div>
        ';
 
      }
    }
  } else {
    $loadinstaller = '<br /><span style="color: #ff0000;"><i><b>'.$_language->module[ 'not_all_files_edited' ].'<br />Result:   '.$filesgranted.' '.$_language->module[ 'of' ].'  '.$cal.'</b></i></span>';
  }
  
  $loadfiles1 = '
    <div class=\'card\'>
      <div class=\'card-header\'>
        <h5>Lade Dateien</h5>
      </div>
      <div class=\'card-body\'>
        <div class=\'alert alert-info\' role=\'alert\'>
  ';
  foreach ($filesgrant as $filesgranted) {
    $loadfiles2 .= $filesgranted;
  }
  $loadfiles2 .= $loadinstaller;
  $loadfiles3 = '
        </div>
      </div>
    </div>
  ';
  
  $installicheck = '
    '.$loadfiles1.' '.$loadfiles2.' '.$loadfiles3.'
  ';

  echo'
    <div class="col-lg-12"><br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-upload" aria-hidden="true"></i> '.$_language->module[ 'webspell_update' ].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admincenter.php?site=update">'.$_language->module[ 'webspellupdater' ].'</a></li>
            <li class="breadcrumb-item"><a href="admincenter.php?site=update&action=ftpcheck">'.$_language->module[ 'check_version' ].' FTP-check</a></li>
          </ol>
        </nav>  
        <div class="card">
          <div class="card-header">
            <i class="fas fa-tasks"></i> '.$_language->module[ 'ftp_settings' ].' &Uuml;berpr&uuml;fe Login:
          </div>
          <div class="card-body">
            <div class="row">
              <div class="card-body">
                <div class="form-group">
                  '.$hostcheck.'
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  '.$logincheck.'
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  '.$pfadcheck.'
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  '.$installicheck.'
                </div>
              </div>
             </div>
            &nbsp;&nbsp;<a class="btn btn-primary" href="admincenter.php?site=update">'.$_language->module[ 'back_to_overview' ].'</a>
          </div>
        </div>
      </div>
    </div>
  ';
} else {
  if(isset($_POST['ftpip'])){
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      $ftpip = isset($_POST[ 'ftpip' ]) ? $_POST[ 'ftpip' ] : '';
      $ftpport = isset($_POST[ 'ftpport' ]) ? $_POST[ 'ftpport' ] * 42 : '';
      $ftppath = isset($_POST[ 'ftppath' ]) ? $_POST[ 'ftppath' ] : '';
      $ftpuser = isset($_POST[ 'ftpuser' ]) ? $_POST[ 'ftpuser' ] : '';
      $ftppw = isset($_POST[ 'ftppw' ]) ? $_POST[ 'ftppw' ] : '';

      safe_query("
        UPDATE
          " . PREFIX . "settings
        SET
          `ftpip` = '" . base64_encode($ftpip) . "',
          `ftpport` = '" . $ftpport . "',
          `ftppath` = '" . $ftppath . "',
          `ftpuser` = '" . base64_encode($ftpuser) . "',
          `ftppw` = '" . base64_encode($ftppw) . "'
        WHERE
          settingID='1'
      ");
      echo '<div class="alert alert-success"><i><b>'.$_language->module['data saved'].'</b></i></div>';
    } else {
      echo '<div class="alert alert-danger"><i><b>'.$_language->module['transaction_invalid'].'</b></i></div>';
    }
  }

  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();

  $settings = safe_query("SELECT * FROM " . PREFIX . "settings");
  $ds = mysqli_fetch_array($settings);

  if (!$getnew = file_get_contents(base64_decode($updateserver) . "vupdate.php")) {
    echo '<i><b>' . $_language->module[ 'error' ] . '&nbsp;' . base64_decode($updateserver) . '.</b></i>';
  } else {
    $latest = explode(".", $getnew);
    $latestversion = ''.$latest['0'].''.$latest['1'].''.$latest['2'].'';
    $ownversion = explode(".", $version);     
    $ownversion = ''.$ownversion['0'].''.$ownversion['1'].''.$ownversion['2'].'';
    $updatebutton = '';
    $newupdateversion = ($ownversion + 1) * 18;
    $newreupdateversion = $ownversion * 18;
    if($ds['ftpip'] !== '' && $ds['ftpport'] !== '' && $ds['ftpuser'] !== '' && $ds['ftppw'] !== '' && $ds['ftppath'] !== '') { 
        $updatebuttontrue = '
            <a href="admincenter.php?site=update&amp;action=update&v='.$newupdateversion.'">
                <button class="btn btn-primary" type="submit" name="submit">'.$_language->module['update_now'].'</button>
            </a>
        '; 
        $checkftp = '
            <a class="btn btn-primary" href="admincenter.php?site=update&amp;action=ftpcheck">
                '.$_language->module['update_now'].' Check FTP
            </a>
        ';
    } else { 
        $updatebuttontrue = '
            <button class="btn btn-primary" type="submit" name="submit" disabled>'.$_language->module['fill_in_ftp_settings'].'</button>
        ';
        $checkftp = ''; 
    }
    if ($ownversion < $latestversion) {
      $updatetxt = '<span style="color: #ff0000;">'.$_language->module['new_version_available'].'</span>';
      $updatebutton = '
        <div class="alert alert-info"> 
          <h4><strong>'.$_language->module['update_info1'].'</strong></h4><br />
          '.$_language->module['update_info2'].'</div><br />
        '.$updatebuttontrue.'
      ';
    } elseif ($ownversion == $latestversion) {
      if($ds['ftpip'] !== '' && $ds['ftpport'] !== '' && $ds['ftpuser'] !== '' && $ds['ftppw'] !== '' && $ds['ftppath'] !== '') { 
        $updatetxt =  '
          <span style="color: #ff0000;">'.$_language->module['update_info3'].'</span><br /><br />'.$_language->module['update_info4'].'
          <a href="admincenter.php?site=update&amp;action=update&v='.$newreupdateversion.'">
              <button class="btn btn-primary" type="submit" name="submit">'.$_language->module['re_update'].'</button>
          </a>
        '; 
      } else { 
        $updatetxt =  '
          <span style="color: #ff0000;">'.$_language->module['update_info3'].'</span><br /><br />
          '.$_language->module['update_info4'].'<a href="admincenter.php?site=update&amp;action=update&v='.$newreupdateversion.'">
              <button class="btn btn-primary" type="submit" name="submit" disabled>'.$_language->module['fill_in_ftp_settings'].'</button>
          </a>
        '; 
      }
    } else {
      $updatetxt =  '<span style="color: #ff0000;">'.$_language->module['update_info5'].'</span>';
    }
  }

  if($ds['ftppw'] !== '') { $pw = base64_decode($ds['ftppw']); } else { $pw = ''; }
  if($ds['ftpuser'] !== '') { $user = base64_decode($ds['ftpuser']); } else { $user = ''; }
  if($ds['ftpip'] !== '') { $ip = base64_decode($ds['ftpip']); } else { $ip = ''; }
  if($ds['ftpport'] !== '') { $port = $ds['ftpport'] / 42; } else { $port = ''; }

  echo'
    <div class="col-lg-12"><br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-upload" aria-hidden="true"></i> '.$_language->module[ 'webspell_update' ].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admincenter.php?site=update">'.$_language->module[ 'webspellupdater' ].'</a></li>
            <li class="breadcrumb-item"><a href="admincenter.php?site=update">'.$_language->module[ 'check_version' ].'</a></li>
          </ol>
        </nav>  
        <div class="card-body">
            <div class="alert alert-success"> 
              <strong>'.$_language->module[ 'your_version' ].':</strong> '.$version.'<br /><strong>'.$_language->module[ 'latest_version' ].':</strong> '.$getnew.' <br />
              <strong>'.$_language->module[ 'result' ].':</strong> '.$updatetxt.'
            </div>
            '.$updatebutton.'
          </div>
        <form class="form-horizontal" method="post" id="post" name="save" action="admincenter.php?site=update">
        <div class="card">
          <div class="card-header">
            <i class="fas fa-tasks"></i> '.$_language->module[ 'ftp_settings' ].':
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="row bt">
                  <div class="col-md-4">
                    '.$_language->module[ 'server_ip' ].':
                  </div>
                  <div class="col-md-8">
                    <span class="text-muted mdall"><em data-toggle="tooltip" title="'.$_language->module[ 'ftp_ip' ].'"><input class="form-control" name="ftpip" type="text" value="'.$ip.'" size="35"></em></span>
                  </div>
                </div>
                <div class="row bt">
                  <div class="col-md-4">
                    '.$_language->module[ 'server_port' ].':
                  </div>
                  <div class="col-md-8">
                    <span class="text-muted mdall"><em data-toggle="tooltip" title="'.$_language->module[ 'ftp_port' ].'"><input class="form-control" type="text" name="ftpport" value="'.$port.'" size="35"></em></span>
                  </div>
                </div>
                <div class="row bt">
                  <div class="col-md-4">
                    '.$_language->module[ 'server_pfad' ].':
                  </div>
                  <div class="col-md-8">
                    <span class="text-muted mdall"><em data-toggle="tooltip" title="'.$_language->module[ 'ftp_pfad' ].'"><input class="form-control" type="text" name="ftppath" value="'.$ds['ftppath'].'" size="35"></em></span>
                  </div>
                </div>
              </div>
             <div class="col-md-6">
                <div class="row bt">
                  <div class="col-md-4">
                    '.$_language->module[ 'server_username' ].':
                  </div>
                  <div class="col-md-8">
                    <span class="text-muted mdall"><em data-toggle="tooltip" title="'.$_language->module[ 'ftp_username' ].'"><input class="form-control" name="ftpuser" type="text" value="'.$user.'" size="35"></em></span>
                  </div>
                </div>
                <div class="row bt">
                  <div class="col-md-4">
                    '.$_language->module[ 'server_password' ].':
                  </div>
                  <div class="col-md-8">
                    <span class="text-muted mdall"><em data-toggle="tooltip" title="'.$_language->module[ 'ftp_password' ].'"><input class="form-control" type="password" name="ftppw" value="'.$pw.'" size="35"></em></span>
                  </div>
                </div>
                <div class="row bt">
                  <div class="col-md-4">
                    
                  </div>
                  <div class="col-md-8">
                    <span class="text-muted mdall">'.$checkftp.'</span>
                  </div>
                </div>
              </div>
            </div>
            &nbsp;&nbsp;<button class="btn btn-primary" type="submit" name="submit">'.$_language->module[ 'save' ].'</button>
            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
          </div>
        </div>
        </form>
      </div>
    </div>
  ';
}
?>