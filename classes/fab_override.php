<?php

function _fab( $str ) {
	return __( $str, 'formidable_action_before' );
}

function _e_fab( $str ) {
	_e( $str, 'formidable_action_before' );
}