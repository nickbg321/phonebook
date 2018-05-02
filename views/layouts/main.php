<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="pull-left page-title-header"><a href="<?= Url::to('/'); ?>">Phonebook</a></h3>

                <a class="pull-right logout-btn" href="<?= Url::to('auth/logout'); ?>">
                    Logout (<?= Html::encode(Yii::$app->user->getIdentity()->username); ?>)
                </a>
            </div>
        </div>

        <?= Alert::widget(); ?>
        <?= $content; ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
