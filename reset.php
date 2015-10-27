<?php

include_once './connect.php';
include_once './classes/storage.class.php';



$storage = new storage();

$storage->delete_storage();

$conn->query("TRUNCATE `se_documents`");
$conn->query("TRUNCATE `se_index`");



