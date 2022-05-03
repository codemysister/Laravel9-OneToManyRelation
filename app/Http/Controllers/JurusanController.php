<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurusanController extends Controller
{
    public function all()
    {
        $jurusans = DB::select('SELECT * FROM jurusans');
        foreach ($jurusans as $jurusan) {
            echo "$jurusan->id | $jurusan->nama | $jurusan->kepala_jurusan | ";
            echo "$jurusan->daya_tampung <br>";
        }
    }

    public function gabung()
    {
        $result = DB::select(
            'SELECT
 jurusans.nama as nama_jurusan,
 mahasiswas.id as id_mahasiswa,
 mahasiswas.nama as nama_mahasiswa
 FROM jurusans, mahasiswas
 WHERE jurusans.id = mahasiswas.jurusan_id'
        );

        foreach ($result as $row) {
            echo "$row->nama_jurusan | $row->nama_mahasiswa ($row->id_mahasiswa) <br>";
        }
    }

    public function gabungJoin()
    {
        $result = DB::select(
            'SELECT
 jurusans.nama as nama_jurusan,
 mahasiswas.id as id_mahasiswa,
 mahasiswas.nama as nama_mahasiswa
 FROM jurusans JOIN mahasiswas
 ON jurusans.id = mahasiswas.jurusan_id
 WHERE jurusans.nama = "Sistem Informasi"
 ORDER BY mahasiswas.id'
        );

        foreach ($result as $row) {
            echo "$row->nama_jurusan | $row->nama_mahasiswa ($row->id_mahasiswa) <br>";
        }
    }


    public function find()
    {
        $jurusan = Jurusan::find(1);
        echo "$jurusan->id | $jurusan->nama | $jurusan->kepala_jurusan | ";
        echo "$jurusan->daya_tampung <br>";
        dump($jurusan->mahasiswas->toArray());

        echo "Jurusan $jurusan->nama <br>";
        echo "Nama Kepala Jurusan : $jurusan->kepala_jurusan <br>";
        echo "Daya Tampung : $jurusan->daya_tampung orang <hr>";

        echo "## Daftar Mahasiswa ##";
        echo "<br><br>";
        foreach ($jurusan->mahasiswas as $mahasiswa) {
            echo "$mahasiswa->nama ($mahasiswa->nim) <br>";
        }

        $jurusans = Jurusan::with('mahasiswas')->get();
        dump($jurusans);
        foreach ($jurusans as $jurusan) {
            echo "Jurusan $jurusan->nama ($jurusan->daya_tampung orang)<br> ";
            echo "Kepala Jurusan: $jurusan->kepala_jurusan <br> ";
            echo "Mahasiswa: ";
            foreach ($jurusan->mahasiswas as $mahasiswa) {
                echo "$mahasiswa->nama ($mahasiswa->nim), ";
            }
            echo "<hr>";
        }
    }

    public function relationshipWhere()
    {
        $jurusan = Jurusan::where('kepala_jurusan', 'Dr. Umar Agustinus, M.Sc.')
            ->first();

        echo "Jurusan $jurusan->nama <br>";
        echo "Nama Kepala Jurusan : $jurusan->kepala_jurusan <br>";
        echo "Daya Tampung : $jurusan->daya_tampung orang <hr>";


        echo "## Daftar Mahasiswa ##";
        echo "<br><br>";
        foreach ($jurusan->mahasiswas as $mahasiswa) {
            echo "$mahasiswa->nama ($mahasiswa->nim) <br>";
        }
    }

    public function has()
    {
        $jurusans = Jurusan::has('mahasiswas')->get();

        foreach ($jurusans as $jurusan) {
            echo "$jurusan->nama | ";
        }
    }

    public function whereHas()
    {
        $jurusans = Jurusan::whereHas('mahasiswas', function ($query) {
            $query->where('nama', 'like', 'M%');
        })->get();

        foreach ($jurusans as $jurusan) {
            echo "$jurusan->nama | ";
        }
    }

    public function doesntHave()
    {
        $jurusans = Jurusan::doesntHave('mahasiswas')->get();

        foreach ($jurusans as $jurusan) {
            echo "$jurusan->nama | ";
        }
    }

    public function withcount()
    {
        $jurusans = Jurusan::withCount('mahasiswas')->get();
        dump($jurusans->toArray());

        foreach ($jurusans as $jurusan) {
            echo "$jurusan->nama ($jurusan->mahasiswas_count mahasiswa) <br> ";
        }
    }

    public function loadCount()
    {
        $jurusan = Jurusan::where('kepala_jurusan', 'Dr. Umar Agustinus, M.Sc.')
            ->first();
        $jurusan->loadCount('mahasiswas');
        echo "$jurusan->nama ($jurusan->mahasiswas_count mahasiswa) <br> ";
    }

    public function insertSave()
    {
        $jurusan = new Jurusan;
        $jurusan->nama = 'Farmasi';
        $jurusan->kepala_jurusan = 'Prof. Silvia Nst, M.Farm';
        $jurusan->daya_tampung = 125;
        $jurusan->save();

        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = '19001516';
        $mahasiswa->nama = 'Christine Wijaya';

        $jurusan->mahasiswas()->save($mahasiswa);

        echo "Penambahan jurusan $jurusan->nama dan
 mahasiswa $mahasiswa->nama ke database berhasil";
    }

    public function insertCreate()
    {
        $jurusan = Jurusan::where('nama', 'Ilmu Komputer')->first();

        $jurusan->mahasiswas()->create([
            'nim' => '19001912',
            'nama' => 'Bobby Permana',
        ]);

        echo "Penambahan mahasiswa ke database berhasil";
    }

    public function insertCreateMany()
    {
        $jurusan = Jurusan::where('nama', 'Sistem Informasi')->first();

        $jurusan->mahasiswas()->createMany([
            [
                'nim' => '19002345',
                'nama' => 'Jessica Irwan'
            ],
            [
                'nim' => '19005007',
                'nama' => 'Lara Permata'
            ]
        ]);

        echo "Penambahan mahasiswa ke database berhasil";
    }

    public function update()
    {
        $jurusan_ilkom = Jurusan::where('nama', 'Ilmu Komputer')->first();
        $jurusan_si = Jurusan::where('nama', 'Sistem Informasi')->first();

        $jurusan_ilkom->mahasiswas()->update([
            'jurusan_id' => $jurusan_si->id,
        ]);

        echo "Semua mahasiswa $jurusan_ilkom->nama sudah sudah pindah
 $jurusan_si->nama";
    }

    public function updatePush()
    {
        $jurusan = Jurusan::where('nama', 'Sistem Informasi')->first();

        foreach ($jurusan->mahasiswas as $mahasiswa) {
            $mahasiswa->nama = $mahasiswa->nama . " S.Kom";
            $mahasiswa->push();
            echo "Berhasil update nama mahasiswa menjadi
 $mahasiswa->nama <br>";
        }
    }

    public function delete()
    {
        $jurusan = Jurusan::where('nama', 'Sistem Informasi')->firstOrFail();
        $jurusan->delete();
        echo "Jurusan $jurusan->nama beserta semua mahasiswa sudah dihapus";
    }
}
