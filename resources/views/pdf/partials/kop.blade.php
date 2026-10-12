@php
    $kopHtml = filled($dokumen->kop_html)
        ? $dokumen->kop_html
        : \App\Models\DocumentSetting::kopHtmlBawaan();

    // $logo = path file, atau data URI (preview logo yang belum disimpan)
    $logoAda = !empty($logo)
        && (\Illuminate\Support\Str::startsWith($logo, 'data:') || file_exists($logo));
@endphp

<div class="kop">
    @if ($logoAda)
        <img src="{{ $logo }}" class="kop-logo" style="{{ \App\Models\DocumentSetting::gayaLogo($logo) }}" alt="Logo">
    @endif
    <div class="kop-teks">{!! $kopHtml !!}</div>
</div>
<div class="kop-garis"></div>