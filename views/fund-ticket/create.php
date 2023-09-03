<?php


/* @var $this yii\web\View */
/* @var $model app\models\FundTicket */
/* @var $referrer string */

$this->title = 'Tambah Fund Ticket';
$this->params['breadcrumbs'][] = ['label' => 'Fund Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-ticket-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>