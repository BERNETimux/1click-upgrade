<?php

class loadTcaRemove
{

    public function title()
    {
        return 'Remove: loadTCA';
    }

    public function upgrade($list, $extensionDir)
    {
        $core = new \BERNETimux\Extensionupgrader\Core;
        $files = $core->recursiveFind('../'.$extensionDir, ['.php']);

        $problemFiles = [];
        foreach ($files as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);   //read file
                //t3lib_div::loadTCA(...);
                $string = preg_quote('t3lib_div::loadTCA(');
                $content = preg_replace("/(?<=\s)$string(.*)\);(?=\s)/", '', $content);

                //\t3lib_div::loadTCA(...);
                $string = preg_quote('\t3lib_div::loadTCA(');
                $content = preg_replace("/(?<=\s)$string(.*)\);(?=\s)/", '', $content);

                // TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA();
                $string = preg_quote('TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA();');
                $content = preg_replace("/(?<=\s)$string(?=\s)/", '', $content);

                // \TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA();
                $string = preg_quote('\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA();');
                $content = preg_replace("/(?<=\s)$string(?=\s)/", '', $content);

                file_put_contents($file, $content); //write file
            }
        }

        return $list;
    }
}

?>