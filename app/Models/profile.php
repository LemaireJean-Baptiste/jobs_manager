<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile Extends Model{

	protected $table = "profiless";

	protected $fillable = [
		'username',
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