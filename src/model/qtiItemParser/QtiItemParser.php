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
 * @package oat\qtiResultReporting\model\qtiItemParser
 */
class QtiItemParser implements ParserInterface
{
    /**
     * @var \stdClass with itemQti
     */
    private $qti;

    /**
     * @var array
     */
    private $elementsParsers;

    /**
     * @var array
     */
    private $responseIdentifiers;

    public function __construct($qti)
    {
        $this->qti = $qti;
    }

    /**
     * Get parsers for each element of the item
     * @return array
     */
    public function getElementParsers()
    {
        if (!isset($this->elementsParsers)) {
            $this->elementsParsers = [];
            foreach ($this->qti->data->body->elements as $key => $element) {
                $this->elementsParsers[$key] = new QtiItemElementParser($element);
            }
        }

        return $this->elementsParsers;
    }

    /**
     * Response identifier can be changed from GUI
     * Collect all of the possible identifiers
     *
     * @return array
     */
    public function getResponseIdentifiers()
    {
        if (!isset($this->responseIdentifiers)) {
            $this->responseIdentifiers = [];
            foreach ($this->getElementParsers() as $elementParser) {
                if ($elementParser->getResponseIdentifier()
                    && !in_array($elementParser->getResponseIdentifier(), $this->responseIdentifiers)
                ) {
                    $this->responseIdentifiers[] = $elementParser->getResponseIdentifier();
                }
            }
        }
        return $this->responseIdentifiers;
    }
}
