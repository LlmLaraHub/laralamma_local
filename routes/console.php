<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:run-llm-pulls-command')->everySecond();
