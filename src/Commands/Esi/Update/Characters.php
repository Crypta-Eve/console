<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Console\Commands\Esi\Update;

use Illuminate\Console\Command;
use Seat\Console\Bus\Character;
use Seat\Eveapi\Models\RefreshToken;

/**
 * Class Characters.
 *
 * @package Seat\Console\Commands\Esi\Update
 * @deprecated since 4.7.0 - this will be moved into eveapi package in a near future
 */
class Characters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esi:update:characters {character_id : ID from character to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule updater jobs for character';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $token = RefreshToken::find($this->argument('character_id'));

        if (! $token) {
            $this->error('The provided ID is invalid or not registered in SeAT.');

            return;
        }

        // Fire the class that handles the collection of jobs to run.
        (new Character($token))->fire();

        $this->info(sprintf('Processing character update %d - %s',
            $token->character_id, $token->character->name ?? trans('web::seat.unknown')));
    }
}
