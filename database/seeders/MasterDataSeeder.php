<?php

namespace Database\Seeders;

use App\Models\MasterData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the data for each category
        $data = [
            'jenis_perangkat' => [
                ['value' => 'router', 'label' => null, 'urutan' => 1],
                ['value' => 'switch', 'label' => null, 'urutan' => 2],
                ['value' => 'server', 'label' => null, 'urutan' => 3],
            ],
            'merk_perangkat' => [
                ['value' => 'MIKROTIK', 'label' => null, 'urutan' => 1],
                ['value' => 'CISCO', 'label' => null, 'urutan' => 2],
                ['value' => 'DELL', 'label' => null, 'urutan' => 3],
                ['value' => 'HP', 'label' => null, 'urutan' => 4],
                ['value' => 'LENOVO', 'label' => null, 'urutan' => 5],
                ['value' => 'HUAWEI', 'label' => null, 'urutan' => 6],
            ],
            'tipe_perangkat' => [
                ['value' => 'RACK MOUNT', 'label' => null, 'urutan' => 1],
                ['value' => 'TOWER', 'label' => null, 'urutan' => 2],
                ['value' => 'BLADE', 'label' => null, 'urutan' => 3],
            ],
            'status_kepemilikan' => [
                ['value' => 'Kominfo', 'label' => null, 'urutan' => 1],
                ['value' => 'Colocation', 'label' => null, 'urutan' => 2],
            ],
            'pemilik_perangkat' => [], // We'll fill this with the OPD list
            'status_perangkat' => [
                ['value' => 'Aktif', 'label' => null, 'urutan' => 1],
                ['value' => 'Non-Aktif', 'label' => null, 'urutan' => 2],
                ['value' => 'Maintenance', 'label' => null, 'urutan' => 3],
            ],
            'kondisi_tipe' => [
                ['value' => 'Standard', 'label' => null, 'urutan' => 1],
                ['value' => 'High Performance', 'label' => null, 'urutan' => 2],
            ],
            'kondisi_status' => [
                ['value' => 'Baru', 'label' => null, 'urutan' => 1],
                ['value' => 'Bekas', 'label' => null, 'urutan' => 2],
            ],
            'nomor_rack' => [
                ['value' => 'R1', 'label' => null, 'urutan' => 1],
                ['value' => 'R2', 'label' => null, 'urutan' => 2],
                ['value' => 'R3', 'label' => null, 'urutan' => 3],
                ['value' => 'R4', 'label' => null, 'urutan' => 4],
                ['value' => 'R5', 'label' => null, 'urutan' => 5],
                ['value' => 'R6', 'label' => null, 'urutan' => 6],
                ['value' => 'R7', 'label' => null, 'urutan' => 7],
                ['value' => 'R8', 'label' => null, 'urutan' => 8],
            ],
        ];

        // OPD list (pemilik_perangkat)
        $opdOptions = [
            'Dinas Pendidikan dan Kebudayaan',
            'Dinas Kesehatan',
            'Dinas Pekerjaan Umum dan Penataan Ruang',
            'Dinas Perumahan dan Kawasan Permukiman',
            'Satuan Polisi Pamong Praja',
            'Badan Kesatuan Bangsa dan Politik',
            'Badan Penanggulangan Bencana Daerah',
            'Dinas Sosial',
            'Dinas Tenaga Kerja',
            'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
            'Dinas Ketahanan Pangan',
            'Dinas Lingkungan Hidup',
            'Dinas Kependudukan dan Pencatatan Sipil',
            'Dinas Pemberdayaan Masyarakat dan Desa',
            'Dinas Pengendalian Penduduk dan Keluarga Berencana',
            'Dinas Perhubungan',
            'Dinas Komunikasi, Informatika dan Persandian',
            'Dinas Koperasi dan Usaha Mikro',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
            'Dinas Perpustakaan dan Kearsipan',
            'Dinas Perikanan',
            'Dinas Pariwisata',
            'Dinas Tanaman Pangan, Hortikultura dan Perkebunan',
            'Dinas Peternakan dan Kesehatan Hewan',
            'Dinas Perdagangan dan Perindustrian',
            'Badan Perencanaan Pembangunan Daerah',
            'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
            'Badan Pendapatan, Pengelolaan Keuangan dan Aset Daerah',
            'Inspektorat Daerah',
            'Sekretariat Daerah',
            'Sekretariat DPRD',
            'Kecamatan Banyuglugur',
            'Kecamatan Jatibanteng',
            'Kecamatan Sumbermalang',
            'Kecamatan Besuki',
            'Kecamatan Suboh',
            'Kecamatan Mlandingan',
            'Kecamatan Bungatan',
            'Kecamatan Kendit',
            'Kecamatan Panarukan',
            'Kecamatan Situbondo',
            'Kecamatan Panji',
            'Kecamatan Mangaran',
            'Kecamatan Kapongan',
            'Kecamatan Arjasa',
            'Kecamatan Asembagus',
            'Kecamatan Jangkar',
            'Kecamatan Banyuputih',
            'RSAR',
            'PDAM Tirta Baluran',
            'RSUD Besuki',
            'RSUD Asembagus',
        ];

        foreach ($opdOptions as $index => $opd) {
            $data['pemilik_perangkat'][] = [
                'value' => $opd,
                'label' => null,
                'urutan' => $index + 1,
            ];
        }

        // Insert each category's data
        foreach ($data as $kategori => $items) {
            foreach ($items as $item) {
                MasterData::updateOrCreate(
                    ['kategori' => $kategori, 'value' => $item['value']],
                    [
                        'label' => $item['label'],
                        'urutan' => $item['urutan'],
                        'is_aktif' => true,
                    ]
                );
            }
        }
    }
}
