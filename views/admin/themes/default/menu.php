<nav>
    <h1>Navigation:</h1>
    <ul>
    <?php foreach($menu as $topic=>$item) {
        echo '<li id="nav_'.url::title($topic).'">';
        echo empty($item[0]) ? $topic : html::anchor($item[0], $topic);
        if (isset($item[1]) AND is_array($item[1])) {
            echo PHP_EOL, '<ul>', PHP_EOL;
            foreach($item[1] as $display=>$link) {
                echo '<li id="nav_nav_'.url::title($display).'">';
                echo html::anchor($link, $display).'</li>'.PHP_EOL;
            }
            echo '</ul>', PHP_EOL;
        }
        echo '</li>', PHP_EOL;
    } ?>
    </ul>
</nav>
