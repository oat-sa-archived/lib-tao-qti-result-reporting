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


use core_kernel_classes_Resource;
use oat\qtiResultReporting\model\ReaderInterface;

/**
 * Reader of the compiled delivery
 *
 * Class DeliveryReader
 * @package oat\qtiResultReporting\deliveryReader
 */
class DeliveryReader implements ReaderInterface
{

    /** @var core_kernel_classes_Resource  */
    private $delivery;

    /** @var  TestReader */
    private $testReader;

    public function __construct(core_kernel_classes_Resource $compiledDelivery)
    {
        $this->delivery = $compiledDelivery;
    }

    public function init()
    {
        $this->testReader = new TestReader($this->delivery);
        $this->testReader->init();

        return $this;
    }

    /**
     * @return TestReader
     */
    public function getTestReader()
    {
        return $this->testReader;
    }

}
