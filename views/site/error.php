<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="row justify-content-center">
    <div class="container text-center">
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <h1 class="display-4 text-dark"><?= Html::encode($this->title) ?></h1>

        <p style="padding: 1rem 0.5rem;">
            The above error occurred while the Web server was processing your request. <br>
            Please contact us if you think this is a server error. Thank you.
        </p>
        <?= Html::a('Home', ['/'], ['class' => 'btn btn-primary']) ?>

    </div>
</div>

