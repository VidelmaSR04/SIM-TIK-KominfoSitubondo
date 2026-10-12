<?php

namespace Tests\Feature;

use App\Models\Server;
use App\Models\ServerDocument;
use App\Models\MasterData;
use Barryvdh\DomPDF\Facade\Pdf;
use Tests\TestCase;

class ServerDocumentLayoutTest extends TestCase
{
    public function test_pdf_uses_automatic_appendix_header_and_blank_signature_qr_spaces(): void
    {
        $server = new Server([
            'nama_perangkat' => 'Server Uji',
            'kode_perangkat' => 'KO261012A',
            'status_kepemilikan' => 'Kominfo',
        ]);
        $server->setRelation('aplikasis', collect());

        $dokumen = new ServerDocument([
            'kop_html' => '<p>Kop</p>',
            'surat_tanggal' => '12 Oktober 2026',
            'surat_nomor' => '123/ABC/2026',
            'surat_sifat' => 'Penting',
            'surat_lampiran' => '1 (satu) berkas',
            'surat_hal' => 'Rincian server',
            'surat_yth' => '(Daftar terlampir)',
            'isi_surat' => '<p>Isi surat.</p>',
            'ttd_kiri_judul' => "KEPALA DINAS\nKOMUNIKASI DAN INFORMATIKA\nKABUPATEN SITUBONDO",
            'ttd_kiri_nama' => 'Nama Pejabat',
            'ttd_kiri_pangkat' => 'IV/c Pembina Utama Muda',
            'ttd_kanan_judul' => 'KEPALA BIDANG',
            'ttd_kanan_nama' => 'Nama Pejabat 2',
        ]);

        $data = [
            'server' => $server,
            'dokumen' => $dokumen,
            'logo' => null,
        ];
        $html = view('pdf.detailserver', $data)->render();

        $this->assertStringContainsString('Lampiran I', $html);
        $this->assertSame(1, substr_count($html, 'Lampiran I'));
        $this->assertStringContainsString('123/ABC/2026', $html);
        $this->assertStringContainsString('12 Oktober 2026', $html);
        $this->assertStringContainsString('font-size: 12pt !important', $html);
        $this->assertStringContainsString('text-align: center !important', $html);
        $this->assertStringContainsString('font-size: 11pt !important', $html);
        $this->assertStringContainsString('<div class="judul">Rincian Server</div>', $html);
        $this->assertStringContainsString('height: 115.2pt', $html);
        $this->assertStringContainsString('Pembina Utama Muda', $html);
        $this->assertStringNotContainsString('IV/c Pembina Utama Muda', $html);
        $this->assertStringContainsString('<div class="ttd-pembuka">Mengetahui,</div>', $html);
        $this->assertStringContainsString('KEPALA DINAS</div>', $html);
        $this->assertStringContainsString('KOMUNIKASI DAN INFORMATIKA</div>', $html);
        $this->assertStringContainsString('KABUPATEN SITUBONDO</div>', $html);
        $this->assertStringContainsString(".ttd-sel {\n            width: 50%;\n            text-align: left;", $html);
        $this->assertStringNotContainsString('display: table-header-group', $html);
        $this->assertSame(2, substr_count($html, '<div class="ttd-ruang"></div>'));
        $this->assertStringStartsWith('%PDF-', Pdf::loadView('pdf.detailserver', $data)
            ->setPaper([0, 0, 612, 936], 'portrait')
            ->output());
    }

    public function test_official_signature_titles_use_three_lines_for_both_official_roles(): void
    {
        $kepalaDinas = new MasterData([
            'value' => MasterData::JABATAN_KEPALA_DINAS,
        ]);
        $kepalaBidang = new MasterData([
            'value' => MasterData::JABATAN_KABID_TIK,
        ]);

        $this->assertSame(
            "KEPALA DINAS\nKOMUNIKASI DAN INFORMATIKA\nKABUPATEN SITUBONDO",
            $kepalaDinas->jabatanTtd()
        );
        $this->assertSame(
            "KEPALA BIDANG\nTEKNOLOGI INFORMASI\nDAN KOMUNIKASI",
            $kepalaBidang->jabatanTtd()
        );
    }
}
