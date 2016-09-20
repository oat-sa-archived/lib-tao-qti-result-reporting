<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2016  (original work) Open Assessment Technologies SA;
 *
 * @author Alexander Zagovorichev <zagovorichev@1pt.com>
 */

namespace oat\qtiResultReporting\helpers;


use oat\oatbox\filesystem\Directory;
use oat\taoQtiItem\helpers\QtiFile;
use oat\taoQtiItem\model\qti\ParserFactory;
use oat\taoQtiItem\model\QtiJsonItemCompiler;

class qtiResultFileSystemHelper
{
    /**
     * Looking for private folder with compiled deliveries (data/tao/private/...)
     *
     * @param string $itemRef
     * @return Directory $path
     * @throws \common_Exception
     * @throws \common_exception_InvalidArgumentType
     */
    public static function getItemDataDirectory($itemRef = '')
    {
        $directoryIds = explode('|', $itemRef);
        if (count($directoryIds) < 3) {
            throw new \common_exception_InvalidArgumentType('The itemRef is not formatted correctly');
        }

        $itemUri = $directoryIds[0];
        $directory = \tao_models_classes_service_FileStorage::singleton()->getDirectoryById($directoryIds[2]);

        if (!$directory->exists()) {
            throw new \common_Exception(
                'item : ' . $itemUri . ' is not compiled'
            );
        }

        $userDataLang = \common_session_SessionManager::getSession()->getDataLanguage();
        $langDir = $directory->getDirectory($userDataLang);

        if (!$langDir->exists()) {
            $langDir = $directory->getDirectory(DEFAULT_LANG);
            if (!$langDir->exists()) {
                throw new \common_Exception(
                    'item : ' . $itemUri . ' is neither compiled in ' . $userDataLang . ' nor in ' . DEFAULT_LANG
                );
            }
        }

        return $langDir;
    }

    public static function readQtiItem(Directory $dir)
    {
        // new TestRunner
        $file = $dir->getFile(QtiJsonItemCompiler::ITEM_FILE_NAME);
        if ($file->exists()) {
            $data = $file->read();
            $data = json_decode($data);
        } else {
            // old TestRunner
            $file = $dir->getFile(QtiFile::FILE);
            if (!$file->exists()) {
                throw new \common_Exception('File "' . $dir->getRelPath($file) . '" can not be found');
            }
            $data = $file->read();
            $xml = new \DOMDocument();
            $xml->loadXML($data);
            $parser = new ParserFactory($xml);
            $data = $parser->load();
        }

        return $data;
    }

    public static function readQtiVariables(Directory $dir)
    {
        $vars = [];

        // new TestRunner
        $file = $dir->getFile(QtiJsonItemCompiler::VAR_ELT_FILE_NAME);
        if ($file->exists()) {
            $vars = $file->read();
        }

        return $vars;
    }
}
