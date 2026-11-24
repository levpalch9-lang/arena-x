<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0d0f">
    <meta name="description" content="ARENA X — турнирная платформа для игроков, команд и организаторов. Турниры, матчи, сетки и результаты.">
    <title>ARENA X — Турнирная платформа</title>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{color-scheme:dark;--bg:#0b0d0f;--surface:#121518;--surface-2:#181c20;--line:#292f34;--text:#f1f3f3;--muted:#929ba1;--accent:#c9f15b;--max:1240px}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--text);font:14px/1.55 Manrope,Arial,sans-serif;overflow-x:hidden}a{color:inherit;text-decoration:none}button{font:inherit}
        .wrap{width:min(var(--max),calc(100% - 64px));margin:auto}.topbar{height:78px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:28px}
        .brand{display:inline-flex;align-items:center;gap:11px;font-size:19px;font-weight:800;letter-spacing:.16em;white-space:nowrap}.brand-mark{width:29px;height:29px;display:grid;place-items:center;border:1px solid var(--accent);color:var(--accent);font-size:12px;font-weight:800;transform:rotate(45deg);border-radius:5px}.brand-mark span{transform:rotate(-45deg)}.brand em{font-style:normal;color:var(--accent)}
        .nav{display:flex;align-items:center;gap:29px;color:#b4bdc2;font-size:13px}.nav a{transition:color .2s}.nav a:hover{color:var(--accent)}.nav-actions{display:flex;align-items:center;gap:10px}
        .btn{display:inline-flex;align-items:center;justify-content:center;min-height:43px;padding:0 17px;border:1px solid transparent;border-radius:5px;background:var(--accent);color:#12160a;font-size:12px;font-weight:800;letter-spacing:.02em;transition:transform .2s,background .2s,border-color .2s}.btn:hover{transform:translateY(-2px);background:#d9ff80}.btn-ghost{background:transparent;border-color:#3b4449;color:var(--text)}.btn-ghost:hover{background:#ffffff08;border-color:#69747a}
        .hero{min-height:555px;display:grid;grid-template-columns:1.04fr .96fr;gap:52px;align-items:center;padding:66px 0 75px}.kicker{display:flex;align-items:center;gap:10px;color:var(--accent);font-size:10px;font-weight:800;letter-spacing:.2em;text-transform:uppercase}.kicker:before{content:"";width:24px;height:1px;background:var(--accent)}
        h1{font-size:clamp(43px,5.2vw,68px);line-height:1.04;letter-spacing:-.065em;margin:20px 0 20px;max-width:650px;font-weight:800}.hero-copy>p{max-width:520px;color:#a5afb4;font-size:15px;margin:0}.hero-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:30px}
        .hero-meta{display:flex;gap:25px;flex-wrap:wrap;margin-top:46px;padding-top:22px;border-top:1px solid var(--line)}.metric strong{display:block;font-size:22px;letter-spacing:-.04em}.metric span{display:block;color:var(--muted);font-size:10px;letter-spacing:.12em;text-transform:uppercase;margin-top:2px}
        .hero-art{height:415px;position:relative;overflow:hidden;border:1px solid #333d42;background:#171c20;border-radius:7px}.hero-art:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,#080b0d35,#080b0d05 55%,#080b0d50),linear-gradient(0deg,#080b0df0 0%,transparent 64%),url("https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1500&q=88") center/cover;transform:scale(1.02);animation:artDrift 18s ease-in-out infinite alternate}
        .art-index{position:absolute;top:22px;left:23px;right:23px;display:flex;justify-content:space-between;color:#e2e7e8;font-size:10px;font-weight:700;letter-spacing:.16em}.live-dot{color:var(--accent)}.live-dot:before{content:"";display:inline-block;width:6px;height:6px;background:var(--accent);border-radius:50%;margin:0 8px 1px 0}
        .art-caption{position:absolute;left:25px;right:25px;bottom:24px}.art-caption small{color:var(--accent);font-size:10px;letter-spacing:.16em;font-weight:800}.art-caption h2{font-size:29px;line-height:1.12;letter-spacing:-.04em;margin:8px 0;max-width:400px}.art-caption p{margin:0;color:#c0c9cc;font-size:12px}
        .section{padding:53px 0 66px;border-top:1px solid var(--line)}.section-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:25px}.section-head h2{font-size:26px;letter-spacing:-.05em;margin:7px 0 0}.section-head p{color:var(--muted);font-size:12px;margin:0}.section-link{color:var(--accent);font-size:12px;font-weight:800;white-space:nowrap}
        .game-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.game{position:relative;min-height:110px;padding:18px 20px;border:1px solid var(--line);background:var(--surface);display:flex;flex-direction:column;justify-content:space-between;transition:border-color .2s,transform .2s}.game:hover{border-color:#74864a;transform:translateY(-3px)}.game small{color:var(--muted);font-size:9px;letter-spacing:.15em}.game strong{font-size:17px;letter-spacing:-.03em}.game span{position:absolute;right:19px;top:17px;color:#647078;font-size:20px}
        .tournament-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}.t-card{background:var(--surface);border:1px solid var(--line);min-width:0;transition:transform .22s,border-color .22s,box-shadow .22s}.t-card:hover{transform:translateY(-5px);border-color:#667746;box-shadow:0 18px 40px #0005}.t-cover{height:145px;position:relative;background:linear-gradient(0deg,#111619 0%,transparent 80%),url("https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=82") center/cover}.t-card:nth-child(2) .t-cover{background-image:linear-gradient(0deg,#111619 0%,transparent 80%),url("https://images.unsplash.com/photo-1593305841991-05c297ba4575?auto=format&fit=crop&w=900&q=82")}.t-card:nth-child(3) .t-cover{background-image:linear-gradient(0deg,#111619 0%,transparent 80%),url("https://images.unsplash.com/photo-1560253023-3ec5d502959f?auto=format&fit=crop&w=900&q=82")}.t-cover-label{position:absolute;left:16px;bottom:13px;color:var(--accent);font-size:9px;letter-spacing:.16em;font-weight:800}.t-body{padding:17px 18px 18px}.t-top{display:flex;justify-content:space-between;gap:12px;color:var(--muted);font-size:10px}.t-status{color:var(--accent);font-weight:800;text-transform:uppercase;letter-spacing:.08em}.t-body h3{font-size:18px;letter-spacing:-.035em;margin:12px 0 6px}.t-body p{font-size:12px;line-height:1.6;color:var(--muted);min-height:38px;margin:0}.t-meta{display:flex;justify-content:space-between;gap:10px;margin-top:16px;padding-top:13px;border-top:1px solid var(--line);color:#c3cacc;font-size:10px}
        .empty{grid-column:1/-1;padding:30px;border:1px dashed #394247;color:var(--muted);text-align:center}.match-list{border-top:1px solid var(--line)}.match{display:grid;grid-template-columns:105px 1fr auto;gap:20px;align-items:center;padding:15px 5px;border-bottom:1px solid var(--line)}.match-date{color:var(--muted);font-size:11px}.match-name{font-size:13px;font-weight:700}.match-game{display:block;color:var(--muted);font-size:10px;margin-top:3px}.match-state{color:var(--accent);font-size:10px;font-weight:800;letter-spacing:.08em}
        .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:25px}.step{border-top:1px solid #465057;padding-top:15px}.step b{font-size:10px;letter-spacing:.16em;color:var(--accent)}.step h3{font-size:17px;margin:8px 0 4px}.step p{color:var(--muted);font-size:12px;margin:0;max-width:300px}
        footer{border-top:1px solid var(--line);padding:25px 0 32px;color:var(--muted);font-size:11px;display:flex;justify-content:space-between;gap:20px}
        .reveal{opacity:0;transform:translateY(18px);transition:opacity .65s ease,transform .65s ease}.reveal.is-visible{opacity:1;transform:none}
        @keyframes artDrift{from{transform:scale(1.02)}to{transform:scale(1.1)}}@media(max-width:900px){.wrap{width:min(var(--max),calc(100% - 40px))}.hero{grid-template-columns:1fr;gap:28px;padding:55px 0}.hero-art{height:330px}.nav{gap:16px}.tournament-grid{grid-template-columns:repeat(2,1fr)}.t-card:last-child:nth-child(odd){display:none}}
        @media(max-width:650px){.wrap{width:calc(100% - 34px)}.topbar{height:68px}.nav{display:none}.brand{font-size:16px}.brand-mark{width:25px;height:25px}.nav-actions .btn{min-height:37px;padding:0 10px;font-size:10px}.hero{padding:42px 0 48px;min-height:auto}h1{font-size:43px}.hero-meta{gap:18px;margin-top:32px}.metric strong{font-size:19px}.hero-art{height:290px}.art-caption h2{font-size:24px}.section{padding:40px 0 48px}.section-head{align-items:start;flex-direction:column;gap:8px}.section-head h2{font-size:23px}.game-row,.tournament-grid,.steps{grid-template-columns:1fr}.t-card:last-child:nth-child(odd){display:block}.t-cover{height:160px}.match{grid-template-columns:78px 1fr;gap:12px}.match-state{grid-column:2}.match-name{font-size:12px}footer{display:block}footer span{display:block;margin-top:6px}}
        @media(prefers-reduced-motion:reduce){*,*:before,*:after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}.reveal{opacity:1;transform:none}}
    </style>
</head>
<body>
<div class="wrap">
    <header class="topbar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark"><span>AX</span></span>ARENA <em>X</em></a>
        <nav class="nav" aria-label="Главная навигация"><a href="{{ route('tournaments.index') }}">Турниры</a><a href="#games">Дисциплины</a><a href="#matches">Матчи</a><a href="#about">О платформе</a></nav>
        <div class="nav-actions">
            @auth
                @if(auth()->user()->isAdmin())<a class="btn btn-ghost" href="{{ route('admin.index') }}">Администрирование</a>@else<a class="btn btn-ghost" href="{{ route('dashboard') }}">Мой кабинет</a>@endif
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-ghost" type="submit">Выйти</button></form>
            @else
                <a class="btn btn-ghost" href="{{ route('login') }}">Войти</a><a class="btn" href="{{ route('register') }}">Создать аккаунт</a>
            @endauth
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-copy">
                <div class="kicker">Локальная киберспортивная платформа</div>
                <h1>Турниры.<br>Команды. Победы.</h1>
                <p>Единое пространство для организации соревнований: от первой заявки до финального счёта. Собирай состав, следи за сеткой и выходи на матч.</p>
                <div class="hero-actions"><a class="btn" href="{{ route('tournaments.index') }}">Открыть каталог&nbsp; ↗</a>@guest<a class="btn btn-ghost" href="{{ route('register') }}">Присоединиться</a>@else<a class="btn btn-ghost" href="{{ route('dashboard') }}">Перейти в кабинет</a>@endguest</div>
                <div class="hero-meta">
                    <div class="metric"><strong>{{ $stats['tournaments'] }}</strong><span>Турниров в системе</span></div>
                    <div class="metric"><strong>{{ $stats['teams'] }}</strong><span>Команд</span></div>
                    <div class="metric"><strong>{{ $stats['matches'] }}</strong><span>Матчей создано</span></div>
                </div>
            </div>
            <aside class="hero-art">
                <div class="art-index"><span>ARENA X / TOURNAMENT SYSTEM</span><span class="live-dot">СЕЗОН 2026</span></div>
                <div class="art-caption"><small>PLAY WITH PURPOSE</small><h2>Каждый матч — шаг к финалу.</h2><p>Турнирная система для игроков, капитанов и организаторов.</p></div>
            </aside>
        </section>

        <section class="section reveal" id="games">
            <div class="section-head"><div><div class="kicker">01 / ДИСЦИПЛИНЫ</div><h2>Выбери свою игру</h2></div><p>Игровые направления платформы</p></div>
            <div class="game-row">
                @forelse($games as $game)
                    <a class="game" href="{{ route('tournaments.index', ['game' => $game->slug]) }}"><small>DISCIPLINE / {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</small><strong>{{ $game->name }}</strong><span>↗</span></a>
                @empty
                    <div class="empty">Игровые дисциплины скоро появятся.</div>
                @endforelse
            </div>
        </section>

        <section class="section reveal" id="tournaments">
            <div class="section-head"><div><div class="kicker">02 / COMPETITIONS</div><h2>Ближайшие турниры</h2></div><a class="section-link" href="{{ route('tournaments.index') }}">Все соревнования&nbsp; ↗</a></div>
            <div class="tournament-grid">
                @forelse($featuredTournaments as $tournament)
                    <a class="t-card" href="{{ route('tournaments.show', $tournament) }}">
                        <div class="t-cover"><span class="t-cover-label">{{ $tournament->game->short_name ?? $tournament->game->name }}</span></div>
                        <div class="t-body">
                            <div class="t-top"><span>{{ $tournament->format === 'single_elimination' ? 'SINGLE ELIMINATION' : strtoupper(str_replace('_', ' ', $tournament->format)) }}</span><span class="t-status">{{ $tournament->status === 'ongoing' ? 'LIVE' : 'OPEN' }}</span></div>
                            <h3>{{ $tournament->title }}</h3><p>{{ \Illuminate\Support\Str::limit($tournament->description ?: 'Открой страницу соревнования, чтобы узнать детали.', 100) }}</p>
                            <div class="t-meta"><span>{{ $tournament->starts_at?->format('d.m.Y') ?? 'Дата уточняется' }}</span><span>{{ $tournament->participants_count }}/{{ $tournament->max_participants }} слотов</span></div>
                        </div>
                    </a>
                @empty
                    <div class="empty">Опубликованные турниры появятся здесь после создания организатором.</div>
                @endforelse
            </div>
        </section>

        <section class="section reveal" id="matches">
            <div class="section-head"><div><div class="kicker">03 / MATCH CENTER</div><h2>Расписание матчей</h2></div><p>Ближайшие встречи и текущие игры</p></div>
            <div class="match-list">
                @forelse($nextMatches as $match)
                    <a class="match" href="{{ route('tournaments.show', $match->tournament) }}">
                        <span class="match-date">{{ $match->scheduled_at?->format('d.m · H:i') ?? 'Время TBA' }}</span>
                        <span><span class="match-name">{{ $match->teamA->name ?? 'TBD' }} <span style="color:var(--muted)">vs</span> {{ $match->teamB->name ?? 'TBD' }}</span><span class="match-game">{{ $match->tournament->game->short_name ?? 'ESPORTS' }} · {{ $match->tournament->title }}</span></span>
                        <span class="match-state">{{ $match->status === 'live' ? 'LIVE NOW' : 'UPCOMING' }}</span>
                    </a>
                @empty
                    <div class="empty">Матчи появятся после публикации расписания организаторами.</div>
                @endforelse
            </div>
        </section>

        <section class="section reveal" id="about">
            <div class="section-head"><div><div class="kicker">04 / HOW IT WORKS</div><h2>От заявки до финала</h2></div><p>Весь турнирный путь в одном месте</p></div>
            <div class="steps">
                <article class="step"><b>01 — ACCOUNT</b><h3>Создай профиль</h3><p>Зарегистрируйся, укажи игровой никнейм и настрой свой профиль.</p></article>
                <article class="step"><b>02 — TEAM</b><h3>Собери команду</h3><p>Создай состав, добавь участников и подготовь командную заявку.</p></article>
                <article class="step"><b>03 — COMPETE</b><h3>Участвуй и побеждай</h3><p>Следи за расписанием, результатами и положением в турнирной таблице.</p></article>
            </div>
        </section>
    </main>
    <footer><strong>ARENA X</strong><span>Платформа локальных киберспортивных турниров · 2026</span><span>PLAY · COMPETE · WIN</span></footer>
</div>
<script>
(() => {
    const blocks = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) { blocks.forEach(block => block.classList.add('is-visible')); return; }
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
        if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
    }), { threshold: .12 });
    blocks.forEach(block => observer.observe(block));
})();
</script>
</body>
</html>
