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

namespace oat\qtiResultReporting\DeliveryReader;


use oat\qtiResultReporting\DeliveryReaderInterface;
use qtism\data\QtiComponentCollection;
use qtism\data\TestPart;

class TestPartReader implements DeliveryReaderInterface
{

    /**
     * @var TestPart
     */
    private $testPart;

    /**
     * @var QtiComponentCollection
     */
    private $items;

    private static $itemReaders = [];

    public function __construct($testPart)
    {
        $this->testPart = $testPart;
    }

    public function init()
    {
        $items = $this->getItems();

        foreach ($items as $item) {
            if(!isset(self::$itemReaders[$item->getHref()])) {
                self::$itemReaders[$item->getHref()] = new ItemReader($item);
                self::$itemReaders[$item->getHref()]->init();
            }
        }
    }

    /**
     * @return QtiComponentCollection
     */
    private function getItems()
    {
        if ($this->items === null) {
            $this->items = $this->testPart->getComponentsByClassName('assessmentItemRef');
        }
        return $this->items;
    }
}