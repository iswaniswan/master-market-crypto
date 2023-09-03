<?php

use app\models\Paket;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Deposit';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Deposit'
    ],
]) ?>


<?php foreach ($dataProvider->models as $model) {
    echo $this->render('_paket-detail', [
        'model' => $model
    ]);
} ?>

<?php 

$style = <<<CSS
    .card-poin::before {
        content: '+';
        margin-right: 5px;
    }
    .card-poin::after {
        content: 'POIN';
        margin-left: 10px;
    }
CSS;

$this->registerCss($style);

?>