<?php


namespace App\Services;
use Exception;

class MessageGenerationService
{
    public function getIncorrectDateMessage($language): string
    {
        return "Please select the date in format 2020-04-06!📅";
    }

    public function getIncorrectDestinationMessage(Exception $exception): string
    {
        $message = "Something went wrong🤷‍♂️ Click /vacation to start again!";
        if($exception->getMessage() == "[\"No photos found.\"]") {
            $message = 'No such place😢 Try another place. For example, Moscow';
        }
        return $message;
    }

    public function getVacationReplyMessage(string $language) :string
    {
        return "Great! Enjoy the photos every day!😀";
    }

    /**
     * @param string $language
     * @return string
     */
    public function getHelpMessage(string $language): string
    {
        return "Check what I can do:\n\n/vacation {Destination} {Date of departure} - choose the place where you go and date when you will depart to receive daily photo (For example, /vacation Bali 2020-04-17)\n\n/trips - check what trips you already have\n\n/stop {Destination} {Date of departure} - stop receiving photos (For example, /stop Bali 2020-04-17)";
    }

    /**
     * @param string $language
     * @return string
     */
    public function getEnjoyPhotoMessage(string $language): string
    {
        if ($language == 'ru') {
            return "Отлично! Теперь ты можешь наслаждаться фотографиями каждый день!✈️";
        } else {
            return "Great! Now you can enjoy the photos every day!✈️";
        }
    }

    /**
     * @param string $language
     * @return string
     */
    public function getChooseDestinationMessage(string $language): string
    {
        if ($language == 'ru') {
            return "Выбери, куда ты полетишь ✈️";
        } else {
            return 'Choose your destination ✈️';
        }
    }

    /**
     * @param string $language
     * @return string
     */
    public function getVacationExistsMessage(string $language): string
    {
        return "Vacation already exists 👯‍♂️";
    }

    /**
     * @param string $language
     * @return string
     */
    public function getDontUnderstandMessage(string $language): string
    {
        return "I don't understand you🤷‍♂️ Try /vacation to choose your destination.";
    }

    /**
     * @param string $language
     * @return string
     */
    public function getSelectDateMessage(string $language): string
    {
        return "Great! Now select the date in format 2020-04-06!📅";
    }

    public function getGreetingMessage(string $language) :string
    {
            return urlencode("Hi👋\nI will send photo of your vacation destination every day until the flight at 9am!\nJust write /vacation.");
    }

    public function getNotSentMessage(): string
    {
        return "Message hasn't been sent";
    }

    /**
     * @return string
     */
    public function getStopMessage(): string
    {
        return "Choose destination to stop notifications 🙅‍♂️";
    }

    /**
     * @param string $destination
     * @param string $date
     * @return string
     */
    public function getDeleteMessage(string $destination, string $date): string
    {
        return "$destination $date deleted";
    }

    /**
     * @param string $destination
     * @param string $date
     * @return string
     */
    public function getTripMessage(string $destination, string $date): string
    {
        return "$destination $date";
    }

    public function getEmptyStopMessage(): string
    {
        return "Such vacation doesn't exist🕵️‍♂️ Try to write in the following format <b>/stop Moscow 2020-01-01</b>";
    }
}
