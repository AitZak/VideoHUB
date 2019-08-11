<?php


namespace App\Manager;


use App\Repository\VideoRepository;

class VideoManager
{
    private $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function countVideosByCategory(int $categoryId)
    {
        $videos = $this->videoRepository->findBy(['category' => $categoryId]);
        return count($videos);
    }

}