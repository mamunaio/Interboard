<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

// Schedule the automated monthly billing generator to run daily in the background
Schedule::command('app:generate-monthly-invoices')->daily();

// Schedule the automated billing suspension checker to run daily in the background
Schedule::command('app:suspend-overdue-customers')->daily();
