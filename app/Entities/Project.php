<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $fillable = [

		'owner_id', //chave estrangeira para users
		'client_id', //chave estrangeira para clients
		'name',
		'description',
		'progress',
		'status',
		'due_date'
	]; 

	
	public function notes()
	{
		return $this->hasMany(ProjectNote::class);
	}

	public function client() {
		return $this->belongsTo(Client::class); 
	}

	public function owner() {
		return $this->belongsTo(User::class); 
	}

	public function members()
	{
		return $this->belongsToMany(User::class,'project_members', 'project_id', 'member_id');
	}

	public function files()
	{
		return $this->hasMany(ProjectFile::class);
	}

}
