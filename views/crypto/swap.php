<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Crypto */
/* @var $form yii\widgets\ActiveForm */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Market Swap";

$this->params['breadcrumbs'][] = ['label' => 'Swap', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Swap"
    ],
]);


?>

<div class="row">
    <div class="container-fluid">
        <div class="card-box">
            <div class="card-body row">
                <div class="col-2 text-center badge badge-pink m-1">
                    <h6 class="text-white">#RANK <?= $model->assets->rank ?></h6>
                </div>
                <div class="col-3 text-center badge badge-success m-1">
                    <h6 class="text-white">Market Cap $<?= number_format($model->assets->market_cap_usd, 0, ".", ",")  ?></h6>
                </div>
                <div class="col-3 text-center badge badge-info m-1">
                    <h6 class="text-white">Volume (24Hr) <?= number_format($model->assets->vwap_24_hr, 0, ".", ",")  ?></h6>
                </div>
                <div class="col-3 text-center badge badge-warning m-1">
                    <h6 class="text-white">Supply <?= number_format($model->assets->supply, 0, ".", ",")  ?>
                        <?= $model->assets->symbol ?>
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-box">
            <div class="card-body">
                <?= $this->render('_swap_chart', [
                        'model' => $model,
                        'dataChart' => $dataChart
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <ul class="nav nav-pills" id="myTabalt" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#home1" role="tab" aria-controls="home" aria-expanded="true" aria-selected="true">Market Jual</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab1" data-toggle="tab" href="#profile1" role="tab" aria-controls="profile" aria-selected="false">Market Beli</a>
            </li>
        </ul>
        <div class="tab-content text-muted" id="myTabaltContent">
            <div role="tabpanel" class="tab-pane fade in active show" id="home1" aria-labelledby="home-tab">
                <?php echo $this->render('_market_jual', [
                        'model' => $model,
                        'referrer' => $referrer
                ]) ?>
            </div>
            <div class="tab-pane fade" id="profile1" role="tabpanel" aria-labelledby="profile-tab">
                <?php echo $this->render('_market_beli', [
                    'model' => $model,
                    'referrer' => $referrer
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php

$style = <<<CSS
    
CSS;

$this->registerCss($style);

?>
