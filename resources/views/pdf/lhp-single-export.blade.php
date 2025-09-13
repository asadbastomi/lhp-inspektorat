<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LHP Export</title>
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

        ul {
            margin: 0;
            padding-left: 15px;
        }

        li {
            margin-bottom: 5px;
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
                            <strong>{{ $lhp->nomor_lhp }}</strong>
                            <br>
                            {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                        </td>
                        <td colspan="4" style="text-align: center;">Tidak ada temuan.</td>
                    </tr>
                @else
                    @foreach ($lhp->temuans as $temuan)
                        @php
                            $totalRows = 0;
                            foreach ($temuan->rekomendasis as $rekomendasi) {
                                $totalRows += max(1, $rekomendasi->tindakLanjuts->count());
                            }
                            if ($totalRows === 0) {
                                $totalRows = 1;
                            }
                            $firstTemuan = true;
                        @endphp

                        @if ($temuan->rekomendasis->isEmpty())
                            <tr>
                                <td class="no-wrap">
                                    <strong>{{ $lhp->nomor_lhp }}</strong>
                                    <br>
                                    {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    {{ $loop->iteration }}. {{ $temuan->rincian }}
                                </td>
                                <td>
                                    @if ($temuan->penyebab)
                                        {{ $loop->iteration }}. {{ $temuan->penyebab }}
                                    @endif
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @else
                            @foreach ($temuan->rekomendasis as $rekomendasi)
                                @php
                                    $tindakLanjutCount = $rekomendasi->tindakLanjuts->count();
                                    $rowspan = max(1, $tindakLanjutCount);
                                    $firstRekomendasi = true;
                                @endphp

                                @if ($tindakLanjutCount > 0)
                                    @foreach ($rekomendasi->tindakLanjuts as $tindakLanjut)
                                        <tr>
                                            @if ($firstTemuan)
                                                <td rowspan="{{ $totalRows }}" class="no-wrap">
                                                    <strong>{{ $lhp->nomor_lhp }}</strong>
                                                    <br>
                                                    {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                                                </td>
                                                <td rowspan="{{ $totalRows }}">
                                                    {{ $loop->parent->parent->iteration }}. {{ $temuan->rincian }}
                                                    @php $totalKerugian = $temuan->rekomendasis->sum('besaran_temuan'); @endphp
                                                    @if ($totalKerugian > 0)
                                                        <br><br>
                                                        <strong>NILAI KERUGIAN NEGARA / KEWAJIBAN PENYETORAN
                                                            (Rp.)
                                                            :</strong>
                                                        <br>
                                                        {{ number_format($totalKerugian, 2, ',', '.') }}
                                                    @endif
                                                </td>
                                                <td rowspan="{{ $totalRows }}">
                                                    @if ($temuan->penyebab)
                                                        {{ $loop->parent->parent->iteration }}.
                                                        {{ $temuan->penyebab }}
                                                    @endif
                                                </td>
                                                @php $firstTemuan = false; @endphp
                                            @endif

                                            @if ($firstRekomendasi)
                                                <td rowspan="{{ $rowspan }}">{{ $loop->parent->iteration }}.
                                                    {{ $rekomendasi->rincian }}</td>
                                                @php $firstRekomendasi = false; @endphp
                                            @endif

                                            <td>
                                                <ol style="list-style-type: decimal; padding-left: 20px; margin: 0;">
                                                    <li>
                                                        {{ $tindakLanjut->uraian ?? 'Tidak ada uraian' }} <br>
                                                        <small>
                                                            <strong>File:</strong>
                                                            {{ $tindakLanjut->file_name ?? 'Tidak ada file' }} <br>
                                                            <strong>Tanggal:</strong>
                                                            {{ $tindakLanjut->created_at ? $tindakLanjut->created_at->format('d-m-Y') : 'N/A' }}
                                                        </small>
                                                    </li>
                                                </ol>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        @if ($firstTemuan)
                                            <td rowspan="{{ $totalRows }}" class="no-wrap">
                                                <strong>{{ $lhp->nomor_lhp }}</strong>
                                                <br>
                                                {{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}
                                            </td>
                                            <td rowspan="{{ $totalRows }}">
                                                {{ $loop->parent->iteration }}. {{ $temuan->rincian }}
                                            </td>
                                            <td rowspan="{{ $totalRows }}">
                                                @if ($temuan->penyebab)
                                                    {{ $loop->parent->iteration }}. {{ $temuan->penyebab }}
                                                @endif
                                            </td>
                                            @php $firstTemuan = false; @endphp
                                        @endif
                                        <td rowspan="{{ $rowspan }}">{{ $loop->iteration }}.
                                            {{ $rekomendasi->rincian }}</td>
                                        <td>N/A</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>
