<?php

abstract class expression {

    abstract protected function evaluate();
}

class leaf extends expression {

    private $term;

    public function __construct($term) {
        $this->term = $term;
    }

    public function evaluate() {  
        return ($this->term) ? $this->term["posting"] : false;
    }

}

class notEx extends expression {

    protected $op;

    public function __construct(expression $op) {
        $this->op = $op;
    }

    public function evaluate() {
        global $all_docs;
        $docs = $this->op->evaluate();
        if (!$docs) {
            return array();
        }
        $diff = array_diff($all_docs, $docs);
        return $diff;
    }

}

class andEx extends expression {

    protected $left;
    protected $right;

    public function __construct(expression $left, expression $right) {
        $this->left = $left;
        $this->right = $right;
    }

    public function evaluate() {
        $l = $this->left->evaluate();
        $r = $this->right->evaluate();
        if (!$l && $r) {
            $l = $r;
        }
        elseif ($l && !$r) {
            $r = $l;
        }
        elseif (!$l && !$r) {
            $l = $r = array();
        }
        
        $intersect = array_intersect($l,$r);
        return $intersect;
    }

}

class orEx extends expression {

    protected $left;
    protected $right;

    function __construct(expression $left, expression $right) {
        $this->left = $left;
        $this->right = $right;
    }

    public function evaluate() {
        $l = $this->left->evaluate();
        $r = $this->right->evaluate();
        if (!$l) {
            $l = array();
        }
        if (!$r) {
            $r = array();
        }
        $merged = $l + $r;
        return $merged;
    }

}
