<?php
    /**
        Magento module installer class
    */

    require_once(dirname(__FILE__) . '/magictoolbox.installer.core.class.php');

    class MagicToolboxMagentoModuleInstallerClass extends MagicToolboxCoreInstallerClass {

        private $design = 'base';
        private $isCompilerDisabled = true;
        private $mageVersion;
        private $moduleDisabled = false;
        private $foundCopies = array();

        function MagicToolboxMagentoModuleInstallerClass() {
            $this->dir = dirname(dirname(__FILE__));
            $this->modDir = dirname(__FILE__) . '/module';
            //for fix url's in css files
            $this->runMage();
        }

        function runMage() {

            if(file_exists($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml')) {
                $this->moduleDisabled = @rename($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml', $this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml.backup');
            }

             // go to magento core folder
            chdir($this->dir);
            ob_start();
            // include core magento file (load front page)
            include('index.php');
            ob_end_clean();

            //check Magento version
            $mageVersion = Mage::getVersion();
            $pattern = "/([0-9]+\.[0-9]+\.[0-9]+)(?:\.(?:[0-9]+))*/";
            $matches = array();
            if(preg_match($pattern, $mageVersion, $matches)) {
                $this->mageVersion = $matches[1];
                if(version_compare($this->mageVersion, '1.4.0', '<')) {
                    $this->design = 'default';
                }
            }

            $this->resDir = "/" . preg_replace('/https?:\/\/[^\/]+\//is','',str_replace('/magiczoomplus.css', '', Mage::getSingleton('core/design_package')->getSkinUrl('css/magiczoomplus.css')));
            //TODO: find better way to get web path
            $this->resDir = preg_replace('/(skin\/frontend\/)([^\/]+\/[^\/]+)/is', '$1base/default', $this->resDir);

            if($this->design == 'default') {
                $this->resDir = str_replace('/base/', '/default/', $this->resDir);
            }
            //this hack need if Web Base URL contains {{base_url}}
            $this->resDir = str_replace('/magiczoomplus/', '/', $this->resDir);

            if(defined('COMPILER_INCLUDE_PATH')) {
                $this->setError('Please, disable Magento Compiler before continue.');
                $this->isCompilerDisabled = false;
            }

            if($this->moduleDisabled) {
                $resource = Mage::getSingleton('core/resource');
                $connection = $resource->getConnection('core_write');
                $table = $resource->getTableName('core/resource');
                $result = $connection->query("SELECT * FROM {$table} WHERE code = 'magiczoomplus_setup'");
                if($result) {
                    $rows = $result->fetch(PDO::FETCH_ASSOC);
                    if($rows) {
                        $connection->query("DELETE FROM {$table} WHERE code = 'magiczoomplus_setup'");
                    }
                }
                //delete old options
                $result = $connection->query("DROP TABLE IF EXISTS magiczoomplus");
            } else {
                @rename($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml.backup', $this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml');
            }

            $availableDesigns = Mage::getSingleton('core/design_source_design')->getAllOptions();
            foreach($availableDesigns as $pKey => $package) {
                if(is_array($package['value'])) {
                    foreach($package['value'] as $tKey => $theme) {
                        if($package['label'] == $this->design && $theme['label'] == 'default') continue;
                        if(file_exists($this->dir . '/app/design/frontend/'.$package['label'].'/'.$theme['label'].'/template/magiczoomplus')) {
                            $this->foundCopies[] = '/app/design/frontend/'.$package['label'].'/'.$theme['label'].'/template/magiczoomplus';
                        }
                        if(file_exists($this->dir . '/app/design/frontend/'.$package['label'].'/'.$theme['label'].'/layout/magiczoomplus.xml')) {
                            $this->foundCopies[] = '/app/design/frontend/'.$package['label'].'/'.$theme['label'].'/layout/magiczoomplus.xml';
                        }
                    }
                }
            }

            // return to installer folder
            chdir(dirname(__FILE__));

            return true;
        }

        function checkPlace() {
            $this->setStatus('check', 'place');
            if(!is_dir($this->dir . '/app') && !file_exists($this->dir . '/index.php')) {
                $this->setError('Wrong location: please upload the files from the ZIP archive to the Magento store directory.');
                return false;
            }
            return $this->isCompilerDisabled;
        }

        function checkPerm() {
            $this->setStatus('check', 'perm');
            $files = array(
                // directory
                '/app/code/local',
                '/app/design/adminhtml/default/default/layout',
                '/app/design/adminhtml/default/default/template',
                '/app/etc/modules',
                '/js',
                '/app/design/frontend/'.$this->design.'/default/layout',
                '/app/design/frontend/'.$this->design.'/default/template',
                '/skin/adminhtml/default/default',
                '/skin/frontend/'.$this->design.'/default/css',
                '/skin/frontend/'.$this->design.'/default/images',
                '/skin/frontend/'.$this->design.'/default/js',
            );

            $excludeDesign = ($this->design == 'base')?'default':'base';
            foreach($this->getFilesRecursive($this->modDir) as $file) {
                if(strpos($file, '/app/design/frontend/'.$excludeDesign) === 0) continue;
                if(strpos($file, '/skin/frontend/'.$excludeDesign) === 0) continue;
                if(file_exists($this->dir . $file)) {
                    $files[] = $file;
                }
            }

            if(file_exists($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml')) {
                $files[] = '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml';
            }

            list($result, $wrang) = $this->checkFilesPerm($files);
            if(!$result) {
                $this->setError('This installer need to modify some Magento store files.');
                $this->setError('Please check write access for following files and/or dirrectories of your Magento store:');
                $this->setError(array_unique($wrang), '&nbsp;&nbsp;&nbsp;-&nbsp;');
                return false;
            }
            return true;
        }

        function getFilesRecursive($path, $firstCall = true) {
            $result = array();
            $files = glob($path . '/*');
            if($files !== false) {
                foreach($files as $file) {
                    if(is_dir($file)) {
                        $result = array_merge($result, $this->getFilesRecursive($file, false));
                    } else {
                        $result[] = $file;
                    }
                }
            }
            if($firstCall) {
                $result = str_replace($path, '', $result);
            }
            return $result;
        }

        function installFiles() {
            $this->setStatus('install', 'files');

            //copy app, js, skin folders
            $this->copyDir($this->modDir . '/app/code', $this->dir . '/app/code');
            $this->copyDir($this->modDir . '/app/design/adminhtml', $this->dir . '/app/design/adminhtml');
            $this->copyDir($this->modDir . '/app/design/frontend/'.$this->design, $this->dir . '/app/design/frontend/'.$this->design);
            $this->copyDir($this->modDir . '/js', $this->dir . '/js');
            $this->copyDir($this->modDir . '/skin/adminhtml', $this->dir . '/skin/adminhtml');
            $this->copyDir($this->modDir . '/skin/frontend/'.$this->design, $this->dir . '/skin/frontend/'.$this->design);

            //modify config.xml
            if(isset($this->mageVersion) && version_compare($this->mageVersion, '1.4.1', '<')) {
                $fileContents = file_get_contents($this->dir . '/app/code/local/MagicToolbox/MagicZoomPlus/etc/config.xml');
                $rCount = 0;
                $fileContents = preg_replace('/<!--(<page>.*?MagicToolbox_MagicZoomPlus_Block_Html_Head.*?<\/page>)-->/is', '$1', $fileContents, 1, $rCount);
                if($rCount) {
                    file_put_contents($this->dir . '/app/code/local/MagicToolbox/MagicZoomPlus/etc/config.xml', $fileContents);
                }
            }

            //this must be last
            $this->copyDir($this->modDir . '/app/etc', $this->dir . '/app/etc');

            if(count($this->foundCopies)) {
                $this->setError('The following layout and/or template files was detected in your Magento directory:');
                $this->setError($this->foundCopies, '&nbsp;&nbsp;&nbsp;-&nbsp;');
                $this->setError('Make sure to update these files from \''.$this->design.'/default\' design if needed!');
            }

            if($this->moduleDisabled) {
                @unlink($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml.backup');
            }

            return true;
        }

        function restoreStep_installFiles() {

            if($this->moduleDisabled) {
                @unlink($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml.backup');
            } else {
                @unlink($this->dir . '/app/etc/modules/MagicToolbox_MagicZoomPlus.xml');
            }

            $this->removeDir($this->dir . '/app/code/local/MagicToolbox/MagicZoomPlus');
            if($this->is_empty_dir($this->dir . '/app/code/local/MagicToolbox')) {
                $this->removeDir($this->dir . '/app/code/local/MagicToolbox');
                $removeAll = true;
            } else $removeAll = false;

            unlink($this->dir . '/app/design/adminhtml/default/default/layout/magiczoomplus.xml');
            $this->removeDir($this->dir . '/app/design/adminhtml/default/default/template/magiczoomplus');

            unlink($this->dir . '/app/design/frontend/'.$this->design.'/default/layout/magiczoomplus.xml');
            $this->removeDir($this->dir . '/app/design/frontend/'.$this->design.'/default/template/magiczoomplus');
            unlink($this->dir . '/skin/frontend/'.$this->design.'/default/css/magiczoomplus.css');
            unlink($this->dir . '/skin/frontend/'.$this->design.'/default/js/magiczoomplus.js');


            $this->removeDir($this->dir . '/js/magiczoomplus');
            $this->removeDir($this->dir . '/skin/adminhtml/default/default/magiczoomplus');
            if($removeAll) {
                $this->removeDir($this->dir . '/skin/frontend/'.$this->design.'/default/css/graphics');


                unlink($this->dir . '/skin/frontend/'.$this->design.'/default/js/magictoolbox_utils.js');
                unlink($this->dir . '/skin/frontend/'.$this->design.'/default/js/magicscroll.js');
                unlink($this->dir . '/skin/frontend/'.$this->design.'/default/css/magicscroll.css');

                if(file_exists($this->dir . '/skin/frontend/'.$this->design.'/default/images/loader.gif'))
                    unlink($this->dir . '/skin/frontend/'.$this->design.'/default/images/loader.gif');
            }
            unlink($this->dir . '/skin/frontend/'.$this->design.'/default/images/ajax-loader.gif');


            return true;
        }

        function is_empty_dir($dir) {
            if($dh = @opendir($dir)) {
                while($file = readdir($dh)) {
                    if($file != '.' && $file != '..') {
                        closedir($dh);
                        return false;
                    }
                }
                closedir($dh);
                return true;
            }
            else return false; //no such dir, not a dir, not readable
        }

        function upgrade($files) {
            $path = $this->dir . '/skin/frontend/'.$this->design.'/default/js/';
            foreach($files as $name => $file) {
                if(file_exists($path . $name)) {
                    unlink($path . $name);
                }
                file_put_contents($path . $name, $file);
                chmod($path . $name, 0755);
            }
            return true;
        }

    }

?>