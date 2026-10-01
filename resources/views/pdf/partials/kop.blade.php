@php
    $kopHtml = filled($dokumen->kop_html)
        ? $dokumen->kop_html
        : \App\Models\DocumentSetting::kopHtmlBawaan();
@endphp

<div class="kop">
    @if (!empty($logo) && file_exists($logo))
        <img src="{{ $logo }}" class="kop-logo" alt="Logo">
    @endif
    <div class="kop-teks">{!! $kopHtml !!}</div>
</div>
<div class="kop-garis"></div>