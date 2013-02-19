<?php if (Arr::get($_SESSION, 'db_connect')): ?>
    <div id="main_menu">
        <ul>
            <li><a href="<?php echo URL_ROOT;?>">Коннект к БД</a></li>
            <li><a href="<?php echo URL_ROOT . '?q=show_struct';?>">Просмотр БД</a></li>
            <li><a href="<?php echo URL_ROOT . '?q=logout';?>">Выход</a></li>
        </ul>
    </div>
<?php endif; ?>

