<?php

namespace App\Modules\Admin\Holiday\Services;

use App\Models\Holiday;
use App\Services\BaseService;
use DOMDocument;
use DOMXPath;

class HolidayService extends BaseService
{
    public function __construct(Holiday $holiday)
    {
        $this->model = $holiday;
    }

    /**
     * Parse website resource holidays in storage.
     *
     * @param string $year
     * @return bool
     */
    public function parse(string $year): bool
    {
        Holiday::query()->truncate();

        $page = file_get_contents('https://www.consultant.ru/law/ref/calendar/proizvodstvennye/' . $year);

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($page);
        libxml_use_internal_errors(false);

        $xpath = new DOMXPath($dom);
        $tables = $xpath->query('//table[@class="cal"]');

        foreach ($tables as $table) {
            $data = array();

            $month = $table->getElementsByTagName('th')[0]->textContent;
            $data['month'] = $month;

            $rows = $table->getElementsByTagName('tr');

            foreach ($rows as $row) {
                $cells = $row->getElementsByTagName('td');
                foreach ($cells as $cell) {
                    if ($cell->hasAttribute('class') && (str_contains($cell->getAttribute('class'), 'holiday weekend')
                            || str_contains($cell->getAttribute('class'), 'preholiday'))) {
                        $isPreholiday = strpos($cell->textContent, '*');
                        $day = str_replace('*', '', $cell->textContent);
                        $date = match ($data['month']) {
                            'Январь' => $day . '.01.' . $year,
                            'Февраль' => $day . '.02.' . $year,
                            'Март' => $day . '.03.' . $year,
                            'Апрель' => $day . '.04.' . $year,
                            'Май' => $day . '.05.' . $year,
                            'Июнь' => $day . '.06.' . $year,
                            'Июль' => $day . '.07.' . $year,
                            'Август' => $day . '.08.' . $year,
                            'Сентябрь' => $day . '.09.' . $year,
                            'Октябрь' => $day . '.10.' . $year,
                            'Ноябрь' => $day . '.11.' . $year,
                            'Декабрь' => $day . '.12.' . $year,
                        };

                        $holiday = new Holiday();
                        $holiday->fill(['date' => $date, 'is_shortened' => (bool)$isPreholiday])->save();
                    }
                }
            }
        }

        return true;
    }
}
