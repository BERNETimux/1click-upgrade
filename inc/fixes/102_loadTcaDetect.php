<?php

class loadTcaDetect
{
    public function title(){
        return 'Detect: loadTCA';
    }
    
    public function upgrade($list, $extensionDir)
    {
        $core = new \BERNETimux\Extensionupgrader\Core;
        $files = $core->recursiveFind('../'.$extensionDir, ['.php']);
        
        $problemFiles = [];        
        foreach ($files as $file) { 
            if(file_exists($file))
            {
                $content = file_get_contents($file);   //read file
                if (preg_match("/loadTCA/i", $content)) {
                      $problemFiles[] = $file;
                }
            }
        }
        
        //List problematic Files
        if(count($problemFiles)>0){
           $list .= '<span style="color:#FF0000">Warning, loadTCA found!:</span><br/>';  
           foreach($problemFiles as $problemFile){
               $list .= $problemFile.'<br/>';
           }
        }
        
        return $list;
    }
}

?>