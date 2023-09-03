<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Daftar Paket';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Paket'    
    ],
]) ?>

<?php echo $this->render('_paket-detail', [
    'model' => $model
]) ?>

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
    .no-border {
        border: unset !important;
    }
    .no-pl {
        padding-left: unset !important;
    }
CSS;

$this->registerCss($style);

$js = <<<JS
    $(document).ready(function() {
        $('#checkbox_agree').on('change', function() {
            const t = $(this);
            if (t.is(":checked")) {
                $('#action-purchase').removeClass('disabled');
            } else {
                $('#action-purchase').addClass('disabled');
            }
        })
    })
JS;

$this->registerJs($js);

?>