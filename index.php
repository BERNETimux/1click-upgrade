<?php
$scriptVersion = '1.0.0-dev';

set_time_limit(7200); // 2h
ini_set('max_execution_time', 7200);
error_reporting(E_ALL);

/* ---- includes --- */
include('inc/functions.php');
include('inc/config.php');

$mainTemplate = file_get_contents('html/start.html');
$time = time();
$extensionDir = '';
$list = '';



//-------- Extension upgrade --------
if (isset($_POST['extensionUpgrade'])) {
    if ($_POST['extensionUpgrade'] != '') {
        $core = new \BERNETimux\Extensionupgrader\Core;
        $extensionDir = $core->UnixFileSys($_POST['extensionDir']);

        //only upgrade extensions, look for ext_emconf.php
        if (file_exists('../'.$extensionDir.'/ext_emconf.php')) {
            if ($handle = opendir('inc/fixes/')) {
                while (false !== ($fix = readdir($handle))) {
                    if ($fix != '.' && $fix != '..' && strpos($fix, '.php')) {
                        include('inc/fixes/'.$fix); // upgrade, change this to instance
                        $classNameArray = explode('_', str_ireplace('.php', '', $fix));
                        $className = $classNameArray[1];

                        $fixToExecute = new $className;
                        $list .= '<span style="color:#2caa4e;font-weight:bold;" >Fix: '.$fixToExecute->title().'</span><br/>'; //a new fix
                        $list = $fixToExecute->upgrade($list, $extensionDir);  //execute fix
                    }
                }
                closedir($handle);
            }

            $timeResult = (time() - $time);
            $mins = floor(($timeResult / 60));
            $sec = $timeResult - ($mins * 60);
            $list .= '<hr/><h1 style="color:#2caa4e">Finished: '.$mins.' min '.$sec.'s  </h1><br/><br/>';
        } else {
            $list = '<span style="font-size:14px;color:#FF0000;">Not an Extension! Path wrong..</span>';
        }
    }
}




$mainTemplate = str_replace('###scriptVersion###', $scriptVersion, $mainTemplate);
$mainTemplate = str_replace('###extensionDir###', $extensionDir, $mainTemplate);
$mainTemplate = str_replace('###list###', $list, $mainTemplate);
echo $mainTemplate;

?>
