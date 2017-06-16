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
                    <label>Content</label>
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
        
    })
</script>