<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatStatus extends Model
{
    public const SELECT_DATE = 2;
    public const COMPLETED = 3;
    public const STOP = 4;

    public function chats()
    {
        return $this->hasMany('App\Chat', 'chat_status_id', 'id');
    }
}
