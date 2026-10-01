<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only process server_documents table since document_settings already has kop_html column
        $this->processTable('server_documents', [
            'kop_baris_1' => 'kop_baris_1',
            'kop_baris_2' => 'kop_baris_2'
        ]);
    }

    public function down(): void
    {
        Schema::table('server_documents', function (Blueprint $table) {
            $table->dropColumn('kop_html');
        });
    }

    private function processTable(string $tabel, array $kolomMapping): void
    {
        // Add kop_html column
        Schema::table($tabel, function (Blueprint $table) {
            $table->longText('kop_html')->nullable()->after('kop_alamat');
        });

        // Make kop columns nullable
        foreach ($kolomMapping as $kolomActual => $kolomExpected) {
            Schema::table($tabel, function (Blueprint $table) use ($kolomActual) {
                $table->string($kolomActual)->nullable()->change();
            });
        }

        // Copy data from text columns to HTML
        foreach (DB::table($tabel)->get() as $row) {
            $data = [];
            foreach ($kolomMapping as $kolomActual => $kolomExpected) {
                $data[$kolomActual] = $row->{$kolomActual};
            }

            $kopHtml = $this->susunHtml(
                $data['kop_baris_1'] ?? null,
                $data['kop_baris_2'] ?? null,
                $row->kop_alamat
            );

            DB::table($tabel)->where('id', $row->id)->update([
                'kop_html' => $kopHtml,
            ]);
        }
    }

    private function susunHtml(?string $b1, ?string $b2, ?string $alamat): string
    {
        $gaya  = 'font-family: Arial, Helvetica, sans-serif; text-align: center;';
        $baris = array_values(array_filter(
            preg_split('/\r\n|\r|\n/', (string) $alamat),
            fn ($b) => trim($b) !== ''
        ));

        $html  = '<p style="' . $gaya . ' margin: 0 0 0 1.27cm; font-size: 12pt; line-height: 1.15;">'
               . str_replace(' ', '&nbsp; ', e(trim((string) $b1))) . '</p>';
        $html .= "\n" . '<p style="' . $gaya . ' margin: 0 0 4.7pt 1.27cm; font-size: 16pt; line-height: 1.15;"><strong>'
               . e(trim((string) $b2)) . '</strong></p>';

        foreach ($baris as $i => $b) {
            $terakhir = $i === count($baris) - 1;
            $html .= "\n" . '<p style="' . $gaya . ' margin: ' . ($terakhir ? '0' : '0 0 0 1.27cm')
                   . '; font-size: 10pt; line-height: 1.3;">' . e(trim($b)) . '</p>';
        }

        return $html;
    }
};