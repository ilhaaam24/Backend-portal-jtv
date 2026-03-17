<!DOCTYPE html>
<html>
<head>
    <title>Balasan Curhatan</title>
</head>
<body>
    <h3>Halo, {{ $data->name }}</h3>
    <p>Terima kasih telah mengirimkan keluh kesah Anda melalui Portal JTV.</p>
    
    <p><strong>Pesan Anda:</strong><br>
    "{{ $data->message }}"</p>
    <hr>
    <p><strong>Tanggapan Admin:</strong><br>
    {{ $replyMessage }}</p>
    
    <br>
    <p>Salam,<br>Tim Admin JTV</p>
</body>
</html>