<?php
/**
 * Reorders pool participants to the given order
 */

$guid = get_input('guid');
$user_guids = get_input('guids');

$pool = get_entity($guid);

$first_turn = $pool->getFirstTurn();

$map = array(
	'weekly' => 'week',
	'monthly' => 'month',
);

$count = 0;
foreach ($user_guids as $user_guid) {
	$turn = $pool->getUsersNextTurn($user_guid);

	if ($count == 0) {
		$time = strtotime("next {$pool->interval_time}");
	} else {
		$time = strtotime("+1 {$map[$pool->interval]}", $time);
	}

	// Save the updated time
	// TODO Skip user if time hasn't changed
	$turn->value = $time;
	$turn->save();

	$count++;
}
