<?php
namespace Algorithm;


abstract class Combination {
    
    private static function flattenArray($entries){
        $flattenArray = array();
        
        $nbEntries = count($entries);
        // Join trees by adding a common root
        $flattenArray[0] = array('value' => '', 'level' => 0, 'next' => 1, 'offset' => 0, 'next_sibling' => null);
        $flattenOffset = 1;
        for($i=0;$i<$nbEntries;$i++){
            // An entry is a final array which contains elements
            $nbElements = count($entries[$i]);
            for($j=0;$j<$nbElements;$j++){
                $nextOffset = ($i === $nbEntries-1) ? null : $flattenOffset + $nbElements - $j;
                $nextSibling = ($nbElements-1 !== $j) ? ($flattenOffset + 1) : null;
                $flattenArray[$flattenOffset++] = array('offset' => $flattenOffset, 'value' => $entries[$i][$j], 'next' => $nextOffset, 'next_sibling' => $nextSibling);
            }
        }
        return $flattenArray;
    }

    public static function combinations(array $entries){
        // Combinations cache
        static $cache = array();
        // Flatten array and build pseudo n-ary tree structure
        $flattenArray = self::flattenArray($entries);
        $hashcode = "";
        array_walk($flattenArray, function($v, $k) use (&$hashcode) {
            $hashcode .= $v['value'];
        });
        $hashcode = sha1($hashcode);
        if(isset($cache[$hashcode])){
            return $cache[$hashcode];
        }
        $combinations = array();
        // Traverse n-ary tree from left to right in-order (current node first)
        $stack = array();
        $node = $flattenArray[0];
        $done = 0;
        // Traversal callbacks
        // traverse node from left to right
        $path = array();
        while(!$done){
            // We have no more node to process for this node
            if($node !== null) {
                //echo "Node = {$node['value']}\n";
                $path[] = $node['value'];
                $stack[] = $node;
                $node = @$flattenArray[$node['next']];
                if(!isset($node) || $node === null){
                    // Next node is null, we have to pop from stack
                }
            }
            else {
                if(count($stack) === 0){
                    if(count($path) !== 0){
                        $combinations[] = $path;
                        $path = array();
                    }
                    // End of traversal
                    $cache[$hashcode] = $combinations;
                    $done = 1;
                }
                else {
                    // When poped from the stack set current node to next sibling if available
                    $node = array_pop($stack);
                    $node = isset($flattenArray[$node['next_sibling']])?$flattenArray[$node['next_sibling']] : null;
                    if($node){
                        if(count($path) !== 0){
                            $combinations[] = $path;
                            $path = array();
                        }
                        $nbStackEl = count($stack);
                        for($k=0;$k<$nbStackEl;$k++){
                            $path[] = $stack[$k]['value'];
                        }
                    }
                }
            }
        }
        return $combinations;
    }
}

