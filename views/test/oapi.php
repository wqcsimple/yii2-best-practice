<?php
use yii\widgets\ActiveForm;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <link rel="stylesheet" href="/css/util.css"/>
    <link rel="stylesheet" href="/res/bootstrap/css/bootstrap.min.css"/>
    <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="/res/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/jquery.format.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <style>
        #close { cursor: pointer; }
    </style>
</head>
<body>

<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">API TEST</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

            </ul>

        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3"><h5>TYPE</h5></div>
                        <div class="col-md-8">
                            <select class="form-control" name="type" id="type">
                                <option value="all">全部</option>
                                <?php
                                foreach ($type_list as $type)
                                {
                                    ?>
                                    <option value="<?= $type ?>"> <?= $type ?></option>
                                <?
                                }
                                ?>
                            </select>
                        </div>
                        <div class="help-block"></div>
                    </div>
                    <div class="clear-10"></div>
                    <div class="row">
                        <div class="col-md-3"><h5>NAME</h5></div>
                        <div class="col-md-8">
                            <select class="form-control" name="action-name" id="action-name">

                            </select>
                        </div>

                    </div>
                    <div class="clear-10"></div>
                    <div class="row">
                        <div class="col-md-3"><h5>ACTION</h5></div>
                        <div class="col-md-8"><input class="form-control" type="text" id="action-url"/></div>
                        <div class="help-block">POST</div>
                    </div>
                    <div class="clear-10"></div>
                    <div class="row">
                        <div class="col-md-3"><h5>PARAMS</h5></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><h5>token:</h5></div>
                        <div class="col-md-8"><input class="form-control" type="text" id="token"/></div>
                    </div>
                    <div class="clear-10"></div>
                    <div id="params">

                    </div>
                    <div class="clear-10"></div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-3">
                            <button onclick="apiRequest()">REQUEST</button>
                        </div>
                    </div>
                </div>

                <div class="clear-10"></div>
                <div class="row">
                    <pre id="response" class="alert " style="margin: 30px;"></pre>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    var ACTION_LIST = <?= json_encode($action_list) ?>;
    var URL = '<?= $url ?>';
    var currentAction = null;

    $(function(){
        renderAll();
    });

    function renderAll()
    {
        $('#action-name').empty();
        $.each(ACTION_LIST, function(i, n) {
            $('#action-name').append("<option value='"+ n.name +"'>" + n.name + "</option>");
        });
    }

    $('#type').change(function(){
        var type = $(this).val();
        if (type == 'all')
        {
            renderAll();
            return;
        }

        $('#action-name').empty();
        $.each(ACTION_LIST, function(i, n) {
            if (n.type == type)
            {
                $('#action-name').append("<option value='"+ n.name +"'>" + n.name + "</option>");
            }
        });
        var actionName = $('#action-name').val();
        $.each(ACTION_LIST, function(i, n){
            if (n.name == actionName)
            {
                $('#action-url').val(n.action).attr('disabled', 'disabled');
                $('#params').empty();
                $.each(n.param, function(i ,n){
                    $('#params').append(
                        '<div class="row item">' +
                        '<div class="col-md-3"><h5 class="key">'+ n +':</h5></div>' +
                        '<div class="col-md-8"><input class="form-control" name="' + n + '" type="text"/></div>' +
                        '</div><div class="clear-10">'
                    );
                });
            }
        });
    });

    $('#action-name').change(function(){
        var actionName = $(this).val();
        $.each(ACTION_LIST, function(i, n){
            if (n.name == actionName)
            {
                currentAction = n;
                $('#action-url').val(n.action).attr('disabled', 'disabled');
                $('#params').empty();
                $.each(n.param, function(i ,n){
                    $('#params').append(
                        '<div class="row item">' +
                        '<div class="col-md-3"><h5 class="key">'+ n +':</h5></div>' +
                        '<div class="col-md-8"><input class="form-control" name="' + n + '" type="text"/></div>' +
                        '</div><div class="clear-10">'
                    );
                });

                if (!currentAction.token)
                {
                    $('#token').attr('disabled', 'disabled');
                }
                else
                {
                    $('#token').removeAttr('disabled');
                }
            }
        });
    });

    function apiRequest(){
        var type = $('#type').val();
        var name = $('#action-name').val();
        var action = $('#action-url').val();
        var url = URL + action;
        var token = $('#token').val();
        if (!token && currentAction.token)
        {
            alert('token is required');
            return;
        }
        var data = {client: 1, version:1, token: token};
        $('#params input').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        console.log(data);

        $('#response').removeClass('alert-success').removeClass('alert-danger').html('');

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(data){
                console.log(data);
                if (data.code == 0)
                {
                    var jsonString = $.format(JSON.stringify(data),  {method: 'json'});
                    console.log(jsonString);
                    $('#response').html(jsonString).addClass('alert-success');
                }
                else
                {
                    var jsonString = $.format(JSON.stringify(data),  {method: 'json'});
                    console.log(jsonString);
                    $('#response').html(jsonString).addClass('alert-danger');
                }
            }
        });
    }


</script>