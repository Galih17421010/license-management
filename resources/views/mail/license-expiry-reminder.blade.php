<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .alert-info {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            color: #1976D2;
        }
        .alert-warning {
            background: #fff3e0;
            border-left: px solid #ff9800;
            color: #f57c00;
        }
        .alert-danger {
            background: #ffebee;
            border-left: 4px solid #f44336;
            color:  #c62828;
        }
        .detail-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            width: 180px;
            color: #555;
        }
        .default-value {
            flex: 1;
            color: #333;
        }
        .footer {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 0 0 10px 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .countdown {
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Reminder Perizinan</h1>
        <p style="margin: 10px 0 0 0;">Sistem Manajemen Perizinan PT Kinden Indonesia</p>
    </div>

    <div class="content">
        <div class="alert alert-{{ $urgencyLevel }}">
            {{ $actionText }}
        </div>

        <div class="countdown">
            @if ($reminderDay > 0)
                {{ $reminderDay }} Hari               
            @else
                HARI INI!
            @endif
        </div>

        <div class="detail-box">
            <h2 style="margin-top: 0; color: #667eea">Detail Perizinan</h2>

            <div class="detail-row">
                <div class="detail-label">Nama Perizinan:</div>
                <div class="detail-value">{{ $license->nama_perizinan }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jenis Perizinan:</div>
                <div class="detail-value">{{ $license->jenis_perizinan }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Instansi Penerbit:</div>
                <div class="detail-value">{{ $license->instansi_penerbit }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Nomor Izin:</div>
                <div class="detail-value"><strong>{{ $license->nomor_izin }}</strong></div>
            </div>       
            
            <div class="detail-row">
                <div class="detail-label">Tanggal Terbit:</div>
                <div class="detail-value">{{ $license->tanggal_terbit->format('d F Y') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal Berakhir:</div>
                <div class="detail-value"><strong style="color: #f44336;">{{ $license->tanggal_berakhir->format('d F Y') }}</strong></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Penanggung Jawab:</div>
                <div class="detail-value">{{ $license->penanggung_jawab }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                    <span style="
                        padding: 5px 10px;
                        border-radius: 3px;
                        font-weight: bold;
                        background: {{ $license->status === 'AKTIF' ? '#4caf50' : ($license->status === 'AKAN HABIS' ? '#ff9800' : '#f44336') }};
                        color: white;
                    ">
                        {{ $license->status }}
                    </span>
                </div>
            </div>
        </div>

        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 20px;">
            <p style="margin: 0; color: #856404;">
                <strong>Catatan Penting:</strong> <br>
                Email ini dikirim secara otomatis oleh sistem. Harap segera koordinasikan dengan tim terkait untuk proses perpanjangan perizinan.
            </p>
        </div>
    </div>

    <div class="footer">
        <p style="margin: 5px 0;">Email ini dikirim pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        <p style="margin: 5px 0;">Sistem Manajemen Perizinan PT Kinden Indonesia</p>
        <p style="margin: 5px 0;">©️ {{ date('Y') }} - Automated Email System</p>
    </div>
</body>
</html>