<?php
namespace App\model;
use App\config\DataBaseManager;



class ContentText extends AbstractContent
{
    private ?string $content = null; 

    public function __construct(
        ?\config\DataBaseManager $db,
        ?int $id_content = null,
        ?int $id_course = null,
        ?string $title = null,
        ?string $type = null,
        ?string $content = null 
    ) {
        parent::__construct($db, $id_content, $id_course, $title, $type);
        $this->content = $content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content ?? ''; 
    }

    public function display(): string
    {
        return '<div class="course-text">
        <h2 class="text-2xl font-bold mb-4">' . ($this->title) . '</h2>
        <div class="bg-white rounded-lg p-8 mb-8">
            <div class="">
                ' . nl2br(($this->getContent())) . '
            </div>
        </div>
       </div>';
    }



    public function add(): bool
    {
        $data = [
            "id_course" => $this->id_course,
            "title" => $this->title,
            "type" => "texte",
            "content_text" => $this->content
        ];
        return $this->db->insert("content", $data);
    }

    public function update(): bool
    {
        $data = [
            "title" => $this->title,
            "content_text" => $this->content
        ];
        return $this->db->update("content", $data, "id_content", $this->id_content);
    }

    public function delete(): bool
    {
        return $this->db->delete("content", "id_content", $this->id_content);
    }

    public function getById(): ?ContentText
    {
        $result = $this->db->selectBy("content", ["id_content" => $this->id_content]);
        if ($result) {
            $firstRow = $result[0];
            return new ContentText(
                $this->db,
                $firstRow->id_content,
                $firstRow->id_course,
                $firstRow->title,
                $firstRow->type,
                $firstRow->content_text 
            );
        }
    
        return null;
    }

    public function getByIdCourse(): ?ContentText
    {
        $result = $this->db->selectBy("content", ["id_course" => $this->id_course, "type" => "texte"]);
    
        if ($result) {
            $firstRow = $result[0];
            return new ContentText(
                $this->db,
                $firstRow->id_content,
                $firstRow->id_course,
                $firstRow->title,
                $firstRow->type,
                $firstRow->content_text 
            );
        }
    
        return null;
    }
    
    public function getByIdContent(): ?ContentText
    {
        $result = $this->db->selectBy("content", ["id_content" => $this->id_content, "type" => "texte"]);
    
        if ($result) {
            $firstRow = $result[0];
            return new ContentText(
                $this->db,
                $firstRow->id_content,
                $firstRow->id_course,
                $firstRow->title,
                $firstRow->type,
                $firstRow->content_text 
            );
        }
    
        return null;
    }
    static public function getAllByIdCourse($db, $id_course): ?array
    {
        $result = $db->selectBy("content", ["id_course" => $id_course, "type" => "texte"]);
        $contents = [];
        foreach ($result as $row) {
            $contents[] = new ContentText(
                $db,
                $row->id_content,
                $row->id_course,
                $row->title,
                $row->type,
                $row->content_text
            );
        }
        return $contents;
    }



    
}
