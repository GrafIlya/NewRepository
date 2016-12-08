<?php
    $app = Model_DB::query("SELECT * FROM `article`");
    foreach($app as $shit => $do)
    {
        echo '<div class="article">';
        echo("<h2>$do[title]</h2>");
        echo("<p>$do[description]</p>");
        echo("Создано: $do[created] | Изменено: $do[update]");
        echo '</div><br>';
    }
?>
