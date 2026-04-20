<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lembar Disposisi - {{ $item->suratMasuk?->nomor_surat }}</title>
        <style>
            @page { size: A4 portrait; margin: 12mm; }
            body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; margin: 0; color: #000; }
            .page { box-sizing: border-box; }

            .doc { padding-top: 0; }
            .kop-wrap { display: block; }
            .kop { display: inline-block; text-align: left; font-weight: 700; line-height: 1.1; letter-spacing: .2px; }
            .kop .l1 { font-size: 13px; }
            .kop .l2 { font-size: 13px; }
            .kop .u { width: 100%; margin-top: 6px; border-top: 4px solid #000; }
            .kop-meta { margin-top: 6px; text-align: right; font-size: 11px; }
            .h-title { text-align: center; font-weight: 800; font-size: 18px; letter-spacing: .4px; margin: 8px 0 2px; }
            .h-no { text-align: center; font-weight: 800; font-size: 12.5px; margin-bottom: 8px; }

            .line { border-top: 2px solid #000; margin: 6px 0; }
            .fieldgrid { width: 100%; border-collapse: collapse; font-size: 11px; }
            .fieldgrid td { padding: 2px 4px; vertical-align: top; }
            .fieldgrid .lbl { width: 90px; font-weight: 700; }
            .fieldgrid .dots { width: 10px; text-align: center; font-weight: 700; }
            .fieldgrid .val { border-bottom: 1px solid #000; padding-bottom: 1px; }

            .section-title { text-align: center; font-weight: 800; font-size: 12px; padding: 6px 0; border-top: 2px solid #000; border-bottom: 2px solid #000; margin-top: 8px; }

            .cols { width: 100%; border-collapse: collapse; }
            .cols td { vertical-align: top; }
            .list { width: 100%; border-collapse: collapse; }
            .list td { padding: 2px 0; font-size: 11px; vertical-align: middle; line-height: 1.15; }
            .boxcell { width: 22px; }
            .boxchk { width: 16px; height: 16px; border: 2px solid #000; display: inline-block; vertical-align: middle; }
            .boxchk img { width: 12px; height: 12px; display: block; margin: 1px auto 0; }
            .label { white-space: nowrap; }
            .muted { font-size: 10px; font-weight: 700; }

            .pad { padding: 10px 6px 8px; }
            .note-area { border-top: 2px solid #000; padding-top: 8px; margin-top: 10px; }
            .note-title { font-weight: 800; font-size: 11px; margin-bottom: 6px; text-transform: uppercase; }
            .note-text { min-height: 64px; border: 1px dashed #000; padding: 8px; font-size: 12px; color: #0b2d5b; font-style: italic; }
            .qr { text-align: right; }
            .qr img { border: 1px solid #000; padding: 2px; width: 82px; height: 82px; background: #fff; }
            .qr .cap { font-size: 9px; margin-top: 4px; }
        </style>
    </head>
    <body>
        @php($surat = $item->suratMasuk)
        @php($toRole = strtolower(trim((string) ($item->keUser?->role ?? ''))))
        @php($instruksiLower = strtolower(trim((string) $item->instruksi)))
        @php($selectedDisposisi = strtoupper(trim((string) ($item->disposisi_kaskogartap ?? ''))))
        @php($tickSvg = 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"><path d="M10.2 3.1 5 9 1.8 6.1" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'))

        <div class="page">
            <div class="doc">
                <div class="kop-wrap">
                    <div class="kop">
                        <div class="l1">MARKAS BESAR TENTARA NASIONAL INDONESIA</div>
                        <div class="l2">KOMANDO GARNISUN TETAP I/JAKARTA</div>
                        <div class="u"></div>
                    </div>
                </div>
                <div class="kop-meta">
                    <div class="muted">Tgl Cetak: {{ now()->format('d-m-Y') }}</div>
                </div>

                <div class="h-title">LEMBAR DISPOSISI</div>
                <div class="h-no">NO : {{ $surat?->nomor_surat ?? '-' }}</div>

                <div class="line"></div>

                <table class="cols">
                    <tr>
                        <td style="width: 50%; padding-right: 10px;">
                            <table class="fieldgrid">
                                <tr>
                                    <td class="lbl">TERIMA DARI</td><td class="dots">:</td>
                                    <td class="val">{{ $surat?->pengirim ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">TANGGAL</td><td class="dots">:</td>
                                    <td class="val">{{ optional($surat?->tanggal_terima ?? $surat?->tanggal_surat)->format('d-m-Y') }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%; padding-left: 10px;">
                            <table class="fieldgrid">
                                <tr>
                                    <td class="lbl">PUKUL</td><td class="dots">:</td>
                                    <td class="val">{{ optional($item->created_at)->format('H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">STATUS</td><td class="dots">:</td>
                                    <td class="val">{{ strtoupper((string) $item->status) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <div class="line"></div>

                <table class="fieldgrid">
                    <tr>
                        <td class="lbl">NOMOR SURAT</td><td class="dots">:</td>
                        <td class="val">{{ $surat?->nomor_surat ?? '-' }}</td>
                        <td class="lbl">TANGGAL</td><td class="dots">:</td>
                        <td class="val">{{ optional($surat?->tanggal_surat)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">PERIHAL</td><td class="dots">:</td>
                        <td class="val" colspan="4">{{ $surat?->perihal ?? '-' }}</td>
                    </tr>
                </table>

                <div class="section-title">DITERUSKAN KEPADA</div>
                <div class="pad">
                    <table class="cols">
                        <tr>
                            <td style="width: 33.333%; padding-right: 8px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk">@if($toRole === 'asops')<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">ASOPS</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($toRole === 'asmin')<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">ASMIN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($toRole === 'kasatker')<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">KASATKER</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">DANDENPOM</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASINTEL</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASIOPS</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASIPERS</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASLOG</td></tr>
                                </table>
                            </td>
                            <td style="width: 33.333%; padding: 0 4px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0502/JU</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0503/JB</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0504/JS</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0505/JT</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0506/TGR</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0507/BKS</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASSUBKOGARTAP 0508/DPK</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">DANSATINTEL</td></tr>
                                </table>
                            </td>
                            <td style="width: 33.333%; padding-left: 8px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">DANDENMA</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KASET</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">PARSIRENGAR</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">PAKES</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">PAPEN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">KEPRIMKOP</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">SPRI</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk"></span></td><td class="label">LAINNYA</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section-title">DISPOSISI KASKOGARTAP I/JAKARTA</div>
                @php($mark = function (string $label, string $fallbackKey) use ($selectedDisposisi, $instruksiLower) {
                    if ($selectedDisposisi !== '') {
                        return $selectedDisposisi === $label;
                    }

                    return str_contains($instruksiLower, $fallbackKey);
                })
                <div class="pad">
                    <table class="cols">
                        <tr>
                            <td style="width: 25%; padding-right: 8px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('ACC', 'acc'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">ACC</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('ACARAKAN', 'acarakan'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">ACARAKAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('BALAS', 'balas'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">BALAS</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('BANTU', 'bantu'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">BANTU</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('DUKUNG', 'dukung'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">DUKUNG</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('IKUTI PERKEMBANGAN', 'ikuti'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">IKUTI PERKEMBANGAN</td></tr>
                                </table>
                            </td>
                            <td style="width: 25%; padding: 0 4px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('HADIR', 'hadir'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">HADIR</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('TIDAK HADIR', 'tidak hadir'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">TIDAK HADIR</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('KOORDINASIKAN', 'koordin'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">KOORDINASIKAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('LAPORKAN', 'lapor'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">LAPORKAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('PELAJARI & TELITI', 'pelajari') || $mark('PELAJARI & TELITI', 'teliti'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">PELAJARI &amp; TELITI</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('PEDOMANI', 'pedoman'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">PEDOMANI</td></tr>
                                </table>
                            </td>
                            <td style="width: 25%; padding: 0 4px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('SEBAGAI BAHAN', 'bahan'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">SEBAGAI BAHAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('SIAPKAN', 'siapkan') || $mark('SIAPKAN', 'simpan'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">SIAPKAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('TINDAK LANJUTI', 'tindak lanjut') || $mark('TINDAK LANJUTI', 'tindaklanjut'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">TINDAK LANJUTI</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('TANGGAPAN & SARAN', 'saran') || $mark('TANGGAPAN & SARAN', 'tanggapan'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">TANGGAPAN &amp; SARAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('TERUSKAN KE SATWAH', 'teruskan'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">TERUSKAN KE SATWAH</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('WAKILI', 'wakili'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">WAKILI</td></tr>
                                </table>
                            </td>
                            <td style="width: 25%; padding-left: 8px;">
                                <table class="list">
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('ARSIP', 'arsip'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">ARSIP</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('CATAT', 'catat'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">CATAT</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('INGATKAN', 'ingat'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">INGATKAN</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('MONITOR', 'monitor'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">MONITOR</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('UDL', 'udl'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">UDL</td></tr>
                                    <tr><td class="boxcell"><span class="boxchk">@if($mark('UDK', 'udk'))<img src="{{ $tickSvg }}" alt="V">@endif</span></td><td class="label">UDK</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="note-area">
                    <table class="cols">
                        <tr>
                            <td style="padding-right: 12px;">
                                <div class="note-title">Catatan Kaskogartap I/Jakarta</div>
                                <div class="note-text">{{ $item->instruksi }}</div>
                                <div style="font-size:10px; margin-top:6px;">
                                    Dari: {{ $item->dariUser?->name ?? '-' }} | Kepada: {{ $item->keUser?->name ?? '-' }}
                                </div>
                            </td>
                            <td class="qr" style="width: 120px;">
                                @php($qrUrl = $surat?->barcode ? route('barcode.show', $surat->barcode) : route('surat-masuk.show', $surat))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrUrl) }}" alt="QR">
                                <div class="cap">Scan detail surat</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
