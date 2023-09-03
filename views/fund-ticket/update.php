<?php

/* @var $this yii\web\View */
/* @var $model app\models\FundTicket */
/* @var $referrer string */

$this->title = 'Edit Fund Ticket';
$this->params['breadcrumbs'][] = ['label' => 'Fund Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="fund-ticket-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
