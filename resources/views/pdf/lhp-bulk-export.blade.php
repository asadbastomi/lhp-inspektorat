<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LHP Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8px;
            color: #000;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .no-wrap {
            white-space: nowrap;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <h1>{{ $title ?? 'Laporan Hasil Pemeriksaan (LHP)' }}</h1>
    @if (!empty($subtitle))
        <h2 style="text-align: center; font-size: 10px; font-weight: normal; margin-top: -10px; margin-bottom: 20px;">
            {{ $subtitle }}
        </h2>
    @endif

    @if ($lhps->isEmpty())
        <p style="text-align: center;">Tidak ada data LHP yang ditemukan untuk kriteria yang dipilih.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th class="no-wrap" style="width: 15%;">Nomor & Tanggal LHP</th>
                    <th style="width: 20%;">Temuan</th>
                    <th style="width: 20%;">Penyebab</th>
                    <th style="width: 20%;">Rekomendasi</th>
                    <th style="width: 25%;">Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lhps as $lhp)
                    @if ($lhp->temuans->isEmpty())
                        <tr>
                            <td class="no-wrap">
                                <strong>{{ $lhp->nomor_lhp }}</strong><br>
                                {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                            </td>
                            <td colspan="4" style="text-align: center;">Tidak ada temuan.</td>
                        </tr>
                    @else
                        @foreach ($lhp->temuans as $temuan)
                            @php
                                $rekomendasiCount = $temuan->rekomendasis->count();
                                $temuanRowspan = 0;
                                if ($rekomendasiCount > 0) {
                                    foreach ($temuan->rekomendasis as $rekomendasi) {
                                        $temuanRowspan +=
                                            $rekomendasi->tindakLanjuts->count() > 0
                                                ? $rekomendasi->tindakLanjuts->count()
                                                : 1;
                                    }
                                } else {
                                    $temuanRowspan = 1;
                                }
                                $isFirstRekomendasi = true;
                            @endphp

                            @if ($rekomendasiCount > 0)
                                @foreach ($temuan->rekomendasis as $rekomendasi)
                                    @php
                                        $tindakLanjutCount = $rekomendasi->tindakLanjuts->count();
                                        $rekomendasiRowspan = $tindakLanjutCount > 0 ? $tindakLanjutCount : 1;
                                        $isFirstTindakLanjut = true;
                                    @endphp

                                    @if ($tindakLanjutCount > 0)
                                        @foreach ($rekomendasi->tindakLanjuts as $tindakLanjut)
                                            <tr>
                                                @if ($isFirstRekomendasi && $isFirstTindakLanjut)
                                                    <td class="no-wrap" rowspan="{{ $temuanRowspan }}">
                                                        <strong>{{ $lhp->nomor_lhp }}</strong><br>
                                                        {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td rowspan="{{ $temuanRowspan }}">
                                                        {{ $loop->parent->parent->iteration }}. {{ $temuan->rincian }}
                                                        @php $totalKerugian = $temuan->rekomendasis->sum('besaran_temuan'); @endphp
                                                        @if ($totalKerugian > 0)
                                                            <br><br><strong>NILAI KERUGIAN
                                                                (Rp.)
                                                                :</strong><br>{{ number_format($totalKerugian, 2, ',', '.') }}
                                                        @endif
                                                    </td>
                                                    <td rowspan="{{ $temuanRowspan }}">
                                                        @if ($temuan->penyebab)
                                                            {{ $loop->parent->parent->iteration }}.
                                                            {{ $temuan->penyebab }}
                                                        @endif
                                                    </td>
                                                @endif

                                                @if ($isFirstTindakLanjut)
                                                    <td rowspan="{{ $rekomendasiRowspan }}">
                                                        {{ $loop->parent->iteration }}. {{ $rekomendasi->rincian }}
                                                    </td>
                                                @endif

                                                <td>
                                                    {{ $loop->iteration }}.
                                                    {{ $tindakLanjut->description ?? 'Tidak ada uraian' }}
                                                    <br><small><strong>File:</strong>
                                                        {{ $tindakLanjut->file_name ?? 'N/A' }} | <strong>Tgl:</strong>
                                                        {{ $tindakLanjut->created_at ? $tindakLanjut->created_at->format('d-m-Y') : 'N/A' }}</small>
                                                </td>
                                            </tr>
                                            @php
                                                $isFirstRekomendasi = false;
                                                $isFirstTindakLanjut = false;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            @if ($isFirstRekomendasi)
                                                <td class="no-wrap" rowspan="{{ $temuanRowspan }}">
                                                    <strong>{{ $lhp->nomor_lhp }}</strong><br>
                                                    {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                                                </td>
                                                <td rowspan="{{ $temuanRowspan }}">
                                                    {{ $loop->parent->iteration }}. {{ $temuan->rincian }}
                                                    @php $totalKerugian = $temuan->rekomendasis->sum('besaran_temuan'); @endphp
                                                    @if ($totalKerugian > 0)
                                                        <br><br><strong>NILAI KERUGIAN
                                                            (Rp.):</strong><br>{{ number_format($totalKerugian, 2, ',', '.') }}
                                                    @endif
                                                </td>
                                                <td rowspan="{{ $temuanRowspan }}">
                                                    @if ($temuan->penyebab)
                                                        {{ $loop->parent->iteration }}. {{ $temuan->penyebab }}
                                                    @endif
                                                </td>
                                            @endif

                                            <td>{{ $loop->iteration }}. {{ $rekomendasi->rincian }}</td>
                                            <td>N/A</td>
                                        </tr>
                                        @php $isFirstRekomendasi = false; @endphp
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="no-wrap">
                                        <strong>{{ $lhp->nomor_lhp }}</strong><br>
                                        {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                                    </td>
                                    <td>{{ $loop->iteration }}. {{ $temuan->rincian }}</td>
                                    <td>
                                        @if ($temuan->penyebab)
                                            {{ $loop->iteration }}. {{ $temuan->penyebab }}
                                        @endif
                                    </td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</body>

</html>
