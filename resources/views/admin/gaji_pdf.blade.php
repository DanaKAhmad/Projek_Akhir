<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Slip Gaji Karyawan</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Hadir</th>
                <th>Alpha</th>
                <th>Hari Kerja</th>
                <th>Gaji Harian</th>
                <th>Gaji Pokok</th>
                <th>Potongan</th>
                <th>Gaji Bersih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gajiData as $data)
            <tr>
                <td>{{ $data['nama'] }}</td>
                <td>{{ $data['hadir'] }}</td>
                <td>{{ $data['alpha'] }}</td>
                <td>{{ $data['hari_kerja'] }}</td>
                <td>Rp {{ number_format($data['gaji_harian']) }}</td>
                <td>Rp {{ number_format($data['gaji_pokok']) }}</td>
                <td>Rp {{ number_format($data['potongan']) }}</td>
                <td><strong>Rp {{ number_format($data['gaji_bersih']) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
