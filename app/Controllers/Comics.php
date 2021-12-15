<?php

namespace App\Controllers;

use App\Models\ComicsModel;

class Comics extends BaseController
{
    protected $comicsModel;
    public function __construct()
    {
        $this->comicsModel = new ComicsModel();
    }
    public function index()
    {
        // $comics = $this->comicsModel->findAll();
        $currentPage = $this->request->getVar('page_comics') ? $this->request->getVar('page_comics') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $comics = $this->comicsModel->search($keyword);
        } else {
            $comics = $this->comicsModel;
        }

        $data = [
            'title' => 'List of Comics',
            'comics' => $this->comicsModel->paginate(3, 'comics'),
            'pager' => $this->comicsModel->pager,
            'currentPage' => $currentPage
            // 'comics' => $this->comicsModel->getComics()
        ];

        // $comicsModel = new \App\Models\ComicsModel();
        return view('comics/index', $data);
    }
    public function detail($slug)
    {
        $comics = $this->comicsModel->getComics($slug);
        $data = [
            'title' => 'Detail Comics',
            'comics' => $this->comicsModel->getComics($slug)
        ];

        // Jika comic tidak ada di tabel
        if (empty($data['comics'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Title comics ' . $slug . ' Not Found.');
        }


        return view('comics/detail', $data);
    }
    public function create()
    {
        // session();
        $data = [
            'title' => 'Add New Comic',
            'validation' => \Config\Services::validation()
        ];
        return view('comics/create', $data);
    }
    public function save()
    {
        // Validation input
        if (!$this->validate([
            'title' => [
                'rules' => 'required|is_unique[comics.title]',
                'errors' => [
                    'required' => '{field} of comic must be fill!',
                    'is_unique' => '{field} this comic has been registered.'
                ]
            ],
            'cover' => [
                'rules' => 'max_size[cover, 1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png,image/jfif]',
                'errors' => [
                    'max_size' => 'This cover picture oversize, at least 1 mb',
                    'is_image' => 'This file you choose its not a picture',
                    'mime_in' => 'This file you choose its not a picture'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/comics/create')->withInput()->with('validation', $validation);
            return redirect()->to('/comics/create')->withInput();
        }

        // Ambil gambar 
        $fileCover = $this->request->getFile('cover');
        // Apakah tidak ada gamabar yang diupload
        if ($fileCover->getError() == 4) {
            $nameCover = 'Default.png';
        } else {
            // Generate nama sampul random\
            $nameCover = $fileCover->getRandomName();
            // Pindahkan File ke folder img
            $fileCover->move('img', $nameCover);
        }
        // Ambil nama file sampul
        // $nameCover = $fileCover->getName();



        $slug = url_title($this->request->getVar('title'), '-', true);
        $this->comicsModel->save([
            'title' => $this->request->getVar('title'),
            'slug' => $slug,
            'writer' => $this->request->getVar('writer'),
            'publisher' => $this->request->getVar('publisher'),
            'synopsis' => $this->request->getVar('synopsis'),
            'cover' => $nameCover
        ]);

        session()->setFlashdata('message', 'Comic has been success added');



        return redirect()->to('/comics');
    }

    public function delete($id)
    {
        // Cari gambar berdasarkan id
        $comics = $this->comicsModel->find($id);

        // Cek jika file gambarnya default
        if ($comics['cover'] != 'Default.png') {
            // Hapus file gambar
            unlink('img/' . $comics['cover']);
        }


        $this->comicsModel->delete($id);
        session()->setFlashdata('message', 'Data comic has been success delete.');
        return redirect()->to('/comics');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Edit Data Comics',
            'validation' => \Config\Services::validation(),
            'comics' => $this->comicsModel->getComics($slug)
        ];
        return view('comics/edit', $data);
    }

    public function update($id)
    {
        // Cek judul nya dulu
        $oldComic = $this->comicsModel->getComics($this->request->getVar('slug'));
        if ($oldComic['title'] == $this->request->getVar('title')) {
            $rule_title = 'required';
        } else {
            $rule_title = 'required|is_unique[comics.title]';
        }

        if (!$this->validate([
            'title' => [
                'rules' => $rule_title,
                'errors' => [
                    'required' => '{field} of comic must be fill!',
                    'is_unique' => '{field} this comic has been registered.'
                ]
            ],
            'cover' => [
                'rules' => 'max_size[cover, 1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png,image/jfif]',
                'errors' => [
                    'max_size' => 'This cover picture oversize, at least 1 mb',
                    'is_image' => 'This file you choose its not a picture',
                    'mime_in' => 'This file you choose its not a picture'
                ]
            ]
        ])) {
            return redirect()->to('/comics/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileCover = $this->request->getFile('cover');

        // Cek gambar apakah tetap gambar lama
        if ($fileCover->getError() == 4) {
            $nameCover = $this->request->getVar('oldCover');
        } else {
            // Generate file random name
            $nameCover = $fileCover->getRandomName();
            // Pindahkan gamvar / upload file
            $fileCover->move('img', $nameCover);
            // Hapus file yang lama
            unlink('img/' . $this->request->getVar('oldCover'));
        }



        $slug = url_title($this->request->getVar('title'), '-', true);
        $this->comicsModel->save([
            'id' => $id,
            'title' => $this->request->getVar('title'),
            'slug' => $slug,
            'writer' => $this->request->getVar('writer'),
            'publisher' => $this->request->getVar('publisher'),
            'synopsis' => $this->request->getVar('synopsis'),
            'cover' => $nameCover
        ]);

        session()->setFlashdata('message', 'Data comic has been edited successfully.');

        return redirect()->to('/comics');
    }
}






  // cara konek db tanpa model
        
        // $db = \Config\Database::connect();
        // $comics = $db->query("SELECT * FROM comics");
        // foreach ($comics->getResultArray() as $row) {
        //     d($row);
        // ]