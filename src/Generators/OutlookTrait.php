<?php

namespace Spatie\CalendarLinks\Generators;

use Spatie\CalendarLinks\Link;

trait OutlookTrait
{
    /** @var string {@see https://www.php.net/manual/en/function.date.php} */
    protected $dateFormat = 'Y-m-d';

    /** @var string {@see https://www.php.net/manual/en/function.date.php} */
    protected $dateTimeFormat = 'Y-m-d\TH:i:s\Z';
    
    /**
     * Get Outlook parameters from link.
     *
     * @param \Spatie\CalendarLinks\Link $link
     * @return array
     */
    private function getOutlookParams(Link $link): array
    {
        $query = [];
        $query['path'] = '/calendar/action/compose';
        $query['rru'] = 'addevent';

        if ($link->allDay) {
            $query['startdt'] = $link->from->format($this->dateFormat);
            $query['enddt'] = $link->to->format($this->dateFormat);
            $query['allday'] = 'true';
        }
        else {
            $query['startdt'] = (clone $link->from)->setTimezone(new \DateTimeZone('UTC'))->format($this->dateTimeFormat);
            $query['enddt'] = (clone $link->to)->setTimezone(new \DateTimeZone('UTC'))->format($this->dateTimeFormat);
        }

        $query['subject'] = $link->title;
        $query['body'] = $link->description ?? NULL;
        $query['location'] = $link->address ?? NULL;
        return $query;
    }
}
