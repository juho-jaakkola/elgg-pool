<?php

$lang = array(
	'pool' => 'Pool',
	'pool:all' => 'Pools',
	'pool:join' => 'Join',
	'pool:leave' => 'Leave',
	'pool:members:count' => '(%s members)',
	'pool:shift' => 'Shift',
	'pool:remove' => 'Remove',

	'pool:current:daily' => "Today's turn:",
	'pool:current:weekly' => "This week's turn:",
	'pool:current:monthly' => "This month's turn:",

	'pool:next:daily' => "Tomorrow's turn:",
	'pool:next:weekly' => "Next week's turn:",
	'pool:next:monthly' => "Next month's turn:",

	'pool:daily' => 'daily',
	'pool:weekly' => 'weekly',
	'pool:monthly' => 'monthly',

	// Messages
	'pool:join:success' => 'You have joined the pool',
	'pool:join:error' => 'Joining the list failed',
	'pool:leave:success' => 'You have successfully left the pool',
	'pool:leave:error' => 'Leaving the list failed',

	// Admin panel
	'admin:pool' => 'Administer',
	'admin:pool:list' => 'Pools',
	'admin:pool:save' => 'Create a new pool',
	'pool:interval' => 'Interval',
	'pool:interval:time' => 'Time',

	// Admin messages
	'pool:save:success' => 'Pool saved',
	'pool:save:error' => 'Cannot save pool',
	'pool:delete:success' => 'Pool deleted',
	'pool:error:cannot_delete_pool' => 'Cannot delete pool',
	'pool:error:pool_not_found' => 'Cannot find the pool',
	'pool:shift:success' => 'Pool order shifted successfully',
	'pool:remove:success' => 'User was successfully removed from the pool',
	'pool:error:cannot_remove' => 'Cannot remove user from the pool',
);

add_translation('en', $lang);