<?php

class Epigra {

    public static function buildTree( $ar, $pid = 0) {
		$op = array();
		foreach( $ar as $item ) {
			if( $item->parent_id == $pid ) {
				$op[$item->id] = array(
					'id' => $item->id,
					'title' => $item->title,
					'parent_id' => $item->parent_id,
					'description' => $item->description,
					);
            // using recursion
				$children = self::buildTree( $ar, $item->id );
				if( $children ) {
					$op[$item->id]['children'] = $children;
				}
			}
		}
		return $op;
	}
}