<?php
class Youtube{

    private $myApiKey = "AIzaSyBwF-SlaLyFZE6BEKRmGZ-V3nR9bFWv9ME";
    private $myChannelID = "UCwWOrOUimjdNl5abPRiuCHg";

    public function getVideos(){
        $myQuery = "https://www.googleapis.com/youtube/v3/search?key=$this->myApiKey&channelId=$this->myChannelID&part=snippet,id&order=date";
        $videoList = file_get_contents($myQuery);
        $decoded = json_decode($videoList, true);
        $results = $decoded['pageInfo']['totalResults'];
        $myQuery = "https://www.googleapis.com/youtube/v3/search?key=$this->myApiKey&channelId=$this->myChannelID&part=snippet,id&order=date&maxResults=$results";
        $videoList = file_get_contents($myQuery);
        return json_decode($videoList, true);
    }
}
?>