<?php
use yii\helpers\Html;
use yii\web\JqueryAsset;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

  

    <?= $content ?>




<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
