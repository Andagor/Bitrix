<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <title>Тест</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <?php
        require_once "app.php";
        $linc = '"https://b24.crm.in.ua/webhooks/Diason/analytics/Приложение/index.php"';

        $date_from = date_create($_POST['calendar_from']);
        $date_to = date_create($_POST['calendar_to']);
    ?>

    <div class="header-block">
        <div class="header-text">
            <p>Выборка за: </p>
            <p class="selected-date">
                <?php echo date_format($date_from, 'd.m.Y').' - '.date_format($date_to, 'd.m.Y') ?>
            </p>
        </div>

        <form action=" <?php $linc; ?> "method="post">
            <!-- <p> С: </p> -->
                <input type="date" id="date_from" name="calendar_from">
            <p> – </p>
                <input type="date" id="date_to" name="calendar_to">

                <input type="submit" value="Выбрать" onclick=" <?php $linc; ?> ">
        </form>
    </div>;

    <div class="body-block">
        <div class="t-block"> <?php echo $table_pay_form;   ?>  </div>
        <div class="t-block"> <?php echo $table_department; ?>  </div>
        <div class="t-block"> <?php echo $table_article; ?>     </div>
    </div>
  </body>
</html>