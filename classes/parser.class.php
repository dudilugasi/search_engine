<?php

include_once 'expressions.php';

class parser {

    const operators_dictionary = array("(", ")", "and", "or", "not");

    function infix_to_rpn($tokens) {
        $out_q = new SplQueue();
        $stack = new SplStack();

        $index = 0;

        while (count($tokens) > $index) {
            $t = $tokens[$index];
            switch ($t) {
                case (!in_array($t, self::operators_dictionary)):
                    $out_q->enqueue($t);
                    break;
                case ($t == "not"):
                case ($t == "and"):
                case ($t == "or"):
                    $stack->push($t);
                    break;
                case ($t == "("):
                    $stack->push($t);
                    break;
                case ($t == ")"):
                    while ($stack->top() != "(") {
                        $out_q->enqueue($stack->pop());
                    }
                    $stack->pop();
                    if ($stack->count() > 0 && $stack->top() == "not") {
                        $out_q->enqueue($stack->pop());
                    }
                    break;
                default :
                    break;
            }
            ++$index;
        }
        while ($stack->count() > 0) {
            $out_q->enqueue($stack->pop());
        }

        $reversed_q = array();
        foreach ($out_q as $value) {
            $reversed_q[] = $value;
        }
        return array_reverse($reversed_q);
    }

    static function create_tree(ArrayIterator &$it) {

        if (!in_array($it->current()["term"], self::operators_dictionary)) {
            $leaf = new leaf($it->current());
            $it->next();
            return $leaf;
        } else {
            if ($it->current()["term"] == "not") {
                $it->next();

                $op = self::create_tree($it);
                return new notEx($op);
            } else if ($it->current()["term"] == "and") {
                $it->next();

                $left = self::create_tree($it);
                $right = self::create_tree($it);
                return new andEx($left, $right);
            } else if ($it->current()["term"] == "or") {
                $it->next();

                $left = self::create_tree($it);
                $right = self::create_tree($it);
                return new orEx($left, $right);
            }
        }
        return null;
    }

}
