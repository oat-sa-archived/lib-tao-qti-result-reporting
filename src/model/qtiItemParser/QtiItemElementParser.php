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
use oat\qtiResultReporting\model\qtiItemElementParser\DefaultElementParser;


/**
 * Parser for the qtiItem elements (each of the $qti->body->elements)
 *
 * Class QtiItemElementReader
 * @package oat\qtiResultReporting\src\model\qtiItemParser
 */
class QtiItemElementParser implements ParserInterface
{
    private $element;

    private $reader;

    public function __construct($element)
    {
        $this->element = $element;

        $this->detectReader();
    }

    private function detectReader()
    {
        $reader = new DefaultElementParser();
        /*
         * todo for the elements which can't be read by the default parser
         *
        switch ($this->element->qtiClass) {
            case '': break;
        }*/

        $this->reader = $reader;
    }

    public function parse()
    {

    }
}
