<?php

class changeToNamespaces
{

    public function title()
    {
        return 'TYPO3-Namespaces (Tx_Fluid_Fluid -> \TYPO3\CMS\Fluid\Fluid)';
    }

    public function upgrade($list, $extensionDir)
    {
        $classAliasMapTo62 = include('inc/ClassAliasMapTo62.php');
        $classAliasMapTo76 = include('inc/ClassAliasMapTo76.php');
        $classAliasMapTo8 = include('inc/ClassAliasMapTo87.php');
        $classAliasMapTo7 = array_merge($classAliasMapTo62, $classAliasMapTo76); //combined, one step to 7
        // to start with long names first
        krsort($classAliasMapTo7);
        krsort($classAliasMapTo8);

        $core = new \BERNETimux\Extensionupgrader\Core;
        $files = $core->recursiveFind('../'.$extensionDir, ['.php']);

        $compatibilityProblemFiles = array();

        foreach ($files as $file) {
            //----- migrate to 7 ----
            foreach ($classAliasMapTo7 as $oldName => $newName) {
                $content = file_get_contents($file);   //read file
                if (strpos($content, $oldName)) {
                    $content = str_replace('\\'.$oldName, $newName, $content);    //repleace em all
                    $content = str_replace($oldName, $newName, $content);    //repleace em all
                    $content = str_replace(str_replace('\\', '\\\\', $oldName), str_replace('\\', '\\\\', $newName), $content);  // look for double \\ and convert in same way 
                    file_put_contents($file, $content); //write file
                }
            }


            //----- migrate to 8 ----
            foreach ($classAliasMapTo8 as $oldName => $newName) {
                $content = file_get_contents($file);   //read file
                if (strpos($content, $oldName)) {
                    $content = str_replace('\\'.$oldName, $newName, $content);    //repleace em all
                    $content = str_replace($oldName, $newName, $content);    //repleace em all
                    $content = str_replace(str_replace('\\', '\\\\', $oldName), str_replace('\\', '\\\\', $newName), $content);  // look for double \\ and convert in same way 
                    file_put_contents($file, $content); //write file
                }
            }

            //----- migrate to 9 ----
            //--- problems --
            if (preg_match("/Compatibility6|Compatibility7/i", $content)) {
                $compatibilityProblemFiles[] = $file;
            }
        }


        //List problematic Files
        if (count($compatibilityProblemFiles) > 0) {
            $list .= '<span style="color:#FF0000">Compatibility-Problems found:</span><br/>Please search in those files to Compatibility6 or Compatibility7 and fix it manually:<br/>';
            foreach ($compatibilityProblemFiles as $compatibilityProblemFile) {
                $list .= $compatibilityProblemFile.'<br/>';
            }
        }

        return $list;
    }
}

?>