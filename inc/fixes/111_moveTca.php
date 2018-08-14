<?php

class moveTca
{

    public function title()
    {
        return 'Move TCA (ctrl)';
    }

    public function upgrade($list, $extensionDir)
    {

        //get all possible tca files        
        $core = new \BERNETimux\Extensionupgrader\Core;
        $filesFound = $core->recursiveFind('../'.$extensionDir.'/Configuration/TCA', ['.php']);
        $files = [];
        $countMoves = 0;
        $problemFiles = [];
        foreach ($filesFound as $file) {
            if (file_exists($file)) {
                if (preg_match("/return/i", file_get_contents($file)) == false) {
                    $files[] = $file;
                }
            }
        }



        //check tca files to the modifcation
        foreach ($files as $file) {
            $content = file_get_contents($file);

            //modify ext_tables.php 
            $extTables = '../'.$extensionDir.'/ext_tables.php';
            $extTablesContent = file_get_contents($extTables);


            $string1 = preg_quote('$GLOBALS[\'TCA\'][');
            if (preg_match('/'.$string1.'(.*)\]\s*=\s*(array\()/', $content, $matches)) {
                $txLine = $matches[0];
                $txName = preg_replace('/\'|\"\`|\´/', '', $matches[1]);


                //Look for the stuff
                $string1 = preg_quote('$GLOBALS[\'TCA\'][\''.$txName.'\']');
                if (preg_match('/'.$string1.'\s*=\s*(array\()/', $extTablesContent, $matches)) {
                    $tempArray = preg_split('/'.$string1.'\s*=\s*(array\()/', $extTablesContent, 2);
                    $partBefore = $tempArray[0];

                    $tempArray = preg_split('/\)\;/', $tempArray[1], 2);
                    $middlePart = $tempArray[0];
                    $partAfter = $tempArray[1];

                    $middlePart = $tempArray[0];


                    //remove the hole part
                    $extTablesContent = $partBefore.$partAfter;


                    //remove dynamic stuff from $middlePart
                    $string1 = preg_quote('\'dynamicConfigFile\'');
                    $middlePart = preg_replace('/\s*'.$string1."(.*)\s*\n/", "\n", $middlePart);


                    //icon problem
                    $string1 = preg_quote('$_EXTKEY');
                    $string2 = preg_quote('$EXTKEY_fix');
                    $middlePart = preg_replace('/'.$string1.'/', ''.$string2.'', $middlePart);



                    $string1 = preg_quote('$GLOBALS[\'TCA\'][\''.$txName.'\'][\'ctrl\']');
                    $content = preg_replace('/\'ctrl\'\s*=\>\s*'.$string1.'\s*,/', $middlePart, $content);



                    //change to "return"
                    $string1 = preg_quote($txLine);
                    $content = preg_replace('/'.$string1.'/', "\n".'$EXTKEY_fix = \''.$core->getExtkeyFromPath($extensionDir)."';\n".'return array(', $content);


                    //check if exists already
                    if (!file_exists('../'.$extensionDir.'/Configuration/TCA/'.$txName.'.php')) {
                        file_put_contents($extTables, $extTablesContent); //write ext_tables.php 
                        file_put_contents('../'.$extensionDir.'/Configuration/TCA/'.$txName.'.php', $content); //write file
                        unlink($file);
                        $countMoves++;
                    }
                }
            } else {
                $problemFiles[] = $file;
            }
        }




        //List problematic Files
        if (count($problemFiles) > 0) {
            $list .= '<span style="color:#FF0000">Problem! TCA-Move fail:<br/>'.<<<'EOD'
1. Copy the "CTRL"-part from the ext_tables.php into "Configuration/TCA/Member.php(example)":<br/> 
2. Replace the CTRL-part in the "Configuration/TCA/Member.php(example)" with the correct CTRL-Part:<br/>
3. Member.php: Change this line to $GLOBALS['TCA'][...] = array(...... ); into "return array("
4. Remove the hole " $GLOBALS['TCA']['tx_scrmember_domain_model_member'] = array(...... ); " from the ext_tables.php<br/>
5. Rename the PHP file to the name in the brackets wihthout any ' -> that means tx_scrmember_domain_model_member.php instead Member.php<br/><br/>
The following Files needs to be changed:</span><br/><br/>
EOD;
            $list .= '../'.$extensionDir.'/ext_tables.php<br/>';
            foreach ($problemFiles as $problemFile) {
                $list .= $problemFile.'<br/>';
            }
        }

        if ($countMoves > 0) {
            $list .= 'Files moved: '.$countMoves.'<br/>';
        }

        return $list;
    }
}

?>