<?php

namespace Env\Aplication\API;




class File extends Controller
{

	protected $allowAllCallbacks = true;
	
	public function before_action()
	{
		//parent::before_action();

	}

	public function upload()
	{
		$upload_handler = new \Blueimp\fileuploader\UploadHandler(
			array(
				'upload_dir' => ROOT . DS . 'files' . DS
			)
		);

	}
	
}