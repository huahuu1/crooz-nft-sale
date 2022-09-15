<?php

namespace App\Traits;

use Carbon\Carbon;

trait CalculateNextRunDate
{
    /**
     * @param    $unit, $period, $date
     * @return $runDate
     */
    public function calculateNextRunDate($unit, $period, $date)
    {
        $runDate = new Carbon($date);
        switch ($unit) {
            case 'DAY':
                return $runDate->addDays($period);
            case 'MONTH':
                return $runDate->addMonths($period);
            case 'YEAR':
                return $runDate->addYears($period);
        }
    }
}
