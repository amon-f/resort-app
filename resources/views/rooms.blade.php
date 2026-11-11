<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f3ed">
    <title>Stay at Master's House | WRC Weekend</title>
    <style>
        :root { color-scheme: light; --ink: #25332b; --muted: #68736a; --paper: #f5f3ed; --line: #dedfd5; --green: #315b46; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1160px, calc(100% - 48px)); margin: auto; }
        .topbar { height: 78px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: 11px; font: 21px Georgia, serif; letter-spacing: -.4px; }
        .brand-mark { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; color: #f9f7f0; background: var(--green); font: 17px system-ui; }
        .nav { display: flex; align-items: center; gap: 30px; color: #58645b; font-size: 13px; }
        .nav-cta, .book { border-radius: 4px; background: var(--green); color: white; font-weight: 700; }
        .nav .nav-cta { padding: 12px 17px; }
        .hero { display: grid; grid-template-columns: 1.08fr .92fr; gap: 46px; align-items: center; padding: 62px 0 54px; }
        .eyebrow { color: var(--green); font-size: 11px; font-weight: 750; letter-spacing: 1.65px; text-transform: uppercase; }
        h1 { max-width: 620px; margin: 17px 0 18px; font: 400 clamp(42px, 5.5vw, 68px)/1.04 Georgia, serif; letter-spacing: -2.3px; }
        .hero-copy { max-width: 490px; margin: 0; color: var(--muted); font-size: 16px; line-height: 1.75; }
        .hero-art { position: relative; min-height: 275px; overflow: hidden; border-radius: 8px; background: linear-gradient(160deg, #d6dfca 0 35%, #a7b898 35% 53%, #6b886e 53% 70%, #486951 70%); }
        .sun { position: absolute; top: 34px; right: 52px; width: 58px; height: 58px; border-radius: 50%; background: #e4bd75; }
        .ridge { position: absolute; left: -5%; right: -5%; bottom: 58px; height: 115px; border-radius: 50% 50% 0 0; background: #859d7e; transform: rotate(-7deg); }
        .ridge.two { left: 42%; bottom: 30px; height: 105px; background: #55775e; transform: rotate(9deg); }
        .foreground { position: absolute; left: -5%; right: -5%; bottom: -45px; height: 120px; border-radius: 50% 50% 0 0; background: #375d47; }
        .art-label { position: absolute; z-index: 2; left: 21px; bottom: 19px; color: white; font-size: 11px; letter-spacing: 1.1px; text-transform: uppercase; }
        .section-heading { display: flex; justify-content: space-between; align-items: end; gap: 20px; padding: 4px 0 19px; }
        h2 { margin: 7px 0 0; font: 400 34px/1.15 Georgia, serif; letter-spacing: -.8px; }
        .dates { padding: 10px 13px; border: 1px solid var(--line); border-radius: 5px; color: #58645b; font-size: 12px; white-space: nowrap; }
        .rooms { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; padding-bottom: 55px; }
        .card { overflow: hidden; border: 1px solid var(--line); border-radius: 7px; background: #fffefa; }
        .card-art { position: relative; display: grid; place-items: center; height: 150px; overflow: hidden; }
        .card-art:before, .card-art:after { position: absolute; content: ""; width: 190px; height: 190px; border: 1px solid #ffffff60; border-radius: 50%; }
        .card-art:before { transform: translate(-52px, 45px); } .card-art:after { transform: translate(102px, 92px); }
        .sage { background: #dce4d4; color: #526e55; } .clay { background: #ead8c8; color: #8b604a; } .gold { background: #e9dfbd; color: #827344; }
        .room-icon { z-index: 1; font: 400 66px Georgia, serif; opacity: .8; }
        .badge { position: absolute; top: 13px; left: 13px; padding: 7px 9px; border-radius: 3px; background: #fffefaed; color: #46554a; font-size: 10px; font-weight: 700; }
        .card-body { padding: 18px; }
        .room-type { color: #798078; font-size: 9px; font-weight: 750; letter-spacing: 1.05px; text-transform: uppercase; }
        .room-title-row { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin-top: 8px; }
        h3 { margin: 0; font: 400 23px/1.15 Georgia, serif; }
        .price { white-space: nowrap; font-size: 14px; font-weight: 750; } .price small { color: var(--muted); font-size: 10px; font-weight: 450; }
        .description { min-height: 63px; margin: 11px 0 16px; color: var(--muted); font-size: 12px; line-height: 1.65; }
        .card-bottom { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding-top: 13px; border-top: 1px solid #e9e8e0; }
        .amenity { color: #69746b; font-size: 10px; } .book { padding: 10px 13px; font-size: 11px; white-space: nowrap; }
        .note { margin: 0 0 55px; padding: 17px 20px; border-left: 3px solid #b99b5d; background: #eeece3; color: #5d675e; font-size: 12px; line-height: 1.65; }
        footer { border-top: 1px solid var(--line); padding: 19px 0 25px; color: #737b73; font-size: 11px; }
        @media (max-width: 760px) { .wrap { width: min(100% - 32px, 560px); } .topbar { height: 68px; } .nav a:not(.nav-cta) { display: none; } .hero { grid-template-columns: 1fr; gap: 24px; padding: 42px 0; } .hero-art { min-height: 210px; } .rooms { grid-template-columns: 1fr; } .card-art { height: 175px; } .section-heading { align-items: start; flex-direction: column; } h2 { font-size: 31px; } }
    </style>
</head>
<body>
    <header class="wrap topbar">
        <a class="brand" href="/" aria-label="Master's House home"><span class="brand-mark">M</span> Master's House</a>
        <nav class="nav" aria-label="Main navigation">
            <a href="#rooms">Stay</a><a href="#about">The resort</a><a href="#contact">Contact</a><a class="nav-cta" href="#rooms">Explore rooms</a>
        </nav>
    </header>
    <main class="wrap">
        <section class="hero" id="about">
            <div><div class="eyebrow">Your WRC weekend, well spent</div><h1>A little room to breathe between the stages.</h1><p class="hero-copy">Settle into the calm of Master's House, a welcoming countryside retreat for rally fans, families, and curious travellers.</p></div>
            <div class="hero-art" role="img" aria-label="Illustrated green hills at sunset"><span class="sun"></span><span class="ridge"></span><span class="ridge two"></span><span class="foreground"></span><span class="art-label">The highlands are calling</span></div>
        </section>
        <section id="rooms" aria-labelledby="rooms-heading">
            <div class="section-heading"><div><div class="eyebrow">Find your place</div><h2 id="rooms-heading">Stays made for slowing down</h2></div><div class="dates">WRC WEEKEND · 2026</div></div>
            <div class="rooms">
                @foreach ($rooms as $room)
                    <article class="card">
                        <div class="card-art {{ $room['tone'] }}" aria-hidden="true"><span class="badge">{{ $room['label'] }}</span><span class="room-icon">{{ $room['icon'] }}</span></div>
                        <div class="card-body"><div class="room-type">{{ $room['type'] }}</div>
                            <div class="room-title-row"><h3>{{ $room['name'] }}</h3><div class="price">KSh {{ number_format($room['price']) }} <small>/ night</small></div></div>
                            <p class="description">{{ $room['description'] }}</p>
                            <div class="card-bottom"><span class="amenity">Breakfast included · Wi-Fi</span><a class="book" href="#contact" aria-label="Ask about {{ $room['name'] }}">Ask about this stay</a></div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
                <section id="contact" aria-labelledby="booking-heading" style="margin:0 0 55px;padding:30px;border:1px solid #dedfd5;border-radius:8px;background:#fffefa">
            <div style="max-width:760px;margin:0 auto">
                <div class="eyebrow">Make a request</div>
                <h2 id="booking-heading" style="margin:8px 0 10px">Plan your stay</h2>
                <p style="margin:0 0 22px;color:#68736a;font-size:13px;line-height:1.65">Tell us what you need and the resort team will follow up to confirm availability. This form does not take payment.</p>

                @if (session('booking_success'))
                    <div role="status" style="margin-bottom:18px;padding:13px 15px;border:1px solid #bdd0bf;border-radius:5px;background:#edf4ec;color:#315b46;font-size:13px">{{ session('booking_success') }}</div>
                @endif
                @if ($errors->any())
                    <div role="alert" style="margin-bottom:18px;padding:13px 15px;border:1px solid #e4c2b9;border-radius:5px;background:#fbefec;color:#8b3d32;font-size:13px">Please review the highlighted details and try again.</div>
                @endif

                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:15px">
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Full name
                            <input name="full_name" type="text" autocomplete="name" maxlength="120" value="{{ old('full_name') }}" required style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;font:inherit">
                            @error('full_name') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Email address
                            <input name="email" type="email" autocomplete="email" maxlength="254" value="{{ old('email') }}" required style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;font:inherit">
                            @error('email') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Phone (optional)
                            <input name="phone" type="tel" autocomplete="tel" maxlength="32" value="{{ old('phone') }}" style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;font:inherit">
                            @error('phone') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Stay type
                            <select name="room_name" required style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;background:white;font:inherit">
                                <option value="">Choose a stay</option>
                                @foreach (['Garden Cottage', 'Rally View Suite', 'Family Lodge'] as $roomName)
                                    <option value="{{ $roomName }}" @selected(old('room_name') === $roomName)>{{ $roomName }}</option>
                                @endforeach
                            </select>
                            @error('room_name') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Arrival date
                            <input name="arrival_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('arrival_date') }}" required style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;background:white;font:inherit">
                            @error('arrival_date') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Departure date
                            <input name="departure_date" type="date" value="{{ old('departure_date') }}" required style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;background:white;font:inherit">
                            @error('departure_date') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                        <label style="display:block;color:#46554a;font-size:12px;font-weight:650">Guests
                            <input name="guests" type="number" min="1" max="8" value="{{ old('guests', 2) }}" required style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;font:inherit">
                            @error('guests') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                        </label>
                    </div>
                    <label style="display:block;margin-top:15px;color:#46554a;font-size:12px;font-weight:650">Anything else we should know? (optional)
                        <textarea name="notes" rows="3" maxlength="1000" style="display:block;width:100%;margin-top:7px;padding:11px;border:1px solid #cfd4c9;border-radius:4px;font:inherit">{{ old('notes') }}</textarea>
                        @error('notes') <span style="color:#9b4537;font-size:11px">{{ $message }}</span> @enderror
                    </label>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-top:18px">
                        <span style="color:#68736a;font-size:11px">Request only · No card details are collected</span>
                        <button type="submit" class="book" style="border:0;cursor:pointer">Send booking request</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <footer><div class="wrap">Master's House · A countryside stay for the WRC weekend · Sample prototype</div></footer>
</body>
</html>
