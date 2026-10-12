<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    private array $columns = [
        'surat_tanggal',
        'surat_nomor',
        'surat_sifat',
        'surat_lampiran',
        'surat_hal',
        'surat_yth',
    ];

    public function up(): void
    {
        Schema::table('server_documents', function (Blueprint $table) {
            $table->string('surat_tanggal', 100)->nullable();
            $table->string('surat_nomor', 150)->nullable();
            $table->string('surat_sifat', 100)->nullable();
            $table->string('surat_lampiran', 100)->nullable();
            $table->string('surat_hal', 255)->nullable();
            $table->string('surat_yth', 500)->nullable();
        });

        foreach (DB::table('server_documents')->select(['id', 'isi_surat'])->cursor() as $document) {
            $header = $this->extractHeader($document->isi_surat ?? '');
            if ($header === null) {
                continue;
            }

            DB::table('server_documents')
                ->where('id', $document->id)
                ->update($header);
        }
    }

    public function down(): void
    {
        foreach (DB::table('server_documents')->select(array_merge(['id'], $this->columns))->cursor() as $document) {
            if (!filled($document->surat_tanggal)) {
                continue;
            }

            $rows = [
                ['Nomor', $document->surat_nomor],
                ['Sifat', $document->surat_sifat],
                ['Lampiran', $document->surat_lampiran],
                ['Hal', $document->surat_hal],
            ];
            $table = '<table style="width:100%;border-collapse:collapse"><tbody>';
            foreach ($rows as [$label, $value]) {
                $table .= '<tr><td>' . e($label) . '</td><td>:</td><td>' . e($value) . '</td></tr>';
            }
            $table .= '</tbody></table>';
            $header = '<p style="text-align:right">Situbondo, ' . e($document->surat_tanggal) . '</p>'
                . $table
                . '<p>Yth.&nbsp;&nbsp;&nbsp;' . e($document->surat_yth) . '</p>';
            $body = DB::table('server_documents')->where('id', $document->id)->value('isi_surat');

            DB::table('server_documents')->where('id', $document->id)->update([
                'isi_surat' => $header . $body,
            ]);
        }

        Schema::table('server_documents', function (Blueprint $table) {
            $table->dropColumn($this->columns);
        });
    }

    private function extractHeader(string $html): ?array
    {
        if (trim($html) === '') {
            return null;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div id="letter-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($dom);
        $root = $xpath->query('//*[@id="letter-root"]')->item(0);
        if (!$root) {
            return null;
        }

        $dateNode = null;
        $tableNode = null;
        $ythNode = null;
        foreach ($root->childNodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            if (!$dateNode && strtolower($node->tagName) === 'p'
                && preg_match('/^\s*Situbondo,\s*(.+)$/ui', trim($node->textContent), $match)) {
                $dateNode = $node;
                $tanggal = trim($match[1]);
                continue;
            }
            if (!$tableNode && strtolower($node->tagName) === 'table') {
                $tableNode = $node;
                continue;
            }
            if ($tableNode && !$ythNode && strtolower($node->tagName) === 'p'
                && preg_match('/^\s*Yth\.?\s*/ui', trim($node->textContent))) {
                $ythNode = $node;
            }
        }

        if (!$dateNode || !$tableNode || !$ythNode) {
            return null;
        }

        $fields = [
            'surat_tanggal' => $tanggal,
            'surat_nomor' => null,
            'surat_sifat' => null,
            'surat_lampiran' => null,
            'surat_hal' => null,
            'surat_yth' => trim(preg_replace('/^[\s\x{00a0}]*Yth\.?[\s\x{00a0}]*/ui', '', trim($ythNode->textContent))),
        ];

        foreach ($xpath->query('.//tr', $tableNode) as $row) {
            $cells = $xpath->query('./td|./th', $row);
            if ($cells->length < 3) {
                continue;
            }
            $label = mb_strtolower(trim($cells->item(0)->textContent));
            $key = match ($label) {
                'nomor' => 'surat_nomor',
                'sifat' => 'surat_sifat',
                'lampiran' => 'surat_lampiran',
                'hal' => 'surat_hal',
                default => null,
            };
            if ($key) {
                $fields[$key] = trim($cells->item(2)->textContent);
            }
        }

        if (in_array(null, array_slice($fields, 1, 5), true)) {
            return null;
        }

        $body = '';
        foreach (iterator_to_array($root->childNodes) as $node) {
            if ($node === $dateNode || $node === $tableNode || $node === $ythNode) {
                $root->removeChild($node);
                continue;
            }
            $body .= $dom->saveHTML($node);
        }
        $fields['isi_surat'] = trim($body);

        return $fields;
    }
};
