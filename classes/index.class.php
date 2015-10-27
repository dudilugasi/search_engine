<?php

class index {

    public $index = array();

    function __construct() {
        global $conn;
        $sql = "SELECT * FROM `se_index`";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->index[$row["term"]] = array("hits"=>$row["hits"],"posting"=>  unserialize($row["posting"]));
            }
        }
    }

    function save_documents($term, $hits, $docs) {
        global $conn;

        //check if the values are correct
        if ($term === null || $docs === null || $hits === null || trim($term) == '') {
            return false;
        }

        if (!is_string($term) || !is_array($docs) || !is_integer($hits)) {
            return false;
        }

        $posting = $this->get_documents($term);
        $hits += count($posting);

        foreach ($docs as $doc => $value) {
            $posting[$doc] = $value;
        }

        /* $sql = "INSERT"
          . " into `se_index`(`term`,`hits`, `posting`) "
          . "VALUES ('" . $term . "'," . $hits . ",'" . $conn->real_escape_string(serialize($posting)) . "')"
          . " ON DUPLICATE KEY UPDATE hits=VALUES(hits) , posting=VALUES(posting)";


          return ($conn->query($sql) === TRUE) ? true : false; */
        $this->index[$term] = array("hits" => $hits, "posting" => $posting);
    }

    function get_documents($term) {
        global $conn;

        if ($term === null || trim($term) == '') {
            return array();
        }

        /* $sql = "SELECT `posting` FROM `se_index` WHERE `term` = '" . $conn->real_escape_string($term) . "'";
          $result = $conn->query($sql); */
        if (array_key_exists($term, $this->index)) {
            return $this->index[$term]["posting"];
        }

        return array();
    }

    function store_index() {
        global $conn;
        $values = array();
        foreach ($this->index as $key => $value) {
            $values[] = "('" . $conn->real_escape_string($key) . "'," . $value["hits"] . ",'" . $conn->real_escape_string(serialize($value["posting"])) . "')";
        }

        $sql = "INSERT"
                . " into `se_index`(`term`,`hits`, `posting`) "
                . "VALUES " . implode(",", $values)
                . " ON DUPLICATE KEY UPDATE hits=VALUES(hits) , posting=VALUES(posting)";

        return ($conn->query($sql) === TRUE) ? true : false;
    }

    function get_documents_ids() {
        global $conn;
        $sql = "SELECT `docid` FROM `se_documents`";
        if ($result = $conn->query($sql)) {
            $ids = array();
            while ($row = $result->fetch_assoc()) {
                $ids[$row["docid"]] = 0;
            }
            return $ids;
        }
        return array();
    }

}
