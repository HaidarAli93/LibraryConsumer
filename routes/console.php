<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('api:fetch')
	->dailyAt('00:00')
	->withoutOverlapping();
