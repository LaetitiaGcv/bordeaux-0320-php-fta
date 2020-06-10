<?php

namespace App\Service;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Iterable_;

/**
 * Class CalendarService
 * @package App\Service
 */
class CalendarService
{
    /**
     * @param iterable $availabilityUsers
     * @return array
     */
    public static function transformToJson(iterable $availabilityUsers): array
    {
        $table = [];
        $increment = 0;
        foreach ($availabilityUsers as $user) {
            $table[$increment]['date'] = $user->getDate()->format('d/m/Y');
            $table[$increment]['isMorning'] = $user->getIsMorning();
            $table[$increment]['isAfternoon'] = $user->getIsAfternoon();
            $increment++;
        }
        return $table;
    }
}
