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
        #close {
            cursor: pointer;
        }
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
    <div id="login-form" class="row">
        <div class="panel panel-default">
            <div class="panel-heading">LOGIN</div>
            <div class="panel-body col-md-8">

                <div class="form-horizontal login-form">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">USERNAME</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="usn" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">PASSWORD</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="pwd" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default" onclick="signIn()">SIGN IN</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="user-info" class="row">
        <div class="panel panel-default">
            <div class="panel-heading">INFO</div>
            <div class="panel-body col-md-8">
                <div class="row user-info">
                    <pre id="user-info-detail" class="col-md-8"></pre>
                </div>
                <button id="close" onclick="logout()">LOGOUT</button>
            </div>
        </div>
    </div>

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
                <div id="request-url-holder" style="margin: 30px;">
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
<script type="text/javascript">
    var ACTION_LIST = <?= json_encode($action_list) ?>;
    var URL = '<?= $url ?>';
    var LOGIN_URL = '<?= $login_url ?>';
    var COOKIE_KEY_PREFIX = '<?= $cookie_key_prefix ?>';
    var currentAction = null;
    var apiRequestHost = window.location.origin;

    function getCookie(key) {
        return $.cookie(COOKIE_KEY_PREFIX + '-' + key);
    }

    function setCookie(key, value) {
        $.cookie(COOKIE_KEY_PREFIX + '-' + key, value);
    }

    function removeCookie(key) {
        $.removeCookie(COOKIE_KEY_PREFIX + '-' + key);
    }

    $(function () {
        init();
        renderAll();
    });

    function init() {

        $('#user-info').hide();
        $('#login-form').hide();

        var token = getCookie('token');
        var info = getCookie('info');
        console.log(info);
        if (token) {
            $('#token').val(token);
            $('#user-info').show();
            $('#user-info-detail').html($.format(info, {method: 'json'}));
        }
        else {
            $('#login-form').show();
        }

    }

    function renderAll() {
        $('#action-name').empty();
        $.each(ACTION_LIST, function (i, n) {
            $('#action-name').append("<option value='" + n.name + "'>" + n.name + "</option>");
        });
        var action_init = ACTION_LIST[0];
        $('#action-url').val(action_init.action).attr('disabled', 'disabled');
        $.each(action_init.params, function (i, n) {
            $('#params').append(
                '<div class="row item">' +
                '<div class="col-md-3"><h5 class="key">' + n.substring(0, n.indexOf('|')) + ':</h5></div>' +
                '<div class="col-md-8"><input class="form-control" name="' + n + '" type="text"/></div>' +
                '</div><div class="clear-10">'
            );
        });

    }

    $('#type').change(function () {
        var type = $(this).val();
        if (type == 'all') {
            renderAll();
            return;
        }

        $('#action-name').empty();
        $.each(ACTION_LIST, function (i, n) {
            if (n.type == type) {
                $('#action-name').append("<option value='" + n.name + "'>" + n.name + "</option>");
            }
        });
        var actionName = $('#action-name').val();
        $.each(ACTION_LIST, function (i, n) {
            if (n.name == actionName) {
                $('#action-url').val(n.action).attr('disabled', 'disabled');
                $('#params').empty();
                $.each(n.params, function (i, n) {
                    $('#params').append(
                        '<div class="row item">' +
                        '<div class="col-md-3"><h5 class="key">' + n.substring(0, n.indexOf('|')) + ':</h5></div>' +
                        '<div class="col-md-8"><input class="form-control" name="' + n + '" type="text"/></div>' +
                        '</div><div class="clear-10">'
                    );
                });
            }
        });
    });

    $('#action-name').change(function () {
        var actionName = $(this).val();
        $.each(ACTION_LIST, function (i, n) {
            if (n.name == actionName) {
                currentAction = n;
                $('#action-url').val(n.action).attr('disabled', 'disabled');
                $('#params').empty();
                $.each(n.params, function (i, n) {
                    $('#params').append(
                        '<div class="row item">' +
                        '<div class="col-md-3"><h5 class="key">' + n.substring(0, n.indexOf('|')) + ':</h5></div>' +
                        '<div class="col-md-8"><input class="form-control" name="' + n + '" type="text"/></div>' +
                        '</div><div class="clear-10">'
                    );
                });

                if (!currentAction.token) {
                    $('#token').attr('disabled', 'disabled');
                }
                else {
                    $('#token').removeAttr('disabled');
                }
            }
        });
    });

    function apiRequest() {
        var type = $('#type').val();
        var name = $('#action-name').val();
        var action = $('#action-url').val();
        var url = URL + action;
        var token = $('#token').val();
        if (!token && currentAction.token) {
            alert('token不能少');
            return;
        }
        var data = {client: 1, version: 1, token: token};
        $('#params input').each(function () {
            var p = $(this).attr('name').replace(' ', '');
            data[p.substring(0, p.indexOf('|'))] = $(this).val();
        });
        console.log(data);

        $('#response').removeClass('alert-success').removeClass('alert-danger').html('');

        var hrefLink = apiRequestHost + url + Util.getUriParams(data);
        $("#request-url-holder").empty().append('<div class="alert alert-info"><a href="' + hrefLink + '" target="_blank">点击查看</a></div>');

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (data) {
                console.log(data);
                if (data.code == 0) {
                    var jsonString = $.format(JSON.stringify(data), {method: 'json'});
                    console.log(jsonString);
                    $('#response').html(jsonString).addClass('alert-success');
                }
                else {
                    var jsonString = $.format(JSON.stringify(data), {method: 'json'});
                    console.log(jsonString);
                    $('#response').html(jsonString).addClass('alert-danger');
                }
            }
        });
    }

    function signIn() {
        var phone = $('#usn').val();
        var password = $('#pwd').val();

        $.ajax({
            url: LOGIN_URL,
            type: 'POST',
            dataType: 'json',
            data: {
                client: 1,
                version: 1,
                username: phone,
                password: password
            },
            success: function (data) {
                console.log(JSON.stringify(data));
                if (data.code == 0) {
                    setCookie('token', data.data.token);
                    setCookie('info', JSON.stringify(data));
                    init();
                }
                else {
                    alert("code: " + data.code + " message: " + data.error);
                }
            }
        });
    }

    function logout() {
        removeCookie('token');
        removeCookie('info');
        init();
    }


    var Util = {
        rtrim: function (str, charlist) {
            //  discuss at: http://phpjs.org/functions/rtrim/
            // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            //    input by: Erkekjetter
            //    input by: rem
            // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // bugfixed by: Onno Marsman
            // bugfixed by: Brett Zamir (http://brett-zamir.me)
            //   example 1: rtrim('    Kevin van Zonneveld    ');
            //   returns 1: '    Kevin van Zonneveld'

            charlist = !charlist ? ' \\s\u00A0' : (charlist + '')
                .replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
            var re = new RegExp('[' + charlist + ']+$', 'g');
            return (str + '')
                .replace(re, '');
        },

        getUriParams: function (data) {
            var uri = '?';
            for (var i in data) {
                uri += i + '=' + data[i] + '&';
            }

            return Util.rtrim(uri, '&');
        }
    }

</script>