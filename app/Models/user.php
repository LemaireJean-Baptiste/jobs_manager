<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User Extends Model{

	protected $table = "users";

	protected $fillable = [
		'fname',
		'lname',
		'email',
		'password',
		'permission'
	];

	public function setPassword($password){

		$this->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}

}
?>