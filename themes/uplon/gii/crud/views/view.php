<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)); ?>";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)); ?>";
}
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?="<?="?> \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)); ?>"
    ],
]) ?>

<?="<?="?> $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>