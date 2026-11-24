<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Турниры — ARENA X</title>
    <link rel="preconnect" href="https://images.unsplash.com">
    <style>
        :root{color-scheme:dark;--bg:#101114;--panel:#191b20;--line:#2a2d34;--text:#f4f5f7;--muted:#969ba6;--accent:#d8ff45}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:15px/1.5 Inter,system-ui,-apple-system,sans-serif}
        a{color:inherit;text-decoration:none}.shell{max-width:1180px;margin:auto;padding:0 28px}html{scroll-behavior:smooth}body:before{content:"";position:fixed;inset:0;pointer-events:none;z-index:-1;background:radial-gradient(ellipse at 80% 12%,#d8ff4510,transparent 35%)}
        header{height:78px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}
        .brand{font-size:22px;font-weight:850;letter-spacing:.12em}.brand span{color:var(--accent)}.back{color:#c1c4cb}
        .intro{padding:64px 0 36px}.eyebrow{color:var(--accent);font-size:12px;letter-spacing:.18em;font-weight:800;text-transform:uppercase}
        h1{font-size:clamp(38px,5vw,58px);line-height:1.05;letter-spacing:-.05em;margin:12px 0}.intro p{color:var(--muted);max-width:620px}
        .notice{margin:20px 0;padding:12px 15px;border:1px solid #55533b;background:#242319;border-radius:8px;color:#e8dfaa;font-size:13px}
        .toolbar{display:flex;gap:10px;align-items:center;justify-content:space-between;margin:28px 0 18px;flex-wrap:wrap}
        .count{color:var(--muted);font-size:13px}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;padding-bottom:80px}
        .card{position:relative;background:linear-gradient(145deg,#1d2026,#15171b);border:1px solid var(--line);border-radius:14px;padding:0 22px 22px;display:flex;flex-direction:column;min-height:310px;overflow:hidden;transition:transform .25s,border-color .25s,box-shadow .25s;animation:rise .65s both}.card:nth-child(2){animation-delay:.12s}.card:nth-child(3){animation-delay:.24s}.card:hover{transform:translateY(-6px);border-color:#68713e;box-shadow:0 18px 45px #0006}.card-art{height:142px;margin:0 -22px 18px;background-size:cover;background-position:center;position:relative}.card-art:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,#10111400,#191b20)}.card:nth-child(1) .card-art{background-image:url("https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80")}.card:nth-child(2) .card-art{background-image:url("https://images.unsplash.com/photo-1593305841991-05c297ba4575?auto=format&fit=crop&w=900&q=80")}.card:nth-child(3) .card-art{background-image:url("https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=80")}
        .top{display:flex;justify-content:space-between;gap:12px;color:var(--muted);font-size:12px}.state{color:var(--accent);font-weight:800;text-transform:uppercase;letter-spacing:.08em}
        h2{font-size:25px;letter-spacing:-.03em;margin:26px 0 8px}.desc{color:var(--muted);margin:0 0 22px;max-width:600px}
        .meta{display:flex;gap:20px;flex-wrap:wrap;color:#c5c8cf;font-size:13px;margin-top:auto;padding-top:16px;border-top:1px solid var(--line)}
        .empty{grid-column:1/-1;padding:54px 30px;border:1px dashed #42454e;border-radius:12px;text-align:center;color:var(--muted);background:linear-gradient(145deg,#1b1e24,#14161a)}@keyframes rise{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}@media(prefers-reduced-motion:reduce){*,*:before,*:after{animation-duration:.01ms!important;transition-duration:.01ms!important;scroll-behavior:auto!important}}
        @media(max-width:700px){.shell{padding:0 18px}.grid{grid-template-columns:1fr}.intro{padding-top:44px}}
    </style>
</head>
<body>
<div class="shell">
    <header><a class="brand" href="/">ARENA <span>X</span></a><a class="back" href="/">← На главную</a></header>
    <main>
        <section class="intro">
            <div class="eyebrow">ARENA X / COMPETITIONS</div>
            <h1>Турниры</h1>
            <p>Выбирай соревнование, изучай формат и готовь команду к следующему матчу.</p>
        </section>
        @if($demoMode)
            <div class="notice">Демонстрационный режим: показаны примеры турниров. Подключи PostgreSQL в локальном .env, чтобы загружать записи из базы Supabase.</div>
        @endif
        <div class="toolbar"><strong>Доступные соревнования</strong><span class="count">{{ $tournaments->count() }} турнира</span></div>
        <section class="grid">
            @forelse($tournaments as $tournament)
                <article class="card">
                    <div class="card-art" aria-hidden="true"></div>
                    <div class="top"><span>{{ $tournament->game->short_name ?? $tournament->game->name ?? 'ESPORTS' }}</span><span class="state">{{ $tournament->status === 'published' ? 'Регистрация открыта' : ($tournament->status === 'ongoing' ? 'Идёт турнир' : 'Завершён / закрыт') }}</span></div>
                    <h2><a href="{{ route('tournaments.show', $tournament) }}">{{ $tournament->title }}</a></h2>
                    <p class="desc">{{ $tournament->description ?: 'Подробности турнира скоро появятся.' }}</p>
                    <div class="meta"><span>{{ $tournament->participant_type === 'team' ? 'Командный формат' : 'Индивидуальный формат' }}</span><span>До {{ $tournament->max_participants }} участников</span><span>{{ $tournament->starts_at ? $tournament->starts_at->format('d.m.Y') : 'Дата уточняется' }}</span></div>
                </article>
            @empty
                <div class="empty">Сейчас нет опубликованных турниров. Загляни позже.</div>
            @endforelse
        </section>
    </main>
</div>
</body>
</html>
