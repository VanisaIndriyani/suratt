<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lembar Disposisi - {{ $item->suratMasuk?->nomor_surat }}</title>
        <style>
            body { font-family: Arial, Helvetica, sans-serif; margin: 0; color: #000; }
            .page { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 12mm; box-sizing: border-box; }
            .top-actions { display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 10px; }
            .btn { border: 1px solid #0b2d5b; color: #0b2d5b; background: #fff; padding: 6px 10px; border-radius: 6px; font-size: 12px; text-decoration: none; cursor: pointer; }
            .btn-primary { background: #0b2d5b; color: #fff; }

            .doc { padding-top: 0; }
            .kop-wrap { display: flex; justify-content: flex-start; }
            .kop { display: inline-block; text-align: left; font-weight: 700; line-height: 1.1; letter-spacing: .2px; }
            .kop .l1 { font-size: 13px; }
            .kop .l2 { font-size: 13px; }
            .kop .u { width: 100%; margin-top: 6px; border-top: 4px solid #000; }
            .kop-meta { margin-top: 6px; text-align: right; font-size: 11px; }
            .h-title { text-align: center; font-weight: 800; font-size: 18px; letter-spacing: .4px; margin: 8px 0 2px; }
            .h-no { text-align: center; font-weight: 800; font-size: 12.5px; margin-bottom: 8px; }

            .line { border-top: 2px solid #000; margin: 6px 0; }
            .rowline { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
            .fieldgrid { width: 100%; border-collapse: collapse; font-size: 11px; }
            .fieldgrid td { padding: 2px 4px; }
            .fieldgrid .lbl { width: 90px; font-weight: 700; }
            .fieldgrid .dots { width: 10px; text-align: center; font-weight: 700; }
            .fieldgrid .val { border-bottom: 1px solid #000; padding-bottom: 1px; }

            .section-title { text-align: center; font-weight: 800; font-size: 12px; padding: 6px 0; border-top: 2px solid #000; border-bottom: 2px solid #000; margin-top: 8px; }

            .checkgrid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 10px 6px 8px; }
            .checkcol { display: grid; gap: 7px; }
            .chk { display: grid; grid-template-columns: 18px 1fr; gap: 8px; align-items: center; font-size: 11px; }
            .boxchk { width: 16px; height: 16px; border: 2px solid #000; display: grid; place-items: center; font-size: 13px; line-height: 1; font-weight: 800; }
            .muted { font-size: 10px; font-weight: 700; }

            .actiongrid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding: 10px 6px 8px; }
            .actioncol { display: grid; gap: 7px; }

            .note-area { border-top: 2px solid #000; padding-top: 8px; display: grid; grid-template-columns: 1fr 120px; gap: 12px; align-items: end; margin-top: 10px; }
            .note-title { font-weight: 800; font-size: 11px; margin-bottom: 6px; text-transform: uppercase; }
            .note-text { min-height: 70px; border: 1px dashed #000; padding: 8px; font-size: 12px; color: #0b2d5b; font-style: italic; }
            .qr { text-align: right; }
            .qr img { border: 1px solid #000; padding: 4px; width: 100px; height: 100px; background: #fff; }
            .qr .cap { font-size: 9px; margin-top: 4px; }

            @media print {
                .top-actions { display: none; }
                .page { margin: 0; width: auto; min-height: auto; padding: 0; }
            }
        </style>
    </head>
    <body>
        @php($surat = $item->suratMasuk)
        @php($toRole = strtolower(trim((string) ($item->keUser?->role ?? ''))))
        @php($instruksiLower = strtolower(trim((string) $item->instruksi)))
        @php($selectedDisposisi = strtoupper(trim((string) ($item->disposisi_kaskogartap ?? ''))))

        <div class="page">
            <div class="top-actions">
                <a class="btn" href="{{ url()->previous() }}">Kembali</a>
                <button class="btn btn-primary" onclick="window.print()">Unduh / Cetak PDF</button>
            </div>

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

                <div class="rowline">
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
                </div>

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
                <div class="checkgrid">
                    <div class="checkcol">
                        <div class="chk"><div class="boxchk">{{ $toRole === 'asops' ? '✓' : '' }}</div><div>ASOPS</div></div>
                        <div class="chk"><div class="boxchk">{{ $toRole === 'asmin' ? '✓' : '' }}</div><div>ASMIN</div></div>
                        <div class="chk"><div class="boxchk">{{ $toRole === 'kasatker' ? '✓' : '' }}</div><div>KASATKER</div></div>
                        <div class="chk"><div class="boxchk"></div><div>DANDENPOM</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASINTEL</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASIOPS</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASIPERS</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASLOG</div></div>
                    </div>
                    <div class="checkcol">
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0502/JU</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0503/JB</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0504/JS</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0505/JT</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0506/TGR</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0507/BKS</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASSUBKOGARTAP 0508/DPK</div></div>
                        <div class="chk"><div class="boxchk"></div><div>DANSATINTEL</div></div>
                    </div>
                    <div class="checkcol">
                        <div class="chk"><div class="boxchk"></div><div>DANDENMA</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KASET</div></div>
                        <div class="chk"><div class="boxchk"></div><div>PARSIRENGAR</div></div>
                        <div class="chk"><div class="boxchk"></div><div>PAKES</div></div>
                        <div class="chk"><div class="boxchk"></div><div>PAPEN</div></div>
                        <div class="chk"><div class="boxchk"></div><div>KEPRIMKOP</div></div>
                        <div class="chk"><div class="boxchk"></div><div>SPRI</div></div>
                        <div class="chk"><div class="boxchk"></div><div>LAINNYA</div></div>
                    </div>
                </div>

                <div class="section-title">DISPOSISI KASKOGARTAP I/JAKARTA</div>
                @php($mark = function (string $label, string $fallbackKey) use ($selectedDisposisi, $instruksiLower) {
                    if ($selectedDisposisi !== '') {
                        return $selectedDisposisi === $label;
                    }

                    return str_contains($instruksiLower, $fallbackKey);
                })
                <div class="actiongrid">
                    <div class="actioncol">
                        <div class="chk"><div class="boxchk">{{ $mark('ACC', 'acc') ? '✓' : '' }}</div><div>ACC</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('ACARAKAN', 'acarakan') ? '✓' : '' }}</div><div>ACARAKAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('BALAS', 'balas') ? '✓' : '' }}</div><div>BALAS</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('BANTU', 'bantu') ? '✓' : '' }}</div><div>BANTU</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('DUKUNG', 'dukung') ? '✓' : '' }}</div><div>DUKUNG</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('IKUTI PERKEMBANGAN', 'ikuti') ? '✓' : '' }}</div><div>IKUTI PERKEMBANGAN</div></div>
                    </div>
                    <div class="actioncol">
                        <div class="chk"><div class="boxchk">{{ $mark('HADIR', 'hadir') ? '✓' : '' }}</div><div>HADIR</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('TIDAK HADIR', 'tidak hadir') ? '✓' : '' }}</div><div>TIDAK HADIR</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('KOORDINASIKAN', 'koordin') ? '✓' : '' }}</div><div>KOORDINASIKAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('LAPORKAN', 'lapor') ? '✓' : '' }}</div><div>LAPORKAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('PELAJARI & TELITI', 'pelajari') || $mark('PELAJARI & TELITI', 'teliti') ? '✓' : '' }}</div><div>PELAJARI &amp; TELITI</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('PEDOMANI', 'pedoman') ? '✓' : '' }}</div><div>PEDOMANI</div></div>
                    </div>
                    <div class="actioncol">
                        <div class="chk"><div class="boxchk">{{ $mark('SEBAGAI BAHAN', 'bahan') ? '✓' : '' }}</div><div>SEBAGAI BAHAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('SIAPKAN', 'siapkan') || $mark('SIAPKAN', 'simpan') ? '✓' : '' }}</div><div>SIAPKAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('TINDAK LANJUTI', 'tindak lanjut') || $mark('TINDAK LANJUTI', 'tindaklanjut') ? '✓' : '' }}</div><div>TINDAK LANJUTI</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('TANGGAPAN & SARAN', 'saran') || $mark('TANGGAPAN & SARAN', 'tanggapan') ? '✓' : '' }}</div><div>TANGGAPAN &amp; SARAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('TERUSKAN KE SATWAH', 'teruskan') ? '✓' : '' }}</div><div>TERUSKAN KE SATWAH</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('WAKILI', 'wakili') ? '✓' : '' }}</div><div>WAKILI</div></div>
                    </div>
                    <div class="actioncol">
                        <div class="chk"><div class="boxchk">{{ $mark('ARSIP', 'arsip') ? '✓' : '' }}</div><div>ARSIP</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('CATAT', 'catat') ? '✓' : '' }}</div><div>CATAT</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('INGATKAN', 'ingat') ? '✓' : '' }}</div><div>INGATKAN</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('MONITOR', 'monitor') ? '✓' : '' }}</div><div>MONITOR</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('UDL', 'udl') ? '✓' : '' }}</div><div>UDL</div></div>
                        <div class="chk"><div class="boxchk">{{ $mark('UDK', 'udk') ? '✓' : '' }}</div><div>UDK</div></div>
                    </div>
                </div>

                <div class="note-area">
                    <div>
                        <div class="note-title">Catatan Kaskogartap I/Jakarta</div>
                        <div class="note-text">{{ $item->instruksi }}</div>
                        <div style="font-size:10px; margin-top:6px;">
                            Dari: {{ $item->dariUser?->name ?? '-' }} | Kepada: {{ $item->keUser?->name ?? '-' }}
                        </div>
                    </div>
                    <div class="qr">
                        @php($qrUrl = $surat?->barcode ? route('barcode.show', $surat->barcode) : route('surat-masuk.show', $surat))
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrUrl) }}" alt="QR">
                        <div class="cap">Scan detail surat</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
