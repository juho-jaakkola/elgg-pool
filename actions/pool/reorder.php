<?php
/**
 * Reorders pool participants to the given order
 */

$guid = get_input('guid');
$user_guids = get_input('guids');

$pool = get_entity($guid);

$first_turn = $pool->getFirstTurn();

$count = 0;
foreach ($user_guids as $user_guid) {
	$turn = $pool->getUsersNextTurn($user_guid);

	if ($count == 0) {
		// Next possible occasion
		$time = $pool->getNextTurnFromTime(time());
	} else {
		// One interval later than the previous member
		$time = $pool->getNextTurnFromTime($time);
	}

	// Save the updated time
	// TODO Skip user if time hasn't changed
	$turn->value = $time;
	$turn->save();

	$count++;
}
