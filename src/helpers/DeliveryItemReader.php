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


use oat\qtiResultReporting\model\DeliveryReader\DeliveryReader;
use oat\qtiResultReporting\model\DeliveryReader\ItemReader;
use oat\qtiResultReporting\model\DeliveryReader\TestPartReader;
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
     * List of the QtiItemParsers
     * @see \oat\qtiResultReporting\model\qtiItemParser\QtiItemParser
     * @var array
     */
    private static $parsers = [];

    public static function getQtiItemParsers(\core_kernel_classes_Resource $delivery)
    {
        if (!isset(self::$parsers[$delivery->getUri()])) {

            self::$parsers[$delivery->getUri()] = [];
            $deliveryReader = new DeliveryReader($delivery);

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
}
