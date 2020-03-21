<?php


namespace App\Services;
use Crew\Unsplash;

class PhotoDownloadService
{
    private $applicationName = "vacation_bot";
    public function getPhotoByDestination(string $destination)
    {
        if(env('APP_ENV') == 'dev') {
            $photo = new \stdClass();
            $photo->links['html'] = "url";
            $photo->user['links']['html'] = "url";
            $photo->user['name'] = "name";
            return $photo;
        }

        Unsplash\HttpClient::init([
            'applicationId'	=> env('UNSPLASH_APP_ID'),
            'secret'		=> env('UNSPLASH_APP_SECRET'),
            'callbackUrl'	=> 'urn:ietf:wg:oauth:2.0:oob',
            'utmSource' => $this->applicationName
        ]);
        $filters = [
            'featured' => true,
            'query'    => $destination,
            'w'        => 100,
            'h'        => 100
        ];
        $photo = Unsplash\Photo::random($filters);
        return $photo;
    }

    public function getPhotoUrl($photo)
    {
        return $photo->links['html'];
    }

    public function getPhotographerUrl($photo)
    {
        return $photo->user['links']['html'];
    }

    public function getPhotographerName($photo)
    {
        return $photo->user['name'];
    }

    public function getUnsplashText()
    {
        $unsplashUrl = env('UNSPLASH_APP_URL');
        return "$unsplashUrl/?utm_source=$this->applicationName&utm_medium=referral";
    }

    public function getUnsplashLegalText($photo)
    {
        $photographerUrl = $this->getPhotographerUrl($photo);
        $photogrpaherName = $this->getPhotographerName($photo);
        $unsplashText = $this->getUnsplashText();
        return "\nPhoto by <a href=\"$photographerUrl\">$photogrpaherName</a> on <a href=\"$unsplashText\">Unsplash</a>";
    }
}
