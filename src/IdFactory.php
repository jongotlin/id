<?php
/**
 * This file is part of ledgr/id.
 *
 * Copyright (c) 2014 Hannes Forsgård
 *
 * ledgr/id is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ledgr/id is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ledgr/id.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ledgr\id;

use ledgr\id\Exception\UnableToCreateIdException;

/**
 * Create ID object from raw id string
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class IdFactory
{
    /**
     * Create ID object from raw id string
     *
     * @param  string $rawId             Raw id string
     * @return void                      never returns
     * @throws UnableToCreateIdException Always throws exception
     */
    public function create($rawId)
    {
        throw new UnableToCreateIdException("Unable to create ID for number '{$rawId}'");
    }
}
