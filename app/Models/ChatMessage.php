<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ChatMessage extends Model
	{
		use HasFactory;

		protected $fillable = [
			'session_id',
			'role',
			'message',
			'llm',
			'prompt_tokens',
			'completion_tokens',
		];
	}
