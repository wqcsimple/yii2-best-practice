<?
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="Description" content=""/>
    <meta name="Keywords" content=""/>
    <title><?= Html::encode($this->title) ?></title>
    <link href="/css/mobile/base.css" rel="stylesheet" />
    <script src="/js/zepto.min.js" type="text/javascript"></script>
</head>
<body>
    <div class="wrap">
        <?= $content ?>
    </div>
</body>
</html>