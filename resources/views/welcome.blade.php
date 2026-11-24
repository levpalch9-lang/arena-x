<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101114">
    <meta name="description" content="ARENA X — платформа локальных киберспортивных турниров. Команды, матчи и соревнования в одном месте.">
    <title>ARENA X — Киберспортивные турниры</title>
    <link rel="preconnect" href="https://images.unsplash.com">
    <style>
        :root{color-scheme:dark;--bg:#101114;--panel:#191b20;--line:#2a2d34;--text:#f4f5f7;--muted:#969ba6;--accent:#d8ff45}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--text);font:15px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;overflow-x:hidden}
        body:before{content:"";position:fixed;inset:0;pointer-events:none;z-index:-1;background:radial-gradient(ellipse at 78% 20%,rgba(216,255,69,.075),transparent 34%),radial-gradient(ellipse at 8% 55%,rgba(69,116,255,.06),transparent 32%)}
        a{color:inherit;text-decoration:none}.shell{max-width:1180px;margin:auto;padding:0 28px}
        header{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;animation:drop .7s both}
        .brand{font-size:22px;font-weight:900;letter-spacing:.12em}.brand span{color:var(--accent)}
        nav{display:flex;gap:30px;color:#c1c4cb;font-size:14px}nav a,.nav-action{transition:color .2s,border-color .2s,transform .2s}nav a:hover{color:var(--accent)}
        .nav-action{border:1px solid #464a53;padding:10px 16px;border-radius:8px;font-weight:700}.nav-action:hover{border-color:var(--accent);transform:translateY(-2px)}
        .hero{padding:92px 0 78px;display:grid;grid-template-columns:1.08fr .92fr;gap:54px;align-items:center;min-height:510px}
        .eyebrow{color:var(--accent);font-size:12px;letter-spacing:.18em;font-weight:850;text-transform:uppercase}
        h1{font-size:clamp(46px,6vw,76px);line-height:.99;letter-spacing:-.065em;margin:19px 0 24px;max-width:720px}
        .lead{color:var(--muted);font-size:17px;max-width:560px}.actions{display:flex;gap:12px;margin-top:32px;flex-wrap:wrap}
        .button{display:inline-flex;align-items:center;justify-content:center;padding:14px 20px;border-radius:9px;background:var(--accent);color:#15170d;font-weight:850;transition:transform .22s,box-shadow .22s,background .22s}.button:hover{transform:translateY(-3px);box-shadow:0 12px 35px #d8ff4533;background:#e4ff72}.button.secondary{background:#202229;color:var(--text);border:1px solid #383b43}.button.secondary:hover{border-color:#777d88;box-shadow:0 12px 30px #0005}
        .visual{position:relative;min-height:390px;border:1px solid #ffffff20;border-radius:22px;overflow:hidden;background:#202329;box-shadow:0 30px 90px #0008;isolation:isolate;animation:floatIn 1s .12s both}
        .visual:before{content:"";position:absolute;inset:0;background:linear-gradient(180deg,#08090a05 15%,#08090a20 42%,#08090af2 100%),url("https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1400&q=85") center/cover;z-index:-1;transform:scale(1.04);animation:slowZoom 18s ease-in-out infinite alternate}
        .visual:after{content:"";position:absolute;inset:0;border:1px solid #ffffff0d;border-radius:22px;pointer-events:none}
        .visual-top{position:absolute;top:22px;left:24px;right:24px;display:flex;justify-content:space-between;align-items:center;font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#e6e8ec}
        .online{color:var(--accent);font-weight:900}.online:before{content:"";display:inline-block;width:7px;height:7px;background:var(--accent);border-radius:50%;margin:0 8px 1px 0;box-shadow:0 0 14px var(--accent);animation:pulse 1.8s infinite}
        .visual-copy{position:absolute;left:26px;right:26px;bottom:26px}.visual-copy h2{font-size:clamp(25px,3vw,36px);line-height:1.05;letter-spacing:-.04em;margin:0 0 9px;max-width:420px}.visual-copy p{color:#c4c7ce;margin:0;max-width:420px}
        .visual-tag{display:inline-flex;padding:7px 10px;border:1px solid #ffffff40;border-radius:6px;background:#11182780;backdrop-filter:blur(10px);font-size:11px;letter-spacing:.12em;margin-bottom:16px}
        .section-head{display:flex;justify-content:space-between;align-items:end;margin:24px 0}.section-head h2{font-size:25px;margin:0;letter-spacing:-.03em}.section-head p{color:var(--muted);margin:0}
        .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;padding-bottom:82px}
        .card{position:relative;overflow:hidden;background:linear-gradient(145deg,#1b1e24,#15171b);border:1px solid var(--line);border-radius:14px;padding:0;transition:transform .25s,border-color .25s,box-shadow .25s;animation:rise .7s both}
        .card:nth-child(2){animation-delay:.12s}.card:nth-child(3){animation-delay:.24s}.card:hover{transform:translateY(-7px);border-color:#68713e;box-shadow:0 18px 45px #0006}
        .card-art{height:148px;position:relative;background-size:cover;background-position:center;overflow:hidden}.card-art:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,#10111400,#101114e8)}
        .card:nth-child(1) .card-art{background-image:url("https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=80")}
        .card:nth-child(2) .card-art{background-image:url("https://images.unsplash.com/photo-1593305841991-05c297ba4575?auto=format&fit=crop&w=900&q=80")}
        .card:nth-child(3) .card-art{background-image:url("https://images.unsplash.com/photo-1560253023-3ec5d502959f?auto=format&fit=crop&w=900&q=80")}
        .card-body{padding:20px 22px 24px}.card small{color:var(--accent);font-size:11px;letter-spacing:.13em;font-weight:800}.card h3{margin:12px 0 8px;font-size:19px}.card p{color:var(--muted);margin:0}
        .how{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin:18px 0 76px}.step{border-top:1px solid #41444c;padding-top:17px}.step b{color:var(--accent);font-size:12px;letter-spacing:.12em}.step h3{font-size:18px;margin:9px 0 5px}.step p{color:var(--muted);margin:0}
        footer{border-top:1px solid var(--line);padding:24px 0;color:var(--muted);font-size:13px;display:flex;justify-content:space-between;gap:15px}
        .reveal{opacity:0;transform:translateY(22px);transition:opacity .7s ease,transform .7s ease}.reveal.visible{opacity:1;transform:none}
        @keyframes drop{from{opacity:0;transform:translateY(-14px)}to{opacity:1;transform:none}}@keyframes rise{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:none}}@keyframes floatIn{from{opacity:0;transform:translateY(20px) scale(.98)}to{opacity:1;transform:none}}@keyframes slowZoom{from{transform:scale(1.02)}to{transform:scale(1.12)}}@keyframes pulse{0%,100%{opacity:1;box-shadow:0 0 12px var(--accent)}50%{opacity:.45;box-shadow:0 0 2px var(--accent)}}
        @media(max-width:800px){.shell{padding:0 18px}nav{display:none}.hero{grid-template-columns:1fr;padding:60px 0 48px;gap:32px}.visual{min-height:330px}.cards,.how{grid-template-columns:1fr}.section-head{display:block}.section-head p{margin-top:6px}footer{display:block}footer span{display:block;margin-top:6px}}
        @media(prefers-reduced-motion:reduce){*,*:before,*:after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}.reveal{opacity:1;transform:none}}
    </style>
</head>
<body>
<div class="shell">
    <header>
        <a class="brand" href="/">ARENA <span>X</span></a>
        <nav aria-label="Основная навигация"><a href="/tournaments">Турниры</a><a href="#about">О платформе</a><a href="#format">Как играть</a></nav>
        <a class="nav-action" href="/tournaments">Открыть турниры ↗</a>
    </header>
    <main>
        <section class="hero" id="about">
            <div>
                <div class="eyebrow">Твоя игра. Твоя команда. Твой результат.</div>
                <h1>Время выйти<br>на арену.</h1>
                <p class="lead">Платформа локальных киберспортивных турниров. Собирай команду, подавай заявку и доказывай своё мастерство.</p>
                <div class="actions"><a class="button" href="/tournaments">Смотреть турниры&nbsp; ↗</a><a class="button secondary" href="#format">Как это работает</a></div>
            </div>
            <aside class="visual" aria-label="Киберспортивная арена">
                <div class="visual-top"><span>ARENA X / 2026</span><span class="online">ONLINE</span></div>
                <div class="visual-copy"><span class="visual-tag">PLAY · COMPETE · WIN</span><h2>Соревнование начинается здесь.</h2><p>Единое пространство для игроков, команд и организаторов.</p></div>
            </aside>
        </section>
        <section id="tournaments" class="reveal">
            <div class="section-head"><h2>Турнирная платформа</h2><p>Всё необходимое для проведения соревнований</p></div>
            <div class="cards">
                <article class="card"><div class="card-art"></div><div class="card-body"><small>01 / ИГРОКИ</small><h3>Создавай профиль</h3><p>Укажи игровой никнейм, найди команду и участвуй в соревнованиях.</p></div></article>
                <article class="card"><div class="card-art"></div><div class="card-body"><small>02 / КОМАНДЫ</small><h3>Играй вместе</h3><p>Собирай состав, управляй участниками и подавай командные заявки.</p></div></article>
                <article class="card"><div class="card-art"></div><div class="card-body"><small>03 / ТУРНИРЫ</small><h3>Борись за победу</h3><p>Следи за расписанием, матчами, результатами и турнирной таблицей.</p></div></article>
            </div>
        </section>
        <section id="format" class="reveal">
            <div class="section-head"><h2>От регистрации до финала</h2><p>Три шага до первого матча</p></div>
            <div class="how">
                <article class="step"><b>01 — ПРОФИЛЬ</b><h3>Зарегистрируйся</h3><p>Создай аккаунт и укажи игровой никнейм.</p></article>
                <article class="step"><b>02 — КОМАНДА</b><h3>Собери состав</h3><p>Создай команду или присоединись к существующей.</p></article>
                <article class="step"><b>03 — ТУРНИР</b><h3>Подай заявку</h3><p>Выбери соревнование и следи за результатами.</p></article>
            </div>
        </section>
    </main>
    <footer><strong>ARENA X</strong><span>Учебный проект · 2026</span></footer>
</div>
<script>
    (() => {
        const items = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) { items.forEach(item => item.classList.add('visible')); return; }
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
        }), { threshold: 0.12 });
        items.forEach(item => observer.observe(item));
    })();
</script>
</body>
</html>