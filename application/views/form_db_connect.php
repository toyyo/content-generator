<div id="db_connect_form">
    <?php echo Messages::view();?>
    <form action method="post">
        <div><input type="text" name="dbhost" value="<?php echo Arr::get($_POST, 'dbhost', 'localhost');?>"/><span class="desc">хост БД</span></div>
        <div><input type="text" name="dbuser" value="<?php echo Arr::get($_POST, 'dbuser');?>"/><span class="desc">имя пользователя</span></div>
        <div><input type="password" name="dbpass" value=""/><span class="desc">пароль</span></div>
        <div><input type="text" name="dbname" value="<?php echo Arr::get($_POST, 'dbname');?>"/><span class="desc">имя БД</span></div>
        <div><input type="submit" value="подконнектиться к бд"/></div>
    </form>
</div>
