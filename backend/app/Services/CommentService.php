<?php


namespace App\Services;


use DateTime;

class CommentService
{
    public function getComment(string $destination, string $vacation_date): string
    {
        $datetime1 = new DateTime(today());
        $datetime2 = new DateTime($vacation_date);
        $interval = $datetime1->diff($datetime2);
        $caption = $interval->days;
        $destinationComment = "You are going to $destination in";
        $caption = $caption > 1 ? $caption .= ' days!✈️' : $caption .= ' day!🥳✈️';
        return "$destinationComment $caption";
    }
}
