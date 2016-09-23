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


use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\AbstractElementParser;
use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\ChoiceParser;
use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\DefaultElementParser;
use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\GapMatchParser;
use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\HottextParser;
use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\InlineChoiceParser;
use oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser\MatchParser;


/**
 * Parser for the qtiItem elements (each of the $qti->body->elements)
 *
 * Class QtiItemElementReader
 * @package oat\qtiResultReporting\model\qtiItemParser
 */
class QtiItemElementParser extends AbstractElementParser
{
    /**
     * @var AbstractElementParser
     */
    private $reader;

    public function getResponseIdentifier()
    {
        return isset($this->element->attributes->responseIdentifier) ? $this->element->attributes->responseIdentifier : '';
    }

    public function getReader()
    {
        if (!isset($this->reader)) {

            $parser = DefaultElementParser::class;
            switch ($this->element->qtiClass) {
                case 'gapMatchInteraction':
                    $parser = GapMatchParser::class;
                    break;
                case 'matchInteraction':
                    $parser = MatchParser::class;
                    break;
                case 'choiceInteraction':
                    $parser = ChoiceParser::class;
                    break;
                case 'hottextInteraction':
                    $parser = HottextParser::class;
                    break;
                case 'inlineChoiceInteraction':
                    $parser = InlineChoiceParser::class;
                    break;
                // ignore elements
                case 'img':
                    break;
                default:
                    \common_Logger::w('Can not parse qtiItem element with qtiClass "'.$this->element->qtiClass.'"');
            }

            $this->reader = new $parser($this->element);
        }
        return $this->reader;
    }

    public function getElements()
    {
        return $this->reader->getElements();
    }

    public function getElementsIds()
    {
        return $this->getReader()->getElementsIds();
    }

    /**
     * @param string $response
     * @return array ['title' => '', 'value' => '']
     */
    public function parseResponse($response = '')
    {
        $parsed = [];
        if ($this->getReader()){
            $parsed = $this->getReader()->parseResponse($response);
        }
        return $parsed;
    }
}
