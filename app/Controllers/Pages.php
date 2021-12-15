<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Web Portfolio Frans',
            'tes' => ['satu', 'dua', 'tiga']
        ];
        return view('pages/home', $data);
    }
    public function about()
    {
        $data = [
            'title' => 'About Me | Web Portfolio Frans'
        ];
        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact | Web Portfolio Frans',
            'alamat' => [
                [
                    'tipe' => 'Rumah',
                    'alamat' => 'Jl.Pluit Permai 6 no 46',
                    'kota' => 'Jakarta Utara'
                ],
                [
                    'tipe' => 'Kantor',
                    'alamat' => 'Jl.Pergudangan Duta Indah Rawa Buaya Cengkareng',
                    'kota' => 'Jakarta Barat'
                ]
            ]
        ];

        return view('pages/contact', $data);
    }
}
