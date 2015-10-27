<?php
include_once 'connect.php';
include_once 'classes/storage.class.php';
include_once 'classes/index.class.php';
include_once 'classes/search.class.php';

$results = array();
$message = "";
$instructions = false;
if (isset($_GET["search"])) {
    $message = "sorry! no results found";
    $index = new index();
    $storage = new storage();
    $search = new search($index, $storage);
    $results = $search->search_documents($_GET["search"]);
} else {
    $instructions = true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>search</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="lib/animate.css">
        <link rel="stylesheet" type="text/css" href="lib/font-awesome-4.4.0/css/font-awesome.min.css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    </head>
    <body>
        <div class="search_bar">
            <form action="" method="">
                <input type="text" value='' name="search" />
                <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="instructions">
            <div class="results-container">
                <div class="instructions">
                    <?php if ($instructions): ?>
                        <p>
                            to search documents please write in the search box the terms you want to search for,
                            seperated by the operators "and" , "or" and "not". each world should be separated with white space, even paranthesis.
                            <br>
                            for example: dog and house and ( not cat )
                            <br>
                            to search a word that is in the stop list you need to write with double quotations marks.
                            if you want to find a wild card add * to the end of the word and it will find all the document that match the prefix of the word.
                            each word you type will also search in three synonyms for better results.



                        </p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $result): ?>
                        <?php if ($result["exist"] == 1): ?>
                            <section class="result">
                                <h1><a href="single-document.php?doc=<?php echo $result["docid"] ?>">
                                        <?php echo $result["chapter_name"] . " ( chapter number: " . $result["chapter_num"] . " )" ?>
                                    </a></h1>
                                <p class="excerpt"><?php echo nl2br($result["excerpt"]) ?></p>
                            </section>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='message'>
                        <?php echo $message; ?>
                    </p>
                <?php endif; ?>

            </div>

            <script>
                $(document).ready(function () {

                    $(".result").addClass('animated fadeInUp');

                });
            </script>
    </body>
</html>