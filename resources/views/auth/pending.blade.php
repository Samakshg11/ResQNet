<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Verification Pending — ResQNet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #0a0a0a;
            --card: #161616;
            --border: #1e1e1e;
            --text: #e8e0d8;
            --text2: #8a7f75;
            --accent: #e8735a;
            --peach: #f0c4a8;
            --yellow: #c9a84c;
            --serif: 'DM Serif Display', serif;
            --sans: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
        }

        .pending-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-top: 4px solid var(--yellow);
            border-radius: 16px;
            padding: 48px;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
        }

        .icon-wrap {
            width: 80px;
            height: 80px;
            background: rgba(201, 168, 76, 0.1);
            color: var(--yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 24px;
        }

        .title {
            font-family: var(--serif);
            font-size: 28px;
            margin-bottom: 16px;
        }

        .message {
            color: var(--text2);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn:hover {
            border-color: var(--text2);
            background: rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>
    <div class="pending-card">
        <div class="icon-wrap">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <h1 class="title">Verification Pending</h1>
        <p class="message">
            Your agency application has been submitted and is currently pending verification by the NDMA. You will receive access to the command center once approved. This usually takes within 24 hours.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</body>
</html>
