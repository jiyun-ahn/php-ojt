<?php

function uuidgen() {
	$id = sprintf('%08x%04x%04x%04x%04x%08x',
			mt_rand(0, 0xffffffff),
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
			);
	
	return strtoupper($id);
}