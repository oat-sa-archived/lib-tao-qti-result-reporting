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

namespace oat\qtiResultReporting\model\qtiItemParser;


use oat\qtiResultReporting\model\ParserInterface;

/**
 * Parser for the compiled qtiItem (qtiItem could be get from the ItemReader)
 *
 * Class QtiItemParser
 * @package oat\qtiResultReporting\src\model\qtiItemParser
 */
class QtiItemParser implements ParserInterface
{
    /**
     * @var \stdClass with itemQti
     */
    private $qti;

    /**
     * @var
     */
    private static $elementParser;

    public function __construct($qti)
    {
        $this->qti = $qti;
    }

    public function parse()
    {
        // TODO: Implement parse() method.
    }

    /**
     * Get elements parser
     *
     * @param $element
     * @return QtiItemElementParser
     */
    public function getElementParser($element)
    {
        if (!isset(self::$elementParser)) {
            self::$elementParser = new QtiItemElementParser($element);
        }

        return self::$elementParser;
    }

    /**
     * @return mixed
     */
    public function getElements()
    {
        return $this->elements;
    }

    public function parseResponse($element, $responseAnswer)
    {
        $parser = $this->getElementParser($element);
        return $parser->attachResponse($responseAnswer);
    }
}
