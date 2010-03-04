<?php
if (isset($msg) AND $msg != '') {
    echo '<span class="msg">',$msg,'</span>',PHP_EOL;
}

if (isset($err) AND $err != '') {
    echo '</span class="err">',$err,'</span>',PHP_EOL;
}
