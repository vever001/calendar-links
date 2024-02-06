<?php

namespace Spatie\CalendarLinks\Generators;

use Spatie\CalendarLinks\Generator;
use Spatie\CalendarLinks\Link;

/**
 * @see https://github.com/InteractionDesignFoundation/add-event-to-calendar-docs/blob/master/services/google.md
 * @psalm-type GoogleUrlParameters = array<string, scalar|null>
 */
class Google implements Generator
{
    /** @var string {@see https://www.php.net/manual/en/function.date.php} */
    protected $dateFormat = 'Ymd';
    /** @var string */
    protected $dateTimeFormat = 'Ymd\THis';

    /** @psalm-var GoogleUrlParameters */
    protected array $urlParameters = [];

    /** @psalm-param GoogleUrlParameters $urlParameters */
    public function __construct(array $urlParameters = [])
    {
        $this->urlParameters = $urlParameters;
    }

    /** {@inheritDoc} */
    public function generate(Link $link): string
    {
        $url = 'https://calendar.google.com/calendar/render';
        $dateTimeFormat = $link->allDay ? $this->dateFormat : $this->dateTimeFormat;
        $query = [
            'action' => 'TEMPLATE',
            'dates' => "{$link->from->format($dateTimeFormat)}/{$link->to->format($dateTimeFormat)}",
            'ctz' => $link->from->getTimezone()->getName(),
            'text' => $link->title,
            'details' => $link->description ?? NULL,
            'location' => $link->address ?? NULL,
        ];

        $query = [...$query, ...$this->urlParameters];
        return $url . '?' . http_build_query($query);
    }
}
