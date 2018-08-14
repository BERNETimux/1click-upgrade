<?php

class missingMainLayout
{

    public function title()
    {
        return 'Change Default.html to Main.html';
    }

    public function upgrade($list, $extensionDir)
    {
        //detect if its possible to fix             
        if (is_dir('../'.$extensionDir.'/Resources/Private')) {

            //get all files to modifiy
            $oldLayoutDefaultFile = '../'.$extensionDir.'/Resources/Private/Layouts/Default.html';
            $newLayoutMainFile = '../'.$extensionDir.'/Resources/Private/Layouts/Main.html';
            if (file_exists($oldLayoutDefaultFile) && file_exists($newLayoutMainFile) == false) {
                //Rename the file
                rename($oldLayoutDefaultFile, $newLayoutMainFile);
                $list .= 'Default.html renamed to Main.html<br>';

                //update all template files
                $core = new \BERNETimux\Extensionupgrader\Core;
                $files = $core->recursiveFind('../'.$extensionDir.'/Resources/Private/Templates', ['.html', '.htm']);
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        $content = file_get_contents($file);
                        if (preg_match('/\<f:layout\s*name=["|\']Default["|\']\s*\/\>/i', $content)) {
                            $content = preg_replace('/\<f:layout\s*name=["|\']Default["|\']\s*\/\>/i', '<f:layout name="Main" />', $content);
                            file_put_contents($file, $content); //write file
                        }
                    }
                }

                //Default.ts -> Main.ts
                $fileDefaultTS = '../'.$extensionDir.'/Resources/Private/BackendLayouts/Default.ts';
                $fileMainTS = '../'.$extensionDir.'/Resources/Private/BackendLayouts/Main.ts';
                if (file_exists($fileDefaultTS)) {
                    if (file_exists($fileMainTS) == false) {
                        rename($fileDefaultTS, $fileMainTS);
                    } else {
                        $list .= '<span style="color:#FF0000">Error Renaming of Default.ts faild! (main.ts already exists!)</span><br/>';
                    }
                }

                //locallang.xml
                $fileLocallangxml = '../'.$extensionDir.'/Resources/Private/BackendLayouts/locallang.xml';
                if (file_exists($fileLocallangxml)) {
                    $content = file_get_contents($fileLocallangxml);
                    $content = preg_replace('/\<label\s*index=["|\']Default["|\']\>/i', '<label index="Main">', $content);
                    file_put_contents($fileLocallangxml, $content); //write file
                } else {
                    $list .= '<span style="color:#FF0000">Error of patching locallang.xml - file not exits!</span><br/>';
                }
            }
        } else {
            $list .= 'Not possible to fix, perhaps old Extension with BackendLayouts in DB<br/>';
        }
        return $list;
    }
}

?>