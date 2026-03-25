<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance | PlayCricket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Sora:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #ff0f20;
            --accent-dark: #e60d1d;
            --ink: #0f172a;
            --night: #111111;
            --mist: #f8fafc;
            --slate: #cbd5e1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(255, 15, 32, 0.18), transparent 60%),
                radial-gradient(900px 500px at 90% 10%, rgba(15, 23, 42, 0.18), transparent 55%),
                linear-gradient(120deg, #fdf0f1 0%, #f6f7fb 45%, #f1f5f9 100%);
            min-height: 100vh;
        }

        .scene {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px 64px;
            position: relative;
            overflow: hidden;
        }

        .scene::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("{{ asset('images/bg-cricket-pattern.svg') }}");
            background-size: 280px;
            opacity: 0.25;
            mix-blend-mode: multiply;
        }

        .wrap {
            position: relative;
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
            z-index: 1;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--night);
            font-size: 12px;
        }

        .brand-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 6px rgba(255, 15, 32, 0.12);
        }

        .hero {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 28px;
            padding: 36px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.15);
            border: 1px solid rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            gap: 20px;
            backdrop-filter: blur(6px);
            animation: rise 0.8s ease-out both;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(255, 15, 32, 0.12);
            color: var(--accent);
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .title {
            font-family: "Bebas Neue", sans-serif;
            font-size: clamp(42px, 6vw, 80px);
            letter-spacing: 0.02em;
            margin: 0;
            color: var(--night);
        }

        .title span {
            color: var(--accent);
        }

        .subtitle {
            font-size: 16px;
            line-height: 1.7;
            color: rgba(15, 23, 42, 0.72);
            margin: 0;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
        }

        .button {
            padding: 12px 20px;
            border-radius: 14px;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 18px 40px rgba(255, 15, 32, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .button:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 22px 50px rgba(255, 15, 32, 0.3);
        }

        .secondary {
            font-size: 13px;
            color: rgba(15, 23, 42, 0.6);
        }

        .panel {
            background: var(--night);
            color: #fff;
            border-radius: 28px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 22px;
            position: relative;
            overflow: hidden;
            animation: rise 0.8s ease-out 0.1s both;
        }

        .panel::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 15, 32, 0.35), transparent 60%);
            top: -80px;
            right: -80px;
        }

        .panel h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
        }

        .status {
            font-family: "Bebas Neue", sans-serif;
            font-size: 42px;
            letter-spacing: 0.03em;
            margin: 0;
        }

        .status span {
            color: var(--accent);
        }

        .list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 12px;
        }

        .list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.78);
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 6px rgba(255, 15, 32, 0.18);
            flex: 0 0 10px;
        }

        .footer {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 700px) {
            .hero,
            .panel {
                padding: 28px;
            }

            .actions {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .hero,
            .panel {
                animation: none;
            }

            .button {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <main class="scene">
        <div class="wrap">
            <section class="hero">
                <div class="brand">
                    <span class="brand-dot"></span>
                    <span>PlayCricket</span>
                </div>
                <span class="badge">Maintenance Break</span>
                <h1 class="title">We are tuning the <span>nets</span>.</h1>
                <p class="subtitle">
                    Our booking and facility tools are getting a quick upgrade. We will be back online shortly.
                </p>
                <div class="actions">
                    <a class="button" href="/">Try Again</a>
                    <span class="secondary">If this continues, please check back in a few minutes.</span>
                </div>
            </section>
            <aside class="panel">
                <h2>Live Status</h2>
                <p class="status">System <span>Maintenance</span></p>
                <ul class="list">
                    <li><span class="dot"></span>Booking engine refresh</li>
                    <li><span class="dot"></span>Scoreboard data sync</li>
                    <li><span class="dot"></span>Performance tuning</li>
                </ul>
                <p class="footer">Thank you for your patience. We are sprinting back.</p>
            </aside>
        </div>
    </main>
</body>
</html>
