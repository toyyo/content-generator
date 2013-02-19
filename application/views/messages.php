<?php if (isset($aMessages) && count($aMessages)): ?>
    <?php foreach ($aMessages as $msg): ?>
        <div class="msg">
            <div class="<?=$sType;?>">
                <?php echo $msg; ?>
            </div>
        </div> 
    <?php endforeach; ?>
<?php endif; ?>
