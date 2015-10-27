<?php

class indexer {

    public $index = null;
    public $storage = null;

    function __construct($index, $storage) {

        $this->index = $index;
        $this->storage = $storage;
    }

    public function index($docs) {
        ini_set('max_execution_time', 300);
        
        if (!is_array($docs)) {
            return;
        }

        $terms_table = array();

        foreach ($docs as $doc) {
            if (!is_array($doc)) {
                continue;
            }

            $terms = str_word_count($doc["content"], 2);
            $docid = $this->storage->save_document($doc);
            foreach ($terms as $offset => $term) {
                $terms_table[] = array("term" => strtolower($term), "docid" => $docid , "offset" => $offset );
            }
        }
        
        
        $terms_table_sorted = $this->sort_terms($terms_table);

        
        $index = array();
        $prev_row["term"] = "";
        $prev_row["docid"] = "";

        foreach ($terms_table_sorted as $row) {
            if ($row["term"] != $prev_row["term"]) {
                $index[$row["term"]] = array("hits" => 1, "posting" => array($row["docid"]=>$row["offset"]));
            } else if ($row["docid"] != $prev_row["docid"]) {
                $index[$row["term"]]["posting"][$row["docid"]] = $row["offset"];
                $index[$row["term"]]["hits"] ++;
            }
            $prev_row = $row;
        }
        

        foreach ($index as $term => $row) {
            $this->index->save_documents($term,$row["hits"],$row["posting"]);
        }
        
        $this->index->store_index();
        
    }

    function sort_terms(array $terms_table) {
        $term = array();
        foreach ($terms_table as $key => $row) {
            $term[$key] = $row['term'];
        }
        array_multisort($term, SORT_ASC, $terms_table);
        return $terms_table;
    }
    
    function remove_docs($docs){
        
    }

}
