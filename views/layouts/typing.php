<?php
use yii\helpers\Html;
use yii\web\JqueryAsset;


\app\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <script src="/js/jquery-1.11.1.min.js"></script>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

  

    <?= $content ?>




<?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
