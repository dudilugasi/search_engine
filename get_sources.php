<?php
include_once 'connect.php';
include_once 'classes/storage.class.php';
include_once 'classes/index.class.php';
include_once 'classes/indexer.class.php';


define("SOURCE_DIR", "source/");

$storage = new storage();
$index = new index();
$indexer = new indexer($index, $storage);

$file_names = scandir(SOURCE_DIR, 1);

if (!$file_names) {
    die();
}

$documents = array();
foreach ($file_names as $file_name) {
    if ($file_name[0] != '.') {

        //copy file from source to storage
        $fp = fopen(SOURCE_DIR . $file_name, "r");

        //scan file
        if (!$fp) {
            continue;
        }

        $document = array();

        $offset = 0;

        $counter = 0;
        while (($line = fgets($fp)) !== false) {

            //if the line start with # it is document data
            if ($line[0] == '#') {

                //add document to db and get the docID
                $arr = explode(" = ", substr($line, 1));

                //set the document data
                $document[$arr[0]] = $arr[1];
                $offset += strlen($line);
            }

            if ($counter > 3) {
                break;
            }
            $counter++;
        }

        $document["content"] = stream_get_contents($fp, -1, $offset);

        $documents[] = $document;
    }
}

$indexer->index($documents);


$fp = opendir(SOURCE_DIR);
while (false !== ($file = readdir($fp))) {
    if (is_file(SOURCE_DIR . $file)) {
        unlink(SOURCE_DIR . $file);
    }
}
?>
<h1> documents added successfully! </h1>
<a href="admin.php">back</a>







