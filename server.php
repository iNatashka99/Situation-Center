<?php
	header('Content-type:application/json; charset=utf-8');
	class Events 
	{
		public static function getEvent()
		{
			$num = 0;
			$events = array();
			$File = 'sourcefile.csv';
			if (!file_exists($File)) 
			{
				$f = fopen($File, 'x');
				fclose($f);
			}
			$f = fopen($File, 'r');
			while ( ($data=fgetcsv($f, 1000, ';')) !== FALSE)
			{
				$event = array();
				$event['time'] = $data[0];
				$event['description'] = $data[1];
				$event['comments'] = 'events/'.$data[2];
				$File ='events/'.$data[2];
				$comment = Events::getLastComment($File);
				$event['comment'] = $comment['text'];
				$event['comment_time'] = $comment['time'];
				$events[$num] = $event;
				$num = $num+1;
			}
			$events['num'] = $num;
			fclose($f);
			return $events;
		}
		public static function updateEvent()
		{
			$num = 0;
			$events = array();
			$File = 'sourcefile.csv';
			if (!file_exists($File)) 
			{
				$f = fopen($File, 'x');
				fclose($f);
			}
			$f = fopen($File, 'r');
			while ( ($data=fgetcsv($f, 1000, ';')) !== FALSE)
			{
				$event = array();
				$event['time'] = $data[0];
				$event['description'] = $data[1];
				$event['comments'] = 'events/'.$data[2];
				$File ='events/'.$data[2];
				$comment = Events::getLastComment($File);
				$event['comment'] = $comment['text'];
				$event['comment_time'] = $comment['time'];
				$events[$num] = $event;
				$num = $num+1;
			}
			$events['num'] = $num;
			fclose($f);
			return $events;
		}
		public function getLastComment($Filename)
		{
			$File = $Filename;
			if (!file_exists($File)) 
			{
				$f = fopen($File, 'x');
				fclose($f);
			}
			$comm = array();
			$f = fopen($File, 'r');
			$comm['time'] = '';
			$comm['text'] = '';
			while (($data=fgetcsv($f, 1000, ';')) !== FALSE )
			{
				$comm['time'] = $data[0];
				$comm['text'] = $data[1];
			}
			fclose($f);
			return $comm;
		}
		public static function addComment()
		{
			$Id = User::getId();
			$File = $_GET['file'];
			$comment = $_GET['comm'];
			if ($comment !=='')
			{
				$f = fopen($File, 'a+');
				fwrite($f,date("d/m/y H:i:s").';"'.$comment.'";'.$Id.PHP_EOL);
				fclose($f);
			}
			return 1;
		}
		public function showComments()
		{
			$File = $_GET['file'];
			if (!file_exists($File)) 
			{
				$f = fopen($File, 'x');
				fclose($f);
			}
			$comm = array();
			$f = fopen($File, 'r');
			while (($data=fgetcsv($f, 1000, ';')) !== FALSE )
			{
				$comm[] = $data;
			}
			fclose($f);
			return $comm;
		}
	}
	class User
	{
		public static function getId()
		{
			$Id = 0;
			if (!isset($_COOKIE['user_id']) || $_COOKIE['user_id'] == '' || !preg_match("/^[a-z,0-9]*$/", $_COOKIE['user_id']))
			{
				$Id = md5(uniqid(rand(), 1));
				setcookie('user_id', $Id, time() + 60 * 60 * 24 * 30, '/');
			} else 
			{
				$Id = $_COOKIE['user_id'];
			}
			return $Id;
		}
		public static function getPin()
		{
			$Id = User::getId();
			$File = 'users/'.$Id.'.csv';
			if (!file_exists($File)) 
			{
				$f = fopen($File, 'x');
				fclose($f);
			}
			$pin = -1;
			$f = fopen($File, 'r');
			if (($data=fgetcsv($f)) !== FALSE)
			{
				$pin = (int)$data[0];
			}
			fclose($f);
			return $pin;
		}
		public static function setPin()
		{
			$Id = User::getId();
			$File = 'users/'.$Id.'.csv';
			$pin = $_GET['pin'];
			$f = fopen($File, 'w');
			fwrite($f,$pin);
			fclose($f);
			return 1;
		}
	}

	switch ($_GET['action'])
	{
    case 'getEvent':
        echo json_encode(Events::getEvent());
        break;
	case 'update':
        echo json_encode(Events::updateEvent());
        break;
	case 'addComment':
        echo Events::addComment();
        break;
	case 'showComments':
        echo Events::showComments();
        break;
	case 'getPin':
        echo User::getPin();
        break;
	case 'setPin':
        echo User::setPin();
        break;
    default:
		http_response_code(404);
        exit();
	}
?>

