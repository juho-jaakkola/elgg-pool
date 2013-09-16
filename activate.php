<?php

if (get_subtype_id('object', 'task_pool')) {
	update_subtype('object', 'task_pool', 'Pool');
} else {
	add_subtype('object', 'task_pool', 'Pool');
}
