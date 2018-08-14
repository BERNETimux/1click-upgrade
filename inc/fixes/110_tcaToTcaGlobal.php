<?php

class tcaToTcaGlobal
{

    public function title()
    {
        return '$TCA to $GLOBALS[\'TCA\']';
    }

    public function upgrade($list, $extensionDir)
    {

        $core = new \BERNETimux\Extensionupgrader\Core;
        $files = $core->recursiveFind('../'.$extensionDir, ['.php']);

        foreach ($files as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);   //read file
                $content = str_replace('$TCA', '$GLOBALS[\'TCA\']', $content);    //repleace em all
                file_put_contents($file, $content); //write file
            }
        }
        return $list;
    }
}

?>