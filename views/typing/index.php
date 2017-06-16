<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/16/17
 * Time: 3:35 PM
 */
/**
 * @var $this \yii\web\View;
 */

$this->title = "打字比赛";
?>

<div class="clear-10"></div>
<div class="clear-10"></div>
<div class="clear-10"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form action="<?= url('typing/submit') ?>" class="form" method="post">
                <div class="form-group">
                    <label>Origin Content</label>
                    <textarea name="origin_content" class="form-control" id="origin-content" rows="10"></textarea>
                    <span class="help-block" id="origin-content-words"></span>
                </div>
                <div class="form-group">
                    <label>Content</label> <button class="btn btn-primary btn-xs" id="begin">Begin</button> <span id="time"></span>
                    <input type="hidden" name="time" id="time-value" value="">
                    <textarea name="content" onpaste="return false" oncontextmenu="return(false)" id="content" class="form-control" rows="10"></textarea>
                    <span class="help-block" id="content-words"></span>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(function () {
        var countWords1 = function () {
            var words = $('#origin-content').val().length || 0;
            $("#origin-content-words").text(words);
        };

        var countWords2 = function () {
            var words = $('#content').val().length || 0;
            $("#content-words").text(words);
        };
        
        setInterval(countWords1, 500);
        setInterval(countWords2, 500);
        
        var intervalTimer;
        $("#begin").on('click', function () {
            
            if ($("#content").val().length > 0)
            {
                alert("输入内容不为空，无法重置定时器");
                return false;
            }
            var beginTime = time();
            
            if (intervalTimer) {
                clearInterval(intervalTimer);
            }
            intervalTimer = setInterval(function () {
                var value = time() - beginTime;
                $("#time").text(value + "s");
                $("#time-value").val(value);
            }, 500);
            
            return false;
        });


        function time() {
            return parseInt(new Date().getTime() / 1000, 10);
        }
    })
</script>