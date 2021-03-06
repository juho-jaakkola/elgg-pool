<?php

return array(
	'pool' => 'Pool',
	'pool:all' => 'Pools',
	'pool:join' => 'Join',
	'pool:leave' => 'Leave',
	'pool:members:count' => '(%s members)',
	'pool:shift' => 'Shift',
	'pool:remove' => 'Remove',
	'pool:list:title' => '%s %s',
	'item:object:task_pool' => 'Task Pools',

	'pool:current:daily' => "Today's turn:",
	'pool:current:weekly' => "This week's turn:",
	'pool:current:monthly' => "This month's turn:",

	'pool:next:daily' => "Tomorrow's turn:",
	'pool:next:weekly' => "Next week's turn:",
	'pool:next:monthly' => "Next month's turn:",

	'pool:daily' => 'daily',
	'pool:weekly' => 'weekly',
	'pool:monthly' => 'monthly',

	// Notifications
	'notifier:notify:daily:subject' => 'Your turn tomorrow: %s',
	'notifier:notify:weekly:subject' => 'Your turn next week: %s',
	'notifier:notify:monthly:subject' => 'Your turn next month: %s',
	'notifier:notify:daily:body' => 'Tomorrow it is your turn in the pool "%s".

%s
',
	'notifier:notify:weekly:body' => 'Next week it is your turn in the pool "%s"

%s
',
	'notifier:notify:monthly:body' => 'Next month it is your turn in the pool "%s"

%s
',

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
	'pool:time' => 'Time',
	'pool:time:help' => 'Use format HH:MM',

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
