<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cetak Surat Keluar - {{ $item->nomor_surat }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @media print {
                .no-print { display: none !important; }
                body { background: white !important; }
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="no-print p-3">
            <div class="d-flex flex-wrap gap-2">
                <button onclick="window.print()" class="btn btn-primary">Print</button>
                <a href="{{ route('surat-keluar.show', $item) }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>

        <div class="container my-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="44" height="44" class="img-fluid">
                        <div>
                            <div class="h5 mb-0">Surat Keluar</div>
                            <div class="text-muted small">{{ config('app.name') }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th style="width: 180px;">Nomor Surat</th>
                                    <td>{{ $item->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Surat</th>
                                    <td>{{ optional($item->tanggal_surat)->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>Tujuan</th>
                                    <td>{{ $item->tujuan }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <td>{{ $item->jenis_surat ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Perihal</th>
                                    <td>{{ $item->perihal }}</td>
                                </tr>
                                <tr>
                                    <th>Barcode</th>
                                    <td class="text-break">{{ $item->barcode }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
