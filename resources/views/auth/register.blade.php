<!DOCTYPE html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Регистрация — ARENA X</title>
<style>
:root{color-scheme:dark;--bg:#101114;--panel:#191b20;--line:#2a2d34;--text:#f4f5f7;--muted:#969ba6;--accent:#d8ff45}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:15px/1.5 Inter,system-ui,sans-serif;min-height:100vh;display:grid;place-items:center;padding:22px}a{color:inherit;text-decoration:none}.panel{width:min(100%,460px);background:var(--panel);border:1px solid var(--line);border-radius:16px;padding:34px}.brand{font-weight:900;letter-spacing:.12em;font-size:20px}.brand span{color:var(--accent)}h1{font-size:30px;letter-spacing:-.04em;margin:30px 0 6px}.muted{color:var(--muted)}label{display:block;font-size:13px;margin:15px 0 6px}input{width:100%;background:#101114;border:1px solid #373b44;border-radius:8px;padding:12px;color:var(--text);font:inherit}input:focus{outline:1px solid var(--accent);border-color:var(--accent)}button{width:100%;border:0;border-radius:8px;padding:14px;background:var(--accent);color:#15170d;font-weight:850;font:inherit;margin-top:22px;cursor:pointer}.error{color:#ff8d8d;font-size:13px;margin-top:5px}.bottom{margin-top:20px;color:var(--muted);font-size:14px}.bottom a{color:var(--accent)}
/* ARENA-X-MOTION-POLISH */

body{background-image:linear-gradient(120deg,#101114f2,#101114d9),url("https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1800&q=80");background-size:cover;background-position:center}.panel{box-shadow:0 28px 90px #0009;backdrop-filter:blur(16px);background:#191b20ed;animation:formIn .55s both}.brand{display:inline-block;transition:transform .2s}.brand:hover{transform:translateY(-2px)}button{transition:transform .2s,box-shadow .2s}button:hover{transform:translateY(-2px);box-shadow:0 10px 28px #d8ff4533}.bottom a:hover{text-decoration:underline}@keyframes formIn{from{opacity:0;transform:translateY(18px) scale(.99)}to{opacity:1;transform:none}}@media(prefers-reduced-motion:reduce){*,*:before,*:after{animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style></head><body><main class="panel"><a class="brand" href="/">ARENA <span>X</span></a><h1>Создать профиль</h1><p class="muted">Регистрация участника платформы.</p>
@if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('register.store') }}">@csrf
<label for="name">Имя</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required>
<label for="nickname">Игровой никнейм</label><input id="nickname" name="nickname" value="{{ old('nickname') }}" autocomplete="nickname" required>
<label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
<label for="password">Пароль (не менее 8 символов)</label><input id="password" name="password" type="password" autocomplete="new-password" required>
<label for="password_confirmation">Повтори пароль</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
<button type="submit">Зарегистрироваться</button></form>
<div class="bottom">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></div></main></body></html>
