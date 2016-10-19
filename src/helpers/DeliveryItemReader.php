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


use oat\qtiResultReporting\model\deliveryReader\DeliveryReader;
use oat\qtiResultReporting\model\deliveryReader\ItemReader;
use oat\qtiResultReporting\model\deliveryReader\TestPartReader;
use oat\qtiResultReporting\model\qtiItemParser\QtiItemParser;

/**
 * Helper for the reading of the compiled delivery items
 *
 * Class DeliveryItemReader
 * @package oat\qtiResultReporting\helpers
 */
class DeliveryItemReader
{

    /**
     * Loaded readers
     * @var array
     */
    private static $deliveryReaders = [];

    /**
     * List of the QtiItemParsers
     * @see \oat\qtiResultReporting\model\qtiItemParser\QtiItemParser
     * @var array
     */
    private static $parsers = [];

    private static $branchRules = [];

    /**
     * Load qtiItems from the delivery
     * @param \core_kernel_classes_Resource $delivery
     * @return mixed
     */
    public static function getQtiItemParsers(\core_kernel_classes_Resource $delivery)
    {
        if (!isset(self::$parsers[$delivery->getUri()])) {

            self::$parsers[$delivery->getUri()] = [];
            $deliveryReader = self::getDeliveryReader($delivery);

            /** @var TestPartReader $testPartReader */
            foreach ($deliveryReader->getTestReader()->getTestPartReaders() as $testPartReader) {
                /**
                 * @var string $href
                 * @var ItemReader $itemReader
                 */
                foreach ($testPartReader->getItemReaders() as $href => $itemReader) {

                    $qti = $itemReader->getQtiItem();
                    $qtiItemParser = new QtiItemParser($qti);

                    $uriItem = explode('|', $href)[0];
                    self::$parsers[$delivery->getUri()][$uriItem] = $qtiItemParser;
                }
            }
        }

        return self::$parsers[$delivery->getUri()];
    }

    /**
     * Load branch rules from the items (which in the delivery)
     *
     * @param \core_kernel_classes_Resource $delivery
     * @return array
     */
    public static function getItemsBranchRules(\core_kernel_classes_Resource $delivery)
    {
        if (!isset(self::$branchRules[$delivery->getUri()])) {

            self::$parsers[$delivery->getUri()] = [];
            $deliveryReader = self::getDeliveryReader($delivery);

            /** @var TestPartReader $testPartReader */
            foreach ($deliveryReader->getTestReader()->getTestPartReaders() as $testPartReader) {
                /**
                 * @var string $href
                 * @var ItemReader $itemReader
                 */
                foreach ($testPartReader->getItemReaders() as $href => $itemReader) {

                    $uriItem = explode('|', $href)[0];
                    self::$branchRules[$delivery->getUri()][$uriItem]['rules'] = $itemReader->getItem()->getBranchRules()->getArrayCopy();
                    self::$branchRules[$delivery->getUri()][$uriItem]['id'] = $itemReader->getItem()->getIdentifier();
                }
            }
        }

        return self::$branchRules[$delivery->getUri()];
    }

    private static function getDeliveryReader(\core_kernel_classes_Resource $delivery)
    {
        if (!isset(self::$deliveryReaders[$delivery->getUri()])){
            self::$deliveryReaders[$delivery->getUri()] = new DeliveryReader($delivery);
        }

        return self::$deliveryReaders[$delivery->getUri()];
    }
}
