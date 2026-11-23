<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101114">
    <title>ARENA X — Киберспортивные турниры</title>
    <style>
        :root{color-scheme:dark;--bg:#101114;--panel:#191b20;--line:#2a2d34;--text:#f4f5f7;--muted:#969ba6;--accent:#d8ff45}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:15px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
        a{color:inherit;text-decoration:none}.shell{max-width:1180px;margin:auto;padding:0 28px}
        header{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}
        .brand{font-size:22px;font-weight:850;letter-spacing:.12em}.brand span{color:var(--accent)}
        nav{display:flex;gap:30px;color:#c1c4cb;font-size:14px}.nav-action{border:1px solid #464a53;padding:10px 16px;border-radius:8px;font-weight:650}
        .hero{padding:92px 0 74px;display:grid;grid-template-columns:1.25fr .75fr;gap:52px;align-items:center}
        .eyebrow{color:var(--accent);font-size:12px;letter-spacing:.18em;font-weight:800;text-transform:uppercase}
        h1{font-size:clamp(42px,6vw,72px);line-height:1.02;letter-spacing:-.055em;margin:18px 0 22px;max-width:720px}
        .lead{color:var(--muted);font-size:17px;max-width:560px}.actions{display:flex;gap:12px;margin-top:32px;flex-wrap:wrap}
        .button{padding:14px 20px;border-radius:8px;background:var(--accent);color:#15170d;font-weight:800}.button.secondary{background:#202229;color:var(--text);border:1px solid #383b43}
        .feature{background:linear-gradient(145deg,#202329,#17191e);border:1px solid var(--line);border-radius:18px;padding:28px;min-height:260px;display:flex;flex-direction:column;justify-content:space-between}
        .feature-top{display:flex;justify-content:space-between;color:var(--muted);font-size:12px;letter-spacing:.1em;text-transform:uppercase}
        .feature h2{font-size:28px;line-height:1.1;margin:38px 0 8px}.feature p{color:var(--muted);margin:0}
        .status{color:var(--accent);font-size:12px;font-weight:800}.section-head{display:flex;justify-content:space-between;align-items:end;margin:24px 0}
        .section-head h2{font-size:25px;margin:0}.section-head p{color:var(--muted);margin:0}
        .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;padding-bottom:80px}.card{background:var(--panel);border:1px solid var(--line);border-radius:12px;padding:22px}
        .card small{color:var(--muted)}.card h3{margin:18px 0 8px;font-size:19px}.card p{color:var(--muted);margin:0}
        footer{border-top:1px solid var(--line);padding:24px 0;color:var(--muted);font-size:13px}
        @media(max-width:760px){.shell{padding:0 18px}nav{display:none}.hero{grid-template-columns:1fr;padding:60px 0 45px;gap:28px}.cards{grid-template-columns:1fr}.section-head{display:block}.section-head p{margin-top:6px}}
    </style>
</head>
<body>
<div class="shell">
    <header>
        <a class="brand" href="/">ARENA <span>X</span></a>
        <nav aria-label="Основная навигация">
            <a href="#tournaments">Турниры</a>
            <a href="#about">О платформе</a>
            <a href="#format">Форматы</a>
        </nav>
        <a class="nav-action" href="/tournaments">Открыть турниры</a>
    </header>

    <main>
        <section class="hero" id="about">
            <div>
                <div class="eyebrow">Твоя игра. Твоя команда. Твой результат.</div>
                <h1>Время выйти<br>на арену.</h1>
                <p class="lead">Платформа локальных киберспортивных турниров. Собирай команду, подавай заявку и доказывай своё мастерство.</p>
                <div class="actions">
                    <a class="button" href="/tournaments">Смотреть турниры</a>
                    <a class="button secondary" href="#format">Как это работает</a>
                </div>
            </div>
            <aside class="feature">
                <div class="feature-top"><span>ARENA X / 2026</span><span class="status">● ONLINE</span></div>
                <div>
                    <h2>Соревнование начинается здесь.</h2>
                    <p>Единое пространство для игроков, команд и организаторов.</p>
                </div>
            </aside>
        </section>

        <section id="tournaments">
            <div class="section-head">
                <h2>Турнирная платформа</h2>
                <p>Всё необходимое для проведения соревнований</p>
            </div>
            <div class="cards">
                <article class="card"><small>01 / ИГРОКИ</small><h3>Создавай профиль</h3><p>Укажи игровой никнейм, найди команду и участвуй в соревнованиях.</p></article>
                <article class="card"><small>02 / КОМАНДЫ</small><h3>Играй вместе</h3><p>Собирай состав, управляй участниками и подавай командные заявки.</p></article>
                <article class="card"><small>03 / ТУРНИРЫ</small><h3>Борись за победу</h3><p>Следи за расписанием, матчами, результатами и турнирной таблицей.</p></article>
            </div>
        </section>
        <section id="format" class="section-head"><div><h2>От регистрации до финала</h2><p>Путь участника будет доступен в личном кабинете после реализации авторизации.</p></div></section>
    </main>
    <footer>ARENA X · Учебный проект · 2026</footer>
</div>
</body>
</html>
