<?php

namespace App\Traits;

use Carbon\Carbon;

trait CalculateNextRunDate
{
    /**
     * @param    $unit, $period, $date
     * @return string
     */
    public function calculateNextRunDate($unit, $period, $date)
    {
        $runDate = new Carbon($date);
        switch ($unit) {
            case 'DAY':
                $runDate->addDays($period);
                break;
            case 'MONTH':
                $runDate->addMonths($period);
                break;
            case 'YEAR':
                $runDate->addYears($period);
                break;
        }

        return $runDate;
    }
}
