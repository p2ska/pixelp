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

		$this->db->query("delete from import_queue");

		while ($file = readdir($dir)) {
			if ($file[0] != ".") {
				$path = ROOT_PATH. IMPORT_PATH. $file;

				// hetkel lisame kõik kohe QUEUE_WAITING, ehk täitmiseks

				if (!is_dir($path))
					$this->db->query("insert into import_queue (file, folder, added, status) values (?, ?, ?, ?)", [ $path, $this->folder, date(SQL_DATETIME), QUEUE_WAITING ]);
			}
		}
	}

	// impordi ootel olev fail

	function process_queue() {
		$this->db->query("select id, file, folder from import_queue where status = ? order by id limit 1", QUEUE_WAITING);
		$photo = $this->db->get_obj();

		if ($photo && file_exists($photo->file)) {

			$type = exif_imagetype($photo->file);

			// lubatud on hetkel vaid jpg & png (todo: videofailid?)

			if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
				// loo uus pildikirje

				$id = $this->init_photo($photo->folder);

				if ($id) {
					$photo_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $id;

					$this->update_photo($id, $photo->file);

					if (@rename($photo->file, $photo_path)) {
						$this->db->query("update import_queue set status = ? where id = ?", [ QUEUE_SUCCESS, $photo->id ]);

						$this->resize_photo($id, 1, 600, "height");
						$this->resize_photo($id, 2, 100, "square", 1);
					}
				}
				else {
					$this->db->query("update import_queue set status = ? where id = ?", [ QUEUE_FAILURE, $photo->id ]);

					return false;
				}
			}
		}

		return true;
	}

	// uuenda foto infot

	function update_photo($id, $path) {
		if (file_exists($path)) {
			$this->db->query("select * from photos where id = ?", $id);

			if ($this->db->rows) {
				$photo = $this->db->get_obj();
				$exif = exif_read_data($path);

				if (isset($exif["DateTimeOriginal"])) {
					list($date, $time) = explode(" ", $exif["DateTimeOriginal"]);
					$photo->shoot_date = str_replace(":", "-", $date). " ". $time;
				}

				$this->db->query("update photos set shoot_date = ?, exif = ?, changed = now() where id = ?",
					[ $photo->shoot_date, json_encode($exif), $id ]);
			}
		}
	}

	// pildi skaleerimine

	function resize_photo($id, $version, $max, $side, $from_resized = false) {
		$this->db->query("select * from photos where id = ?", $id);

		if ($this->db->rows) {
			$photo = $this->db->get_obj();

			// kui on juba vähendatud variant olemas, millest veel väiksem teha, siis kasuta seda võimalus - kiirem

			if ($from_resized)
				$source_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $photo->id. "_". $from_resized;
			else
				$source_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $photo->id;

			if (file_exists($original_path)) {
				$resized_path = ROOT_PATH. STORAGE_PATH. $photo->folder. "/". $photo->id. "_". $version;

				list($source_width, $source_height) = getimagesize($source_path);

				// mis külg on pikem ja mis on laiuse/kõrguse suhe

				if ($source_width >= $source_height)
					$long_side = "width";
				else
					$long_side = "height";

				$source_aspect = $source_width / $source_height;

				$resized_x = 0 $resized_y = 0;
				$source_x = 0; $source_y = 0;

				switch ($side) {1600x800 | width: 800x400
					case "width":
						$resized_width = $max;
						$resized_height = $source_height / ($source_width / $max);

						break;

					case "height":
						$resized_height = $max;
						$resized_width = $source_width / ($source_height / $max);

						break;

					case "square":
						$resized_width = $resized_height = $max;

						/*if ($long_side == "width") {
							$resized_x = $resized_width / 2;
							$resized_y = $resized_height
						}*/

						break;
					case "area":

						break;

					default:

						break;
				}

				//$resized_width = 800;
				//$resized_height = 600;

				$source_img = imagecreatefromjpeg($source_path);
				$resized_img = imagecreatetruecolor($resized_width, $resized_height);

				imagecopyresampled(
					$source_img, $resized_img,
					$resized_x, $resized_y, $source_x, $source_y,
					$resized_width, $resized_height, $source_width, $source_height
				);

				imagejpeg($resized_img, $resized_path);
				imagedestroy($source_img);
				imagedestroy($resized_img);

				$this->db->query("select id from resizes where photo_id = ? && version = ?", [ $id, $version ]);

				if ($this->db->rows) {
					$obj = $this->db->get_obj();

					$this->db->query("update resizes set width = ?, height = ?, changed = now() where id = ?",
						[ $resized_width, $resized_height, $obj->id ]);
				}
				else {
					$this->db->query("insert into resizes (photo_id, version, width, height, changed, created) values (?, ?, ?, ?, now(), now())",
						[ $id, $version, $resized_width, $resized_height ]);
				}

				return true;
			}
		}

		return false;
	}

	// uus pildikirje

	function init_photo($folder) {
		$count = 0;

		// proovi lisada uus kirje

		while ($count++ < ID_TRIES) {
			$id = $this->generate_id();

			echo $id. " [". $folder. "]<br/>";

			$this->db->query("insert into photos (id, folder, changed, created) values (?, ?, now(), now())", [ $id, $folder ]);

			// uue elemendi lisamine õnnestus

			if ($this->db->error == 0)
				return $id;
		}

		return false;
	}

	// uus importkataloog

	function init_folder() {
		$count = 0;

		// proovi lisada uus kaust

		while ($count++ < ID_TRIES) {
			$id = $this->generate_id();
			$folder = ROOT_PATH. STORAGE_PATH. $id;

			// kas õnnestus uue kausta lisamine?

			if (@mkdir($folder)) {
				$this->folder = $id;

				return $id;
			}
		}

		return false;
	}

	// genereeri id, etteantud karakterite järgi

	function generate_id($length = ID_LENGTH) {
		$result = "";

    	$len = strlen(ID_CHARS);

    	for ($a = 0; $a < $length; $a++)
        	$result .= ID_CHARS[rand(0, $len - 1)];

		return $result;
	}
}

?>
