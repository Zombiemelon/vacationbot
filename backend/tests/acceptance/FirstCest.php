<?php

use App\Services\TelegramBotService;

class FirstCest
{
    /** @var AcceptanceTester */
    private $I;

    public function _before(AcceptanceTester $I)
    {
        $this->I = $I;
    }

    /**
     * Check that user is registered and correct name is displayed in the private cabinet
     * @throws Exception
     */
    public function checkAmazon():void
    {
        $this->I->amOnPage('/PlayStation-Édition-Standard-DualSense-Couleur/dp/B08H93ZRK9/ref=sr_1_1?__mk_fr_FR=ÅMÅŽÕÑ&dchild=1&keywords=ps5&qid=1608145435&sr=8-1&fbclid=IwAR2dZA4SC-ZcVv8hR55pooJWMg1ntXkRHnHHmvxlX9fK9a_MOffXMyHcIbs');
        $this->I->see('Actuellement jjjindisponible.');
        }

    public function _failed(\AcceptanceTester $I) {
        $telegramBotService = new TelegramBotService();
        $telegramBotService->sendMessage(226061474, substr("Actuellement indisponible.", 0, 50));
    }
}
