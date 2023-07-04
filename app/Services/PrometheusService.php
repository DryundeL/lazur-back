<?php

namespace App\Services;

class PrometheusService extends BaseService
{

    /**
     * Create metric for prometheus
     *
     * @param $metricName
     * @param $metricType
     * @param $value
     * @param array $quantiles
     * @param array $bucketValues
     * @param int $countValue
     * @param int $sumValue
     * @return EmployeeResource
     */
    public function generateMetric($metricName, $metricType, $value, $quantiles = [], $bucketValues = [], $countValue = 0, $sumValue = 0): string
    {
        $metricHelp = '# HELP ' . $metricName . "\n";
        $metricTypeLine = '# TYPE ' . $metricName . ' ' . $metricType . "\n";

        switch ($metricType) {
            case 'gauge':
            case 'counter':
                $metricLine = $metricName . ' ' . $value . "\n";
                return $metricHelp . $metricTypeLine . $metricLine . "\n";
            case 'summary':
                $quantileLines = '';

                foreach ($quantiles as $quantile => $quantileValue) {
                    $quantileLine = $metricName . '_quantile{' . 'quantile="' . $quantile . '"} ' . $quantileValue . "\n";
                    $quantileLines .= $quantileLine;
                }

                return $metricHelp . $metricTypeLine . $quantileLines . "\n";
            case 'histogram':
                $metricLines = [];

                foreach ($bucketValues as $bucket => $bucketValue) {
                    $bucketLine = $metricName . '_bucket{le="' . $bucket . '"} ' . $bucketValue . "\n";
                    $metricLines[] = $bucketLine;
                }

                $countLine = $metricName . '_count ' . $countValue . "\n";
                $metricLines[] = $countLine;

                $sumLine = $metricName . '_sum ' . $sumValue . "\n";
                $metricLines[] = $sumLine;

                return $metricHelp . $metricTypeLine . implode("", $metricLines) . "\n";
            default:
                return '';
        }
    }

}
