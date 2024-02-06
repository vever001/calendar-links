<?php

namespace Spatie\CalendarLinks\Generators;

use Spatie\CalendarLinks\Generator;
use Spatie\CalendarLinks\Link;

class WebOffice implements Generator
{
    use OutlookTrait;

    /** @psalm-var OutlookUrlParameters */
    protected array $urlParameters = [];

    /** @psalm-param OutlookUrlParameters $urlParameters */
    public function __construct(array $urlParameters = [])
    {
        $this->urlParameters = $urlParameters;
    }

    /** {@inheritDoc} */
    public function generate(Link $link): string
    {
        $url = 'https://outlook.office.com/calendar/deeplink/compose';
        $query = $this->getOutlookParams($link);
        $query = [...$query, ...$this->urlParameters];
        return $url . '?' . http_build_query($query);
    }
}
