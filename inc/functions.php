<?php

namespace BERNETimux\Extensionupgrader;

class Core
{

    public function recursiveFind($src, $fileExtensions = array(), $found = array())
    {
        if (is_dir($src)) {
            $dir = opendir($src);
            while (false !== ( $file = readdir($dir))) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($src.'/'.$file)) {
                        $found = $this->recursiveFind($src.'/'.$file, $fileExtensions, $found);
                    } else {
                        //by fileExtension
                        foreach ($fileExtensions as $fileExtension) {
                            if (strpos($file, $fileExtension)) {
                                $found[] = $this->UnixFileSys($src.'/'.$file);
                            }
                        }
                    }
                }
            }
            closedir($dir);
        }
        return $found;
    }

    public function UnixFileSys($path)
    {
        return str_replace('\\', '/', $path);
    }

    public function getExtkeyFromPath($extensionDir)
    {
        $extensionKey = preg_replace('/\s/', '', $extensionDir);
        if (preg_match('/\//', $extensionKey)) {
            $tempArray = preg_split('/\//', $extensionKey);
            $extensionKey = array_pop($tempArray);
        }
        return strtolower($extensionKey);
    }


 
    public function getVendor()
    {
        global $config;
        return $config['vendor'];
    }
}

?>