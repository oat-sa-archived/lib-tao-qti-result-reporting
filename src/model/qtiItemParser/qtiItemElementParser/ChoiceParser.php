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

namespace oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser;

/**
 * choiceInteraction
 *
 * Class ChoiceParser
 * @package oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser
 */
class ChoiceParser extends AbstractElementParser
{

    public function parseResponse($response = '')
    {
        $rows = [];
        $res = [];

        $response = trim($response, '[]');
        if (!empty($response)) {
            if (strpos($response, ';')) {
                foreach (explode(';', $response) as $item) {
                    $rows[] = trim($item, ' \'');
                }
            } else {
                $rows[] = trim($response, ' \'');
            }

            if (count($rows)) {
                foreach ($this->getElementsIds() as $elementsId) {
                    $res[$elementsId] = in_array($elementsId, $rows) ? 1 : 0;
                }
            }
        }

        return $res;
    }

    public function getElementsIds()
    {
        $ids = [];
        foreach ($this->getElements() as $element) {
            $ids[] = $element->identifier;
        }

        return $ids;
    }

    public function getElements()
    {
        return isset($this->element->choices) ? $this->element->choices : [];
    }
}