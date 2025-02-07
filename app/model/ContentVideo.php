<?php
namespace App\model;
use App\config\DataBaseManager;

class ContentVideo extends AbstractContent {
    private ?string $url;
    private ?int $duration ;
    public function __construct(
        ?\config\DataBaseManager $db,
        ?int $id_content = null,
        ?int $id_course = null,
        ?string $title = null,
        ?string $type = null,
        ?string $url = null ,
        ?int $duration = 0
    ) {
        parent::__construct($db, $id_content, $id_course, $title, $type);
        $this->url= $url; 
        $this->duration = $duration;
    }
    public function setUrl(?string $url): self {
        $this->url = $url;
        return $this;
    }

    public function setDuration( int $duration): self {
        $this->duration = $duration;
        return $this;
    }

    public function add(): bool {
        $data = [
            "id_course" => $this->id_course,
            "title" => $this->title,
            "type" => "video",
            "url_video" => $this->url,
            "duration" => $this->duration
        ];
        return $this->db->insert("content", $data);
    }

    public function update(): bool {
        $data = [
            "title" => $this->title,
            "url_video" => $this->url
        ];
        return $this->db->update("content", $data, "id_content", $this->id_content);
    }

    public function delete(): bool {
        return $this->db->delete("content", "id_content", $this->id_content);
    }

    public function getById(): ?object {
        $result = $this->db->selectBy("content", ["id_content" => $this->id_content]);
        return $result ? $result[0] : null;
    }

    public function getByIdCourse(): ?ContentVideo {
        $result = $this->db->selectBy("content", ["id_course" => $this->id_course  , "type"=>"video"]);
        if ($result) {
            $firstRow = $result[0];
            return new ContentVideo(
                $this->db,
                $firstRow->id_content,
                $firstRow->id_course,
                $firstRow->title,
                $firstRow->type,
                $firstRow->url_video , 
                $firstRow->duration
            );
        }
    
        return null;
    }
    static public function getAllByIdCourse($db ,$id_course ): ?array
    {
       $result = $db->selectBy("content", ["id_course" => $id_course , "type"=>"video"]);
       return $result;
   }

    public function display(): string
{
    $videoHtml = '';

    
    if (strpos($this->url, 'uploads') !== false) {
        $videoHtml = '<video width="560" height="400" controls>
                        <source src="../' . htmlspecialchars($this->url) . '" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                      </video>';
    } else {
      
        $videoHtml = '<iframe height="400" width="560" src="' . htmlspecialchars($this->url) . '"></iframe>';
    }

    return '<div class="course-video w-full">
                <h2 class="text-2xl font-bold mb-4">' . htmlspecialchars($this->title) . '</h2>
                <div class="video-container mb-4">
                    <div class="container flex justify-center">
                        ' . $videoHtml . '
                    </div>
                </div>
                <div class="video-info mb-4">
                    <span class="text-sm text-gray-600">Durée: ' . htmlspecialchars($this->duration) . '</span>
                </div>
            </div>';

}
}

