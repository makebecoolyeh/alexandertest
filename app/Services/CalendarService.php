<?php

namespace App\Services;

use DateTime;

class CalendarService {

    private static $holidays = [
        '1st of January' => [
            'startDay' => 1,
            'endDay' => 2,
            'dayOfTheWeek' => null,
            'numberOfWeekInTheMonth' => null,
            'numberOfTheMonth' => 1,
            'description' => 'New Year'
        ],
        '7th of January' => [
            'startDay' => 7,
            'endDay' => 8,
            'dayOfTheWeek' => null,
            'numberOfWeekInTheMonth' => null,
            'numberOfTheMonth' => 1,
            'description' => 'Christmas'
        ],
        'From 1st of May till 7th of May' => [
            'startDay' => 1,
            'endDay' => 8,
            'dayOfTheWeek' => null,
            'numberOfWeekInTheMonth' => null,
            'numberOfTheMonth' => 5,
            'description' => 'Official week off in May'
        ],
        'Monday of the 3rd week of January' => [
            'startDay' => null,
            'endDay' => null,
            'dayOfTheWeek' => 1,
            'numberOfWeekInTheMonth' => 3,
            'numberOfTheMonth' => 1,
            'description' => 'Celebrate this strange holiday (Monday of the 3rd week of January)'
        ],
        'Monday of the last week of March' => [
            'startDay' => null,
            'endDay' => null,
            'dayOfTheWeek' => 1,
            'numberOfWeekInTheMonth' => 10, //last week
            'numberOfTheMonth' => 3,
            'description' => 'Celebrate this strange holiday (Monday of the last week of March)'
        ],
        'Thursday of the 4th week of November' => [
            'startDay' => null,
            'endDay' => null,
            'dayOfTheWeek' => 4,
            'numberOfWeekInTheMonth' => 4,
            'numberOfTheMonth' => 11,
            'description' => 'Celebrate this strange holiday (Thursday of the 4th week of November)'
        ],
    ];

    /**
     * @return array
     */
    public static function getHolidays(): array
    {
        return self::$holidays;
    }

    private static function getDayOfTheWeek($strDate) {
        $timestamp = strtotime($strDate);
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        $retVal = $date->format('N');
        return (int)$retVal;
    }

    private static function getNumberOfWeekInTheMonth($strDate) {
        $dateArr = date_parse($strDate);
        if(checkdate($dateArr['month'], $dateArr['day'], $dateArr['year'])) {
            $firstDayOfTheMonth = implode('-0', [$dateArr['year'], $dateArr['month'], 1]);
            return (int)(($dateArr['day'] + self::getDayOfTheWeek($firstDayOfTheMonth) - 2) / 7 + 1);
        }
        return false;
    }

    private static function isWeekLast($strDate) {
        $dateArr = date_parse($strDate);
        if(checkdate($dateArr['month'], $dateArr['day'], $dateArr['year'])) {
            $date = new DateTime('last day of '.$strDate);
            $lastDay = $date->format('d-m-Y');
            if (self::getNumberOfWeekInTheMonth($strDate) == self::getNumberOfWeekInTheMonth($lastDay)) {
                return true;
            }
        }
        return false;
    }

    public static function isHoliday($strDate) {
        $dateArr = date_parse($strDate);
        if(checkdate($dateArr['month'], $dateArr['day'], $dateArr['year'])) {
            foreach(self::$holidays as $holiday) {
                if($holiday['numberOfTheMonth'] != null && $holiday['numberOfTheMonth'] == $dateArr['month']) {
                    if ($holiday['startDay'] != null && $holiday['endDay'] != null) {
                        if ($dateArr['day'] >= $holiday['startDay'] && $dateArr['day'] < $holiday['endDay']) {
                            return $holiday['description'];
                        }
                    }
                    else if ($holiday['dayOfTheWeek'] != null && $holiday['numberOfWeekInTheMonth'] != null) {
                        if($holiday['dayOfTheWeek'] == self::getDayOfTheWeek($strDate)) {
                            if($holiday['numberOfWeekInTheMonth'] == $holiday['numberOfWeekInTheMonth']) {
                                return $holiday['description'];
                            }
                            else if(self::isWeekLast($strDate) && $holiday['numberOfWeekInTheMonth'] == 10) {
                                return $holiday['description'];
                            }
                        }
                    }
                }
            }
            if(self::getDayOfTheWeek($strDate) == 1) { //if it is Monday
                if(self::isHoliday('-1 day '.$strDate) || self::isHoliday('-2 day '.$strDate)) {
                    return 'This Monday is day off after holiday in weekend.';
                }
            }
        }
        return false;
    }
};

