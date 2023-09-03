<?php

use app\components\Session;
use app\models\RewardClaimed;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RewardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Daftar Reward';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Reward'    ],
]) ?>

<div class="row mb-4">
    <?php foreach ($dataProvider->models as $model) { ?>
        <div class="col-md-6 col-xl-3">
            <div class="card-box tilebox-one">
                <?php for ($i = 0; $i < $model->rating; $i++) { ?>
                    <i class="icon-star float-right m-0 h4 text-gold"></i>
                <?php } ?>                
                <h6 class="text-muted text-uppercase mt-0">Reward</h6>
                <h3 class="my-3 card-poin"><?= number_format($model->type, 0, ",", ".") ?></h3>
                <div class="row" style="display: flex;">
                    <div class="container" style="position: relative">
                        <span class="text-muted">omzet</span>
                        <div><?= $model->getSimpleTerm() . " : " . $model->getSimpleTerm() ?></div>

                        <?php 
                        /** action claim */
                        $link = "javascript:void(0)";
                        $text = "Claim";
                        $btn = "btn-primary";
                        $isDisabled = "disabled";

                        $isClaimed = RewardClaimed::isRewardClaimd($model->id, Session::getIdMember());

                        if ($isClaimed) {
                            $text = "Success";
                            $btn = "btn-secondary";

                            $rewardClaimed = RewardClaimed::findOne([
                                'id_member' => Session::getIdMember(),
                                'id_reward' => $model->id 
                            ]);
                            if ($rewardClaimed->status != RewardClaimed::SUCCESS) {
                                $text = "Pending";
                            }

                        } else {

                            $eligible = RewardClaimed::isEligible($model->id, Session::getIdMember());

                            if ($eligible) {
                                $link = Url::to([
                                    'reward-claimed/create-claim', 'id_reward' => $model->id, 'id_member' => Session::getIdMember()
                                ]);
                                $isDisabled = '';
                            }
                        }
                        
                        ?>
                        <a href="<?= $link ?>" class="btn <?= $btn ?> btn-rounded waves-effect waves-light float-right btn-claim <?= $isDisabled ?>"><?= $text ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$style = <<<CSS
.text-gold {
    color: gold !important;
}
.btn-claim {
    position: absolute;
    top: 25%;
    right: 0;
}
CSS;

$this->registerCss($style);

?>