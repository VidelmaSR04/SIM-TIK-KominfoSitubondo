<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;

/**
 * Membersihkan HTML dari editor teks sebelum disimpan dan dirender ke PDF.
 * Memakai daftar yang diizinkan: tag, atribut, dan properti CSS di luar daftar dibuang.
 */
class SuratHtmlSanitizer
{
    private const TAG_DIIZINKAN = [
        'p', 'br', 'div', 'span', 'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup',
        'ul', 'ol', 'li', 'table', 'thead', 'tbody', 'tr', 'td', 'th', 'colgroup', 'col',
        'h1', 'h2', 'h3', 'h4', 'hr',
    ];

    /** Tag berbahaya: dibuang beserta isinya. */
    private const TAG_DIBUANG = [
        'script', 'style', 'iframe', 'object', 'embed', 'link', 'meta', 'form', 'input',
        'button', 'textarea', 'select', 'svg', 'math', 'img', 'video', 'audio', 'canvas', 'base',
    ];

    private const CSS_DIIZINKAN = [
        'text-align', 'text-indent', 'font-size', 'font-family', 'font-weight', 'font-style',
        'text-decoration', 'line-height', 'color', 'background-color', 'vertical-align',
        'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
        'border-collapse', 'width', 'list-style-type',
    ];

    public function bersihkan(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $semula = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div id="__root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($semula);

        $xpath = new DOMXPath($dom);
        $root  = $xpath->query('//div[@id="__root"]')->item(0);
        if (!$root) {
            return '';
        }

        $semuaNode = [];
        foreach ($xpath->query('.//*', $root) as $node) {
            $semuaNode[] = $node;
        }

        foreach ($semuaNode as $node) {
            if (!$node instanceof DOMElement || !$node->parentNode) {
                continue;
            }

            $tag = strtolower($node->tagName);

            if (in_array($tag, self::TAG_DIBUANG, true)) {
                $node->parentNode->removeChild($node);
                continue;
            }

            if (!in_array($tag, self::TAG_DIIZINKAN, true)) {
                $this->bukaBungkus($node);   // mis. <a>: teksnya dipertahankan, tag-nya dibuang
                continue;
            }

            $this->bersihkanAtribut($node);
        }

        $hasil = '';
        foreach ($root->childNodes as $anak) {
            $hasil .= $dom->saveHTML($anak);
        }

        return trim($hasil);
    }

    private function bukaBungkus(DOMElement $node): void
    {
        $induk = $node->parentNode;
        while ($node->firstChild) {
            $induk->insertBefore($node->firstChild, $node);
        }
        $induk->removeChild($node);
    }

    private function bersihkanAtribut(DOMElement $node): void
    {
        $ubah = [];   // nama => nilai baru (null = hapus)

        foreach ($node->attributes as $attr) {
            $nama  = strtolower($attr->name);
            $nilai = trim($attr->value);

            switch ($nama) {
                case 'style':
                    $bersih = $this->bersihkanStyle($nilai);
                    $ubah[$attr->name] = $bersih !== '' ? $bersih : null;
                    break;

                case 'colspan':
                case 'rowspan':
                    $ubah[$attr->name] = preg_match('/^\d{1,2}$/', $nilai) ? $nilai : null;
                    break;

                case 'width':
                    $ubah[$attr->name] = preg_match('/^\d{1,4}%?$/', $nilai) ? $nilai : null;
                    break;

                case 'align':
                    $ubah[$attr->name] = in_array(strtolower($nilai), ['left', 'right', 'center', 'justify'], true) ? $nilai : null;
                    break;

                case 'valign':
                    $ubah[$attr->name] = in_array(strtolower($nilai), ['top', 'middle', 'bottom'], true) ? $nilai : null;
                    break;

                default:
                    $ubah[$attr->name] = null;   // id, class, on*, href, dll.
            }
        }

        foreach ($ubah as $nama => $nilai) {
            if ($nilai === null) {
                $node->removeAttribute($nama);
            } else {
                $node->setAttribute($nama, $nilai);
            }
        }
    }

    private function bersihkanStyle(string $style): string
    {
        $hasil = [];

        foreach (explode(';', $style) as $deklarasi) {
            if (!str_contains($deklarasi, ':')) {
                continue;
            }

            [$prop, $nilai] = array_map('trim', explode(':', $deklarasi, 2));
            $prop = strtolower($prop);

            if (!in_array($prop, self::CSS_DIIZINKAN, true) || $nilai === '') {
                continue;
            }

            if (preg_match('/url\s*\(|expression|javascript|@import|behavior|\\\\|<|>/i', $nilai)) {
                continue;
            }

            if (!preg_match('/^[\w\s\.,#%\-\(\)\'"\/]+$/u', $nilai)) {
                continue;
            }

            $hasil[] = $prop . ': ' . $nilai;
        }

        return implode('; ', $hasil);
    }
}