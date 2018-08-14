<?php

class addRenderTypeInTca
{

    public function title()
    {
        return 'Add render type in tca';
    }

    public function upgrade($list, $extensionDir)
    {
        $core = new \BERNETimux\Extensionupgrader\Core;
        $files = $core->recursiveFind('../'.$extensionDir, ['.php']);

        $problemFiles = [];
        foreach ($files as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);   //read file

                $string1 = preg_quote('\'type\'');
                $string2 = preg_quote('=>');
                $string3 = preg_quote('\'select\'');
                $notString = preg_quote('\'renderType\' => \'selectSingle\',');
                $content = preg_replace('/'.$string1.'\s*'.$string2.'\s*'.$string3.'\s*,(?!\s*'.$notString.')/', '\'type\' => \'select\',' . PHP_EOL . '\'renderType\' => \'selectSingle\',', $content);

                file_put_contents($file, $content); //write file
            }
        }
        return $list;
    }
}

?>