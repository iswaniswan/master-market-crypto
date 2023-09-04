<?php

use app\components\Helper;
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
                <div class="col-2 text-center">
                    <img src="<?= $model->assets->getImageUrl() ?>" class="mx-auto" style="width: 48px; display: block; margin-bottom: .5rem">
                    <span>#RANK <?= $model->assets->rank ?></span>
                </div>
                <div class="col-3 text-center">
                    <span class="">Market Cap</span>
                    <h6>$<?= Helper::getSimpleTermEn($model->assets->market_cap_usd) ?></h6>
                </div>
                <div class="col-3 text-center">
                    <span class="">Volume (24Hr)</span>
                    <h6>$<?= Helper::getSimpleTermEn($model->assets->vwap_24_hr) ?></h6>
                </div>
                <div class="col-3 text-center">
                    <span class="">Supply</span>
                    <h6><?= Helper::getSimpleTermEn($model->assets->supply) ?> <?= $model->assets->symbol ?></h6>
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
