<?php

$linc = 'index.php';

$form = '<form action=" <?php $linc; ?> "method="post">
                <input type="date"      id="date_from"  name="calendar_from">
                <input type="date"      id="date_to"    name="calendar_to">
                <input type="submit"    value="Выбрать" onclick=" <?php $linc; ?> ">
        </form>';

?>