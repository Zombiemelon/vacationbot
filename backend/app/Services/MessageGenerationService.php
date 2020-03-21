<?php


namespace App\Services;
use Exception;

class MessageGenerationService
{
    public function getIncorrectDateMessage($language): string
    {
        if ($language == 'ru') {
            return "Пожалуйста выбери дату в формате 2020-04-06!📅";
        } else {
            return "Please select the date in format 2020-04-06!📅";
        }
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
        if ($language == 'ru') {
            return urlencode("Отлично! Наслаждайся фотографиями каждый день!😀");
        } else {
            return urlencode("Great! Enjoy the photos every day!😀");
        }
    }

    /**
     * @param string $language
     * @return string
     */
    public function getHelpMessage(string $language): string
    {
        if ($language == 'ru') {
            return urlencode("Посмотри, что я могу делать:\n\n/vacation {Место} {Дата вылета} - выбери место (на английском), куда ты отправишься, и получай фото каждый день до вылета (Например, /vacation Bali 2020-04-17\n\n/trips - проверь, какие места уже у тебя в списке\n\n/stop {Место} {Дата вылета} - останови отправку фото (Например, /stop Bali 2020-04-17)\");");
        } else {
            return urlencode("Check what I can do:\n\n/vacation {Destination} {Date of departure} - choose the place where you go and date when you will depart to receive daily photo (For example, /vacation Bali 2020-04-17)\n\n/trips - check what trips you already have\n\n/stop {Destination} {Date of departure} - stop receiving photos (For example, /stop Bali 2020-04-17)");
        }
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
        if ($language == 'ru') {
            return "Ты уже получаешь такие фото 👯‍♂️";
        } else {
            return "Vacation already exists 👯‍♂️";
        }
    }

    /**
     * @param string $language
     * @return string
     */
    public function getDontUnderstandMessage(string $language): string
    {
        if ($language == 'ru') {
            return "Я тебя не понимаю🤷‍♂️ Попробуй /vacation, чтобы выбрать куда полетишь.";
        } else {
            return "I don't understand you🤷‍♂️ Try /vacation to choose your destination.";
        }
    }

    /**
     * @param string $language
     * @return string
     */
    public function getSelectDateMessage(string $language): string
    {
        if($language == 'ru') {
            return "Отлично! Теперь выбери дату в формате 2020-04-06!📅";
        }
        return "Great! Now select the date in format 2020-04-06!📅";
    }

    public function getGreetingMessage(string $language) :string
    {
        if ($language == 'ru') {
            return urlencode("Привет👋\nЯ буду отправлять тебе фото с места твоего отпуска ежедневно до даты вылета в 9 утра!\nПросто напиши /vacation.");
        } else {
            return urlencode("Hi👋\nI will send photo of your vacation destination every day until the flight at 9am!\nJust write /vacation.");
        }
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
