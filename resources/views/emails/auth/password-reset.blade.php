<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slaptažodžio atkūrimas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #a5d6a7; /* švelni žalia linija */
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2ecc71; /* žalia */
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2e7d32; /* tamsesnė žalia */
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background-color: #2ecc71; /* žalias mygtukas */
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #27ae60; /* tamsesnė žalia */
        }
        .warning {
            background-color: #e8f5e9; /* žalsvas background */
            border: 1px solid #c8e6c9;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #33691e;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #c8e6c9; /* žalsva linija */
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .company-info {
            margin-top: 15px;
            font-size: 12px;
            color: #9e9e9e;
        }
        .url-box {
            word-break: break-all;
            background-color: #f1f8e9;
            padding: 10px;
            border-radius: 3px;
            font-family: monospace;
            border: 1px solid #dcedc8;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
        </div>
        
        <div class="greeting">Sveiki!</div>
        
        <div class="content">
            <p>Jūs gavote šį el. laišką, nes gavome slaptažodžio atkūrimo užklausą jūsų paskyrai.</p>
            
            <p>Norėdami atkurti slaptažodį, spustelėkite mygtuką žemiau:</p>
            
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">Atkurti slaptažodį</a>
            </div>
            
            <div class="warning">
                <strong>Svarbu:</strong> Šis slaptažodžio atkūrimo mygtukas galios {{ config('auth.passwords.users.expire') }} minučių.
            </div>
            
            <p>Jei jūs neprašėte slaptažodžio atkūrimo, nieko daryti nereikia – jūsų slaptažodis liks nepakeistas.</p>
            
            <p>Jei mygtukas neveikia, nukopijuokite šį URL į savo naršyklę:</p>
            <p class="url-box">{{ $actionUrl }}</p>
        </div>
        
        <div class="footer">
            <p>Pagarbiai,<br>{{ config('app.name') }} komanda</p>
            
            <div class="company-info">
                <p>Šis el. laiškas sugeneruotas automatiškai. Prašome neatsakyti į jį.</p>
                <p>Kilus klausimams – rašykite: {{ config('mail.from.address') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
