<!DOCTYPE html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Вход — ARENA X</title>
<style>
:root{color-scheme:dark;--bg:#101114;--panel:#191b20;--line:#2a2d34;--text:#f4f5f7;--muted:#969ba6;--accent:#d8ff45}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:15px/1.5 Inter,system-ui,sans-serif;min-height:100vh;display:grid;place-items:center;padding:22px}a{color:inherit;text-decoration:none}.panel{width:min(100%,440px);background:var(--panel);border:1px solid var(--line);border-radius:16px;padding:34px}.brand{font-weight:900;letter-spacing:.12em;font-size:20px}.brand span{color:var(--accent)}h1{font-size:30px;letter-spacing:-.04em;margin:34px 0 6px}.muted{color:var(--muted)}label{display:block;font-size:13px;margin:20px 0 7px}input{width:100%;background:#101114;border:1px solid #373b44;border-radius:8px;padding:13px;color:var(--text);font:inherit}input:focus{outline:1px solid var(--accent);border-color:var(--accent)}button{width:100%;border:0;border-radius:8px;padding:14px;background:var(--accent);color:#15170d;font-weight:850;font:inherit;margin-top:24px;cursor:pointer}.error{color:#ff8d8d;font-size:13px;margin-top:6px}.bottom{margin-top:22px;color:var(--muted);font-size:14px}.bottom a{color:var(--accent)}
</style></head><body><main class="panel"><a class="brand" href="/">ARENA <span>X</span></a><h1>С возвращением</h1><p class="muted">Войди, чтобы продолжить участие в турнирах.</p>
@if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('login') }}">@csrf
<label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
<label for="password">Пароль</label><input id="password" name="password" type="password" autocomplete="current-password" required>
<label style="display:flex;align-items:center;gap:9px"><input style="width:auto" type="checkbox" name="remember" value="1"> Запомнить меня</label>
<button type="submit">Войти в аккаунт</button></form>
<div class="bottom">Нет аккаунта? <a href="{{ route('register') }}">Создать профиль</a></div></main></body></html>
