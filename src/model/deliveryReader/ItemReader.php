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

namespace oat\qtiResultReporting\model\deliveryReader;


use oat\qtiResultReporting\helpers\qtiResultFileSystemHelper;
use oat\qtiResultReporting\model\ReaderInterface;
use qtism\data\AssessmentItemRef;

/**
 * Read compiled Item from the compiled delivery
 *
 * Class ItemReader
 * @package oat\qtiResultReporting\DeliveryReader
 */
class ItemReader implements ReaderInterface
{
    /**
     * @var AssessmentItemRef
     */
    private $item;

    /**
     * @var \stdClass
     */
    private $qtiItem;

    /**
     * @var \stdClass
     */
    private $qtiVariables;

    /**
     * @var \tao_models_classes_service_StorageDirectory
     */
    private $dataDirectory;

    public function __construct(AssessmentItemRef $item)
    {
        $this->item = $item;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getQtiItem()
    {
        if (!isset($this->qtiItem)) {
            $itemDataDirectory = $this->getItemDataDirectory($this->item->getHref());
            $this->qtiItem = qtiResultFileSystemHelper::readQtiItem($itemDataDirectory);
        }
        return $this->qtiItem;
    }

    public function getQtiVariableElements()
    {
        if (!isset($this->qtiVariables)) {
            $itemDataDirectory = $this->getItemDataDirectory($this->item->getHref());
            $this->qtiVariables = qtiResultFileSystemHelper::readQtiVariables($itemDataDirectory);
        }
        return $this->qtiVariables;
    }

    private function getItemDataDirectory($itemRef)
    {

        if (!isset($this->dataDirectory)) {
            $this->dataDirectory = qtiResultFileSystemHelper::getItemDataDirectory($itemRef);
        }

        return $this->dataDirectory;
    }
}
