<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'task_pool')) {
	$body_vars = pool_prepare_form_vars($entity);
} else {
	$body_vars = pool_prepare_form_vars();
}

echo elgg_view_form('pool/admin/save', array(), $body_vars);
