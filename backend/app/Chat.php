<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent;

/**
 * Class Chat
 * @package App
 * @mixin Eloquent\
 */
class Chat extends Model
{
    protected $fillable = ['telegram_chat_id', 'chat_status_id'];

    public function status()
    {
        return $this->hasOne('App\ChatStatus', 'id', 'chat_status_id');
    }

    public function vacations()
    {
        return $this->hasMany('App\Vacation', 'chat_id', 'telegram_chat_id');
    }

    /**
     * @param int $status
     */
    public function updateState(int $status): void
    {
        $this->chat_status_id = $status;
        $this->save();
    }
}
