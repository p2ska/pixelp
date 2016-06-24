<?php

class PIXELP_IMPORT {
	var $db, $folder = false;

	function pixelp_import($db) {
		$this->db = $db;
	}

	// mis failid on impordikaustas?

	function add_queue($folder = false) {
		if (!$folder)
			$this->init_folder();
		else
			$this->folder = $folder;

		$dir = @opendir(ROOT_PATH. IMPORT_PATH);

		if (!$this->folder || !$dir)
			return false;

		while ($file = readdir($dir)) {
			if ($file[0] != ".") {
				$path = ROOT_PATH. IMPORT_PATH. $file;

				if (!is_dir($path))
					$this->db->query("insert into import_queue (file, folder, added) values (?, ?, now())", [ $path, $this->folder ]);
			}
		}
	}

	// impordi ootel olev fail

	function process_queue() {
		$this->db->query("select id, file, folder from import_queue where status = ? order by id limit 1", QUEUE_WAITING);
		$photo = $this->db->get_obj();

		// impordi ootel olevad failid

		if ($photo && file_exists($photo->file)) {
			$type = exif_imagetype($photo->file);

			// lubatud on hetkel vaid jpg & png (todo: videofailid?)

			if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
				// loo uus pildikirje

				$uid = $this->init_photo($photo->folder);

				if ($uid) {
					$photo_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $uid;

					$this->update_photo($uid, $photo->file);

					if (@rename($photo->file, $photo_path)) {
						$this->db->query("update import_queue set status = ? where id = ?", [ QUEUE_SUCCESS, $photo->id ]);

						$this->resize_photo($uid, 1);
					}
				}
				else {
					echo "cant get uid";
				}
			}
		}
	}

	// uuenda foto infot

	function update_photo($uid, $path) {
		if (file_exists($path)) {
			$this->db->query("select * from photos where id = ?", $uid);

			if ($this->db->rows) {
				$photo = $this->db->get_obj();
				$exif = exif_read_data($path);

				if (isset($exif["DateTimeOriginal"])) {
					list($date, $time) = explode(" ", $exif["DateTimeOriginal"]);
					$photo->shoot_date = str_replace(":", "-", $date). " ". $time;
				}

				$this->db->query("update photos set shoot_date = ?, exif = ?, changed = now() where id = ?",
					[ $photo->shoot_date, json_encode($exif), $uid ]);
			}
		}
	}

	function resize_photo($uid, $version, $from_resized = false) {
		$this->db->query("select * from photos where id = ?", $uid);

		if ($this->db->rows) {
			$photo = $this->db->get_obj();

			if (!$from_resized)
				$original_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $photo->id;
			else
				$original_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $photo->id. "_". $from_resized;

			$resized_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $photo->id. "_". $version;

			if (file_exists($original_path)) {
				$info = getimagesize($original_path);
				$original_img = imagecreatefromjpeg($original_path);

				$resized_width = 800;
				$resized_height = 600;

				$resized_img = imagescale($original_img, $resized_width, $resized_height,  IMG_BICUBIC_FIXED);

				imagejpeg($resized_img, $resized_path);
				imagedestroy($original_img);
				imagedestroy($resized_img);

				$this->db->query("select id from resizes where photo_id = ? && version = ?", [ $uid, $version ]);

				if ($this->db->rows) {
					$obj = $this->db->get_obj();

					$this->db->query("update resizes set width = ?, height = ?, changed = now() where id = ?",
						[ $resized_width, $resized_height, $obj->id ]);
				}
				else
					$this->db->query("insert into resizes (photo_id, version, width, height, changed, created) values (?, ?, ?, ?, now(), now())",
						[ $uid, $version, $resized_width, $resized_height ]);

				return true;
			}
		}

		return false;
	}

	// uus pildikirje

	function init_photo($folder) {
		$count = 0;

		// proovi lisada uus kirje

		while ($count++ < UID_TRIES) {
			$uid = $this->generate_uid();

			echo $uid. " [". $folder. "]<br/>";

			$this->db->query("insert into photos (id, folder, changed, created) values (?, ?, now(), now())", [ $uid, $folder ]);

			// uue elemendi lisamine õnnestus

			if ($this->db->error == 0)
				return $uid;
		}

		return false;
	}

	// uus importkataloog

	function init_folder() {
		$count = 0;

		// proovi lisada uus kaust

		while ($count++ < UID_TRIES) {
			$uid = $this->generate_uid();
			$folder = ROOT_PATH. STORAGE_PATH. $uid;

			// kas õnnestus uue kausta lisamine?

			if (@mkdir($folder)) {
				$this->folder = $uid;

				return $uid;
			}
		}

		return false;
	}

	// genereeri uid, etteantud karakterite järgi

	function generate_uid($length = UID_LENGTH) {
		$result = "";

    	$len = strlen(UID_CHARS);

    	for ($a = 0; $a < $length; $a++)
        	$result .= UID_CHARS[rand(0, $len - 1)];

		return $result;
	}
}

?>
