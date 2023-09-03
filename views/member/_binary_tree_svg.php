<?php

use yii\helpers\Html;
use app\components\ChartBinary;

header("Content-type: image/svg-xml");

$chart = new \app\components\ChartBinary2();
$chart->svgWidth = 1000;
$chart->svgHeight = 600;
$chart->svgViewBox = "0 0 1100 700";
$chart->dataChart = $dataChartBinary;

echo $chart->getOutputSvg();

$style = $chart->getCss();
$this->registerCss($style);

