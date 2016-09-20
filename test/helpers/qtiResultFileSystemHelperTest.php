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

namespace oat\qtiResultReporting\test\helpers;


use oat\oatbox\filesystem\File;
use oat\qtiResultReporting\helpers\qtiResultFileSystemHelper;
use oat\tao\test\TaoPhpUnitTestRunner;
use oat\taoQtiItem\helpers\QtiFile;
use oat\taoQtiItem\model\QtiJsonItemCompiler;
use Prophecy\Argument;
use tao_models_classes_service_StorageDirectory;


include_once __DIR__ . '/../samples/generis.conf.php';
\common_ext_ExtensionsManager::singleton()->getExtensionById('tao')->load();

class qtiResultFileSystemHelperTest extends TaoPhpUnitTestRunner
{
    /**
     * @var tao_models_classes_service_StorageDirectory
     */
    private $dir;

    private function getSamplesPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR;
    }

    public function _prepare(array $shouldBeCalledTimes = [])
    {
        $fileJson = $this->prophesize(File::class);
        $fileJson->exists()
            ->shouldBeCalledTimes($shouldBeCalledTimes['fileJson->exists'])
            ->willReturn($shouldBeCalledTimes['fileJson->willReturn']);

        $fileJson->read()
            ->shouldBeCalledTimes($shouldBeCalledTimes['fileJson->read'])
            ->willReturn($shouldBeCalledTimes['fileJson->read->willReturn']);

        $fileRdf = $this->prophesize(File::class);
        $fileRdf->exists()
            ->shouldBeCalledTimes($shouldBeCalledTimes['fileRdf->exists'])
            ->willReturn($shouldBeCalledTimes['fileRdf->willReturn']);

        $fileRdf->read()
            ->shouldBeCalledTimes($shouldBeCalledTimes['fileRdf->read'])
            ->willReturn($shouldBeCalledTimes['fileRdf->read->willReturn']);

        $this->dir = $this->prophesize(tao_models_classes_service_StorageDirectory::class);
        $this->dir->getFile(Argument::type('string'))
            ->will(function($type) use ($fileJson, $fileRdf) {
                if ($type[0] == QtiJsonItemCompiler::ITEM_FILE_NAME) {
                    return $fileJson->reveal();
                }

                if ($type[0] == QtiFile::FILE) {
                    return $fileRdf->reveal();
                }
            });

        $this->dir->getRelPath(Argument::any())->willReturn('test exception');
    }

    public function testReadQtiItemJson()
    {
        $this->_prepare([
            'fileJson->exists' => 1,
            'fileJson->willReturn' => true,
            'fileJson->read' => 1,
            'fileJson->read->willReturn' => file_get_contents($this->getSamplesPath() . 'json' . DIRECTORY_SEPARATOR . QtiJsonItemCompiler::ITEM_FILE_NAME),
            'fileRdf->exists' => 0,
            'fileRdf->willReturn' => '',
            'fileRdf->read' => 0,
            'fileRdf->read->willReturn' => ''
        ]);

        $result = qtiResultFileSystemHelper::readQtiItem($this->dir->reveal());
        $this->assertEquals('qti', $result->type);
    }

    /**
     * can't be checked without installation of the tao
     */
    public function testReadQtiItemRdf()
    {
        $this->_prepare([
            'fileJson->exists' => 1,
            'fileJson->willReturn' => false,
            'fileJson->read' => 0,
            'fileJson->read->willReturn' => '',

            'fileRdf->exists' => 1,
            'fileRdf->willReturn' => true,
            'fileRdf->read' => 1,
            'fileRdf->read->willReturn' => file_get_contents($this->getSamplesPath() . 'xml' . DIRECTORY_SEPARATOR . QtiFile::FILE),
        ]);

        try {
            $result = qtiResultFileSystemHelper::readQtiItem($this->dir->reveal());
        } catch (\Exception $e) {
            $this->assertEquals('Portable element model "PCI" not found. Required extension might be missing', $e->getMessage());
        }
    }

    public function testReadQtiItemIncorrect()
    {
        $this->_prepare([
            'fileJson->exists' => 1,
            'fileJson->willReturn' => false,
            'fileJson->read' => 0,
            'fileJson->read->willReturn' => '',

            'fileRdf->exists' => 1,
            'fileRdf->willReturn' => false,
            'fileRdf->read' => 0,
            'fileRdf->read->willReturn' => '',
        ]);

        try {
            qtiResultFileSystemHelper::readQtiItem($this->dir->reveal());
        } catch (\Exception $e) {
            $this->assertEquals('File "test exception" can not be found', $e->getMessage());
        }
    }
}
