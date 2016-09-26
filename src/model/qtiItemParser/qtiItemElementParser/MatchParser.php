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
 * matchInteraction
 *
 * Class MatchParser
 * @package oat\qtiResultReporting\model\qtiItemParser\qtiItemElementParser
 */
class MatchParser extends AbstractElementParser
{
    public function parseResponse($response = '')
    {
        $elIds = $this->getElementsIds();

        $rows = [];
        $res = [];

        $response = trim($response, '[]');
        if (!empty($response)) {
            foreach (explode(';', $response) as $answer) {
                $answer = explode(' ', trim($answer));
                if (count($answer) == 2 && in_array($answer[1], $elIds)) {
                    $rows[$answer[1]] = (isset($rows[$answer[1]]) ? $rows[$answer[1]] . ' ' : '') . $answer[0];
                } else {
                    \common_Logger::w('Incorrect response for MatchParser: ' . $response);
                }
            }

            if (count($rows)) {
                foreach ($elIds as $elementsId) {
                    $res[$elementsId] = isset($rows[$elementsId]) ? $rows[$elementsId] : 0;
                }
            }
        }

        return $res;
    }

    /**
     * @return array [0 - columns, 1 - rows]
     */
    public function getElements()
    {
        return isset($this->element->choices) ? $this->element->choices : [];
    }

    public function getElementsIds()
    {
        $ids = [];
        foreach ($this->getElements()[1] as $element) {
            $ids[] = $element->identifier;
        }

        return $ids;
    }
}