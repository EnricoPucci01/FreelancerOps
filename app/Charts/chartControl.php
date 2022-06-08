<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\ChartJS\Chart;
use Illuminate\Support\Facades\DB;

class chartControl extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

}
