<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff sign in | Master's House</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f5f3ed; color: #26382e; font: 16px/1.5 Arial, sans-serif; }
        main { width: min(100%, 440px); padding: 40px; background: #fffefa; border: 1px solid #e3e0d7; border-radius: 18px; box-shadow: 0 18px 50px #26382e12; }
        .brand { color: #37634d; font-size: 14px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        h1 { margin: 18px 0 8px; font: 38px/1.1 Georgia, serif; }
        p { color: #6e756e; }
        label { display: block; margin: 18px 0 6px; font-weight: 700; }
        input { width: 100%; padding: 12px 14px; border: 1px solid #d8d8cf; border-radius: 8px; font: inherit; }
        button { width: 100%; margin-top: 24px; padding: 13px; border: 0; border-radius: 8px; background: #37634d; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .error { color: #a33a31; font-size: 14px; }
        a { color: #37634d; }
    </style>
</head>
<body>
<main>
    <div class="brand">Master's House · Staff</div>
    <h1>Welcome back</h1>
    <p>Sign in to review guest booking requests.</p>
    @if ($errors->any())
        <p class="error" role="alert">{{ $errors->first() }}</p>
    @endif
    <form method="POST" action="{{ route('staff.login.store') }}">
        @csrf
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button type="submit">Sign in</button>
    </form>
    <p><a href="{{ route('home') }}">Back to the resort</a></p>
</main>
</body>
</html>
