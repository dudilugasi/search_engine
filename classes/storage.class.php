<?php

define("STORAGE_DIR", "storage/");

class storage {

    function save_document($document) {
        global $conn;
        if (!is_array($document) || count($document) != 4) {
            return false;
        }
        $docid = $this->get_doc_id();
        $fp = fopen(STORAGE_DIR . $docid . '.txt', 'a');
        fwrite($fp, serialize($document));
        fclose($fp);

        $title = isset($document["title"]) ? $document["title"] : "";
        $chapter_name = isset($document["name"]) ? $document["name"] : "";
        $chapter_num = isset($document["chapter"]) ? $document["chapter"] : "";
        $sql = "INSERT INTO "
                . "`se_documents` "
                . "(`docid`, `title`, `chapter_name`, `chapter_num`) "
                . "VALUES"
                . " ('" .
                $conn->real_escape_string($docid) . "', '"
                . $conn->real_escape_string($title) . "', '"
                . $conn->real_escape_string($chapter_name) . "', '"
                . $conn->real_escape_string($chapter_num) . "');";
        $conn->query($sql);
        return $docid;
    }

    function get_document($docid) {
        if (!is_integer($docid) || $docid < 0) {
            return null;
        }

        $file_name = STORAGE_DIR . $docid . '.txt';
        if (!file_exists($file_name)) {
            return null;
        }
        $content = file_get_contents($file_name);
        return unserialize($content);
    }

    function get_document_meta($docid,$offset = 0) {
        global $conn;
        
        if (!is_integer($docid) || $docid < 0) {
            return null;
        }
        
        $sql = "SELECT * FROM `se_documents` WHERE `docid` = $docid";
        
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        $document = $this->get_document($docid);
        
        $content = $document["content"];
        
        $offset =  ($offset - 200 < 0) ? 0 : $offset - 200;
            
        $row["excerpt"] = substr($content, $offset , 400);
        return $row;
        
    }

    function get_doc_id() {
        $file_name = STORAGE_DIR . 'count.txt';
        $counter = 0;
        if (file_exists($file_name)) {
            $file = fopen($file_name, 'r');
            $counter = (int) fgets($file);
        }
        $file = fopen($file_name, 'w');
        fputs($file, $counter + 1);
        fclose($file);
        return $counter;
    }

    function delete_storage() {
        $fp = opendir(STORAGE_DIR);
        while (false !== ($file = readdir($fp))) {
            if (is_file(STORAGE_DIR . $file)) {
                unlink(STORAGE_DIR . $file);
            }
        }
    }

    function remove_docs($docs) {
        global $conn;
        $sql = "UPDATE `se_documents` SET `exist`= 0 WHERE `docid` IN ( " . implode(",", $docs) . " )";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

}
