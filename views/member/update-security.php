<?php

use app\components\Mode;
use app\components\Session;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $referrer string */

$this->title = 'Update Security';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => "Security"
    ],
]) ?>

<div class="member-update">

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-12',
            'wrapper' => 'col-12',
            'error' => '',
            'hint' => '',
            'field' => 'mb-3 row',
        ],
        'options' => ['style' => 'padding:unset'],
    ]
]); ?>

<div class="row">
    <div class="col-8">
        <div class="dt-button-wrapper">
            <?php $id_member = Session::getIdMember(); ?>
            <?= Html::a('<i class="icon-user mr-2"></i>Profile', ['member/update-profile', 'id' => $id_member], ['class' => 'btn btn-success mb-1']) ?>
            <?= Html::a('<i class="icon-badge mr-2"></i>Paket', ['member/update-paket', 'id' => $id_member], ['class' => 'btn btn-purple mb-1']) ?>
            <?= Html::a('<i class="icon-wallet mr-2"></i>Bank', ['member/update-bank', 'id' => $id_member], ['class' => 'btn btn-info mb-1']) ?>
            <?= Html::a('<i class="icon-lock-open mr-2"></i>Security', ['member/update-security', 'id' => $id_member], ['class' => 'btn btn-danger mb-1']) ?>            
        </div>

        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3"><?= $this->title ?></h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($user) ?>

                    <?= $form->field($user, 'username')->textInput([
                            'readonly' => 'readonly'
                    ]) ?>

                    <?= $form->field($user, 'email')->input('email', [
                        'readonly' => 'readonly'
                    ]) ?>

                    <?= $form->field($user, 'pin')->input('number') ?>

                    <?= $form->field($user, 'password')->input('password') ?>

                </div>

                <?= Html::hiddenInput('referrer', $referrer) ?>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="container-fluid">
        <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['dashboard/index-member'], ['class' => 'btn btn-info mb-1']) ?>
        <?php if ($mode == 'view') { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords($mode) .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>
