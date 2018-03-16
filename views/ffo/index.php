<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/12/17
 * Time: 7:02 PM
 */


?>

<style>
    a:visited {
        color: #ccc;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-1">
            <h4>FFO</h4>
            <table class="table table-hover table-border">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($ffo_list as $key => $item)
                    {
                        ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><a href="<?= $item['href'] ?>" target="_blank"><?= $item['title'] ?></a></td>
                        </tr>  
                <?php
                    }
                ?>
               
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h4>FFO Buy</h4>
            <table class="table table-hover table-border">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($ffo_buy_list as $j => $i)
                {
                    ?>
                    <tr>
                        <td><?= $j + 1 ?></td>
                        <td><a href="<?= $i['href'] ?>" target="_blank"><?= $i['title'] ?></a></td>
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>