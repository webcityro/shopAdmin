<?php

function renderRating($rating) {
	$rating = round($rating);
	$return = '<div class="ratingDisplay">';
	for ($x=1; $x < 6; $x++) {
		$return .= '<img src="'.config::get('site/domain').'app/public/styles/'.config::get('site/style').'/'.'images/star'.(($x <= $rating && $x > 0) ? 'On' : 'Alpha').'.png" alt="Steaua '.$x.'">';
	}
	$return .= '</div>';
	return $return;
}