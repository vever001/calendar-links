<?php

namespace Spatie\CalendarLinks\Generators;

use Spatie\CalendarLinks\Generator;
use Spatie\CalendarLinks\Link;

/**
 * @see https://github.com/InteractionDesignFoundation/add-event-to-calendar-docs/blob/master/services/yahoo.md
 * @psalm-type YahooUrlParameters = array<string, scalar|null>
 */
class Yahoo implements Generator
{
    /** @var string {@see https://www.php.net/manual/en/function.date.php} */
    protected $dateFormat = 'Ymd';

    /** @var string */
    protected $dateTimeFormat = 'Ymd\THis\Z';

    /** @psalm-var YahooUrlParameters */
    protected array $urlParameters = [];

    /** @psalm-param YahooUrlParameters $urlParameters */
    public function __construct(array $urlParameters = [])
    {
        $this->urlParameters = $urlParameters;
    }

    /** {@inheritDoc} */
    public function generate(Link $link): string
    {
        $url = 'https://calendar.yahoo.com/';
        $query = [];
        $query['v'] = '60';
        $query['view'] = 'd';
        $query['type'] = 20;

        if ($link->allDay) {
            $query['st'] = $link->from->format($this->dateFormat);
            $query['et'] = $link->to->format($this->dateFormat);
            $query['dur'] = 'allday';
        } else {
            $utcStartDateTime = (clone $link->from)->setTimezone(new \DateTimeZone('UTC'));
            $utcEndDateTime = (clone $link->to)->setTimezone(new \DateTimeZone('UTC'));
            $query['st'] = $utcStartDateTime->format($this->dateTimeFormat);
            $query['et'] = $utcEndDateTime->format($this->dateTimeFormat);
        }

        $query['title'] = $link->title;
        $query['desc'] = $link->description ?? NULL;
        $query['in_loc'] = $link->address ?? NULL;
        $query = [...$query, ...$this->urlParameters];
        return $url . '?' . http_build_query($query);
    }

}
