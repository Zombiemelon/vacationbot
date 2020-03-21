<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = [
        'destination', 'date'
    ];

    public function chat()
    {
        return $this->belongsTo('App\Chat');
    }

    /**
     * @param $chat_id
     * @param $destination
     * @param $date
     * @return Vacation
     */
    public function getVacationByChatDestinationDate($chat_id, $destination, $date): ?Vacation
    {
        return self::where('chat_id',$chat_id)
            ->where('destination', $destination)
            ->where('vacation_date', $date)->first();
    }

    public function getAllVacationsByChatId(int $chat_id): Collection
    {
        return self::where('chat_id',$chat_id)->get();
    }

    /**
     * @param string $destination
     * @param int $chat_id
     */
    public function createInitialVacation(string $destination,int $chat_id): void
    {
        $this->destination = $destination;
        $this->chat_id = $chat_id;
        $this->vacation_date = '2020-01-01';
        $this->save();
    }
}
