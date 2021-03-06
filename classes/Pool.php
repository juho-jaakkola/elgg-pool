<?php
/**
 * Manage pool of people and scheduled tasks for them.
 *
 * @property integer $interval      The pool interval (day, week, month)
 * @property integer $interval_time The pool interval time (weekday or month name)
 */
class Pool extends ElggObject {
	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "task_pool";
	}

	/**
	 * Get the next turn using the given time as starting point
	 *
	 * @param int $start_time Timestamp of the starting point
	 * @return int $time Timestamp of the next turn
	 */
	public function getNextTurnFromTime($start_time) {
		// Get the next turn using the given time as a starting point
		$time = strtotime("next {$this->interval_time}", $start_time);

		// Add the time of day as seconds
		$time += strtotime("1970-01-01 {$this->time}:00 UTC");

		return $time;
	}

	/**
	 * Shift the pool one step forward. First person will become last.
	 *
	 * @return boolean
	 */
	public function shift ($force = false) {
		$first_turn = $this->getFirstTurn();

		// Do not shift the queue until the assigned time has passed
		if (time() < $first_turn->value && !$force) {
			return false;
		}

		$first_user = $first_turn->getOwnerEntity();

		// This turn has been completed so it can be deleted
		$deleted = $first_turn->delete();

		$turns = $this->getTurnsNowAndAfter();

		// Start from the next available time
		$new_time = $this->getNextTurnFromTime(time());

		$ia = elgg_set_ignore_access(true);

		// Advance all turns
		foreach ($turns as $turn) {
			if ($this->annotate('pool_turn', $new_time, $this->access_id, $turn->getOwnerGUID())) {

				// The old turn can now be deleted
				if (!$turn->delete()) {
					$success = false;
				}

				// Increase the last assigned time by one interval
				$new_time = $this->getNextTurnFromTime($new_time);
			}
		}

		// Append the removed user to the end of the queue
		$added = $this->annotate('pool_turn', $new_time, $this->access_id, $first_user->guid);

		elgg_set_ignore_access($ia);

		return true;
	}

	/**
	 * Join the pool
	 *
	 * Adds the user to the pool, assigns her as the next in turn.
	 * All other turns are postponed.
	 *
	 * @param int $user_guid
	 * @return boolean
	 */
	public function join ($user_guid = null) {
		if ($user_guid == null) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

		$turns = $this->getTurnsNowAndAfter();

		// Postpone existing turns
		foreach ($turns as $turn) {
			// Increase each turn by one interval (day, week, month)
			$new_time = $this->getNextTurnFromTime($turn->value);

			if ($this->annotate('pool_turn', $new_time, $this->access_id, $turn->getOwnerGUID())) {
				$ia = elgg_set_ignore_access(true);

				// The old turn can now be deleted
				$turn->delete();

				elgg_set_ignore_access($ia);
			}
		}

		// Add new member as the next in turn
		$time = $this->getNextTurnFromTime(time());
		$result = $this->annotate('pool_turn', $time, $this->access_id, $user_guid);

		if ($result) {
			return add_entity_relationship($user_guid, 'member', $this->guid);
		} else {
			return false;
		}
	}

	/**
	 * Leave the pool
	 *
	 * @param int $user_guid
	 * @return boolean
	 */
	public function leave ($user_guid = null) {
		if ($user_guid == null) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

		$success = true;

		$next_turn = $this->getUsersNextTurn($user_guid);

		if ($next_turn) {
			$next_turn_time = $next_turn->value;
			if ($next_turn->delete()) {
				// Rearrange the queue starting from the deleted user's turn
				$turns = $this->getTurnsNowAndAfter($next_turn_time);

				$ia = elgg_set_ignore_access(true);

				// Advance existing turns
				foreach ($turns as $turn) {
					// Decrease each turn by one interval (day, week, month)
					$new_time = $this->getNextTurnFromTime($turn->value);

					if ($this->annotate('pool_turn', $new_time, $this->access_id, $turn->getOwnerGUID())) {

						// The old turn can now be deleted
						if (!$turn->delete()) {
							$success = false;
						}
					}
				}

				elgg_set_ignore_access($ia);
			} else {
				$time = date('Y-m-d', $next_turn_time);
				register_error("Failed to delete turn $time from user $user_guid");
			}
		}

		return remove_entity_relationship($user_guid, 'member', $this->guid);
	}

	/**
	 * Check if user is member of the pool.
	 *
	 * @param int $user_guid
	 * @return boolean
	 */
	public function isMember($user_guid = null) {
		if ($user_guid == null) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

		return check_entity_relationship($user_guid, 'member', $this->guid);
	}

	/**
	 * Get all pool members
	 *
	 * @param array $options
	 * @return ElggUser[]
	 */
	public function getMembers($options = array()) {
		$defaults = array(
			'type' => 'user',
			'relationship' => 'member',
			'relationship_guid' => $this->guid,
			'inverse_relationship' => true,
			'limit' => 10,
		);

		$options = array_merge($defaults, $options);

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * Count the amount of members
	 *
	 * @return int
	 */
	public function countMembers() {
		return $this->getMembers(array('count' => true));
	}

	/**
	 * Get pool turns that are scheduled at the given time and after it.
	 *
	 * @param string $time Unix timestamp
	 * @return array
	 */
	public function getTurnsNowAndAfter($time = null) {
		if ($time == null) {
			$time = time();
		}

		return elgg_get_annotations(array(
			'guid' => $this->guid,
			'metastring_names' => 'pool_turn',
			'wheres' => array("v.string >= $time"),
			'limit' => false,
			'order_by' => 'v.string asc',
		));
	}

	/**
	 * Get user's next turn
	 *
	 * @param int $user_guid
	 * @return ElggAnnotation|boolean
	 */
	public function getUsersNextTurn($user_guid = null) {
		if ($user_guid == null) {
			$user_guid == elgg_get_logged_in_user_guid();
		}

		$result = elgg_get_annotations(array(
			'annotation_owner_guid' => $user_guid,
			'metastring_names' => 'pool_turn',
			'guid' => $this->guid,
			'limit' => 1,
		));

		if (isset($result[0])) {
			return $result[0];
		} else {
			return false;
		}
	}

	/**
	 * Get the first turn in the queue.
	 *
	 * @return ElggUser
	 */
	public function getFirstTurn() {
		$turn = elgg_get_annotations(array(
			'guid' => $this->guid,
			'metastring_names' => 'pool_turn',
			'order_by' => 'v.string asc',
			'limit' => 1,
		));

		return $turn[0];
	}
}