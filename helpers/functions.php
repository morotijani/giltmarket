<?php 


// Density calculation
function calculateDensity($gram, $volume) {
	$density = ($gram / $volume);

	return round_to_decimal_place(2, $density);
}

// Density calculation
function calculatePounds($gram) {
	$pounds = ($gram / FIXED_POUNDS_FIGURE);

	return round_to_decimal_place(2, $pounds);
}

// Carat calculation
function calculateCarat($gram, $volume) {
	$density = calculateDensity($gram, $volume);

	$carat = (($density - FIXED_CARAT_FIGURE_1) * (FIXED_CARAT_FIGURE_2 / $density));
	return round_to_decimal_place(2, $carat);
}

// Total amount calculation
function calculateTotalAmount($gram, $volume, $current_price) {
	$carat = calculateCarat($gram, $volume);
	$pounds = calculatePounds($gram);

	$total_amount = (($carat * $current_price) / (FIXED_TOTAL_FIGURE / $pounds));
	return round_to_decimal_place(2, $total_amount);
}

function round_to_decimal_place($decimal_place, $figure) {
	return number_format((float)$figure, $decimal_place, '.', '');
}
