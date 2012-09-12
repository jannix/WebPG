<?php
class user
{
	protected	$data = array();

	public function	user($id = 0)
	{
		global $db;
		
		if ($id != 0)
		{
			$sql = "SELECT *
				FROM users
				WHERE id = " . (int)$id;
			$result = $db->query($sql);
			if ($row = $db->fetch_assoc($result))
				$this->data = $row;
		}
		else
			$this->data = array('id' => $id);
	}
	
	public function	update($upd)
	{
		global $db;

		$set = array();
		if ($upd & UPD_LAST_TIME || $upd == UPD_ALL)
		{
			$set[] = 'last_time = ' . time();
		}
		if ($upd & UPD_LAST_MESSAGEID || $upd == UPD_ALL)
		{
			$sql = "SELECT id
				FROM chat_messages
				ORDER BY id DESC
				LIMIT 0, 1";
			$result = $db->query($sql);
			$message = $db->fetch_assoc($result);
			$set[] = 'last_messageid = ' . (int)$message['id'];
		}
		$sql = 'UPDATE users
			SET ' . implode(',', $set) . '
			WHERE id = ' . $this->data['id'];
		$db->query($sql);
	}

	public function	get_last_messageid()
	{
		return $this->data['last_messageid'];
	}
	
	public function	get_last_time()
	{
		return $this->data['last_time'];
	}

	public function	set_last_time($time = -1)
	{
		global $db;
		
		if ($time == -1)
			$time = time();
		$sql = "UPDATE users
			SET last_time = " . $time . "
			WHERE id = " . $this->data['id'];
		if ($db->query($sql))
		{
			$this->data['last_time'] = $time;
			return true;
		}
		return false;
	}
	
	public function get_id()
	{
		return $this->data['id'];
	}
}
?>