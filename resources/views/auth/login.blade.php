<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cloud CCTV</title>
    
    <style>
        /* Reset bawaan */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        
        body, html {
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* Container Pembungkus */
        .wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Latar Belakang */
        .bg-image {
            position: absolute;
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Posisi Logo */
        .logo-cctv {
            position: absolute;
            top: -14px;
            left: -32px;
            width: 220px;
        }

        .logo-pens {
            position: absolute;
            top: 10px;
            right: 20px;
            width: 86px;
        }

        /* Kotak Login Persis Figma */
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 40px;
            width: 332px;
            height: 283px;
            box-shadow: 0px 4px 35px rgba(0, 0, 0, 0.08);
            position: relative;
            padding: 20px 45px;
        }

        .login-title {
            font-size: 40px;
            font-weight: 400;
            color: #000000;
            text-align: center;
            margin-bottom: 25px;
            margin-top: -5px;
        }

        /* Grup Input Teks */
        .input-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .enter-label {
            font-size: 16px;
            color: #000000;
            margin-bottom: 2px;
            display: block;
        }

        .input-field {
            width: 100%;
            border: none;
            background: transparent;
            font-size: 14px;
            color: #000000;
            outline: none;
            padding-bottom: 3px;
        }

        .input-field::placeholder {
            color: #808080;
        }

        .line {
            border-bottom: 1px solid #000000;
            width: 100%;
        }

        /* Tombol Persis Figma dengan Efek Merah */
        .btn-submit {
            width: 206px;
            height: 27px;
            background: #808080;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0px 4px 19px rgba(119, 147, 65, 0.3);
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease;
        }

        .btn-submit:hover, 
        .btn-submit:active {
            background-color: red;
        }

        /* Pesan Error Validasi Laravel */
        .error-message {
            color: red;
            font-size: 11px;
            position: absolute;
            bottom: -15px;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Gambar Latar & Logo -->
        <img class="bg-image" src="{{ asset('asset/images/image-30.png') }}" alt="Background" />
        <img class="logo-cctv" src="{{ asset('asset/images/logo-new-cctv-removebg-preview-10.png') }}" alt="Logo CCTV Cloud" />
        <img class="logo-pens" src="{{ asset('asset/images/logo-pens-removebg-preview-10.png') }}" alt="Logo PENS" />

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="login-card">
            @csrf
            
            <div class="login-title">Login</div>
            
            <div class="input-group">
                <label class="enter-label">Enter Username</label>
                <input type="text" name="username" class="input-field" placeholder="Masukkan username anda" value="{{ old('username') }}" required autofocus>
                <div class="line"></div>
                @error('username') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            
            <div class="input-group">
                <label class="enter-label">Enter Password</label>
                <input type="password" name="password" class="input-field" placeholder="Masukkan password anda" required>
                <div class="line"></div>
                @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn-submit">Login</button>
        </form>
    </div>

</body>
</html>