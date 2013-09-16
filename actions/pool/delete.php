<?php
/**
 * Delete a pool
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'task_pool') && $entity->canEdit()) {
	if ($entity->delete()) {
		system_message(elgg_echo('pool:delete:success'));
		forward("admin/pool/list");
	} else {
		register_error(elgg_echo('pool:error:cannot_delete_pool'));
	}
} else {
	register_error(elgg_echo('pool:error:pool_not_found'));
}

forward(REFERER);