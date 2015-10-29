<?php
include_once 'connect.php';
include_once 'classes/storage.class.php';


$document = false;
if (isset($_GET["doc"])) {
    $storage = new storage();

    $docid = is_numeric($_GET["doc"]) ? intval($_GET["doc"]) : null;

    if (!is_null($docid)) {
        $document = $storage->get_document($docid);
    }

    //print_r($document);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>search</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="lib/font-awesome-4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    </head>
    <body>
        <section class='single-document'>
            <?php if ($document): ?>
                <h1 class='entry-header'><?php echo $document["title"] . " ( chapter number: " . $document["chapter"] . " )" ?></h1>
                <div id="print"><i class="fa fa-print"></i></div>
                <div class="content">
                    <?php echo nl2br($document["content"]) ?>
                </div>
            <?php endif; ?>
        </section>
        <script>
            jQuery(document).ready(function(){
                $("#print").click(function(){
                    window.print();
                });
            });
        </script>
    </body>
</html>