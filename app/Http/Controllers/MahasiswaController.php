<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function find()
    {
        $mahasiswa = Mahasiswa::find(2);

        echo "$mahasiswa->nama, dari jurusan {$mahasiswa->jurusan->nama}";
    }

    public function where()
    {
        $mahasiswa = Mahasiswa::where('nama', 'like', 'M%')
            ->orderBy('nama', 'desc')->firstOrFail();

        echo "$mahasiswa->nama, dari jurusan {$mahasiswa->jurusan->nama}";
        // Mustofa Simanjuntak, dari jurusan Ilmu Komputer
    }

    public function whereChaining()
    {
        echo Mahasiswa::where('nama', 'Mahdi Rajata')->firstOrFail()->jurusan->nama;
        // Sistem Informasi
    }

    public function has()
    {
        $mahasiswas = Mahasiswa::has('jurusan')->get();
        foreach ($mahasiswas as $mahasiswa) {
            echo "$mahasiswa->nama, ";
        }
    }

    public function whereHas()
    {
        $mahasiswas = Mahasiswa::whereHas('jurusan', function ($query) {
            $query->where('nama', 'Sistem Informasi');
        })->get();
        foreach ($mahasiswas as $mahasiswa) {
            echo "$mahasiswa->nama, ";
        }
        // Marsito Purnawati, Queen Suryatmi, Mahdi Rajata,
    }

    public function doesntHave()
    {
        $mahasiswas = Mahasiswa::doesntHave('jurusan')->get();
        foreach ($mahasiswas as $mahasiswa) {
            echo $mahasiswa->nama . ", ";
        }
    }

    public function associate()
    {
        $jurusan = Jurusan::where('nama', 'Teknik Komputer')->first();
        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = '19001516';
        $mahasiswa->nama = 'Christine Wijaya';

        $mahasiswa->jurusan()->associate($jurusan);
        $mahasiswa->save();

        echo "Penambahan $mahasiswa->nama ke database berhasil";
    }

    public function associateUpdate()
    {
        $jurusan = Jurusan::where('nama', 'Ilmu Komputer')->first();
        $mahasiswa = Mahasiswa::where('nama', 'Christine Wijaya')->first();

        $mahasiswa->jurusan()->associate($jurusan);
        $mahasiswa->save();

        echo "Perubahan jurusan $mahasiswa->nama berhasil";
    }

    public function delete()
    {
        $mahasiswas = Mahasiswa::whereHas('jurusan', function ($query) {
            $query->where('nama', 'Ilmu Komputer');
        })->get();

        foreach ($mahasiswas as $mahasiswa) {
            $mahasiswa->delete();
        }

        echo "Semua mahasiswa jurusan Ilmu Komputer berhasil di hapus";
    }

    public function dissociate()
    {
        $mahasiswas = Mahasiswa::whereHas('jurusan', function ($query) {
            $query->where('nama', 'Sistem Informasi');
        })->get();

        foreach ($mahasiswas as $mahasiswa) {
            $mahasiswa->jurusan()->dissociate();
            $mahasiswa->save();
            echo "Pengosongan jurusan untuk $mahasiswa->nama_mahasiswa berhasil <br>";
        }
    }
}
