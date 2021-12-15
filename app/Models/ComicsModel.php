<?php

namespace App\Models;

use CodeIgniter\Model;

class ComicsModel extends Model
{
    protected $table = 'comics';
    protected $useTimestamps = true;
    protected $allowedFields = ['title', 'slug', 'writer', 'publisher', 'synopsis', 'cover'];

    public function getComics($slug = false)
    {
        if ($slug == false) {
            return $this->findAll();
        }
        return $this->where(['slug' => $slug])->first();
    }

    public function search($keyword)
    {
        return $this->table('comics')->like('title', $keyword)->orLike('writer', $keyword);
    }
}
