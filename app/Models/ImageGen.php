<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageGen extends Model
{
    use HasFactory;

	protected $fillable = [
		'session_id',
		'user_id',
		'user_prompt',
		'llm_prompt',
		'image_prompt',
		'image_path',
		'llm',
		'prompt_tokens',
		'completion_tokens',
	];

}
