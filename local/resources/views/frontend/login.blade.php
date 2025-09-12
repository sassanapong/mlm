<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDK Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css') }}">
    <style>
        body {
            background: url('frontend/images/bg.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-2xl shadow-lg p-8 text-white">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('frontend/images/logo_new.png') }}" alt="MDK Logo" class="w-24 mb-3">
            <h1 class="text-2xl font-bold">Welcome!</h1>
            <p class="text-sm">ยินดีต้อนรับ</p>
        </div>


        <form action="login" class="space-y-4" method="POST">
            @csrf
            <div>
                <label class="block text-sm mb-1">ชื่อผู้ใช้ / Username</label>
                <input type="text" placeholder="Username" value="{{ old('username') }}" name="username" autocomplete="off"
                    class="w-full px-3 py-2 rounded-lg bg-white/20 text-black focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label class="block text-sm mb-1">รหัสผ่าน / Password</label>
                <input type="password" autocomplete="off" placeholder="Password" name="password" 
                    class="w-full px-3 py-2 rounded-lg bg-white/20 text-black focus:outline-none focus:ring-2 focus:ring-yellow-400">
                {{-- <div class="flex justify-end mt-1">
          <a href="#" class="text-xs text-yellow-300 hover:underline">ลืมรหัสผ่าน / Forgot Password</a>
        </div> --}}
            </div>

            <button type="submit"
                class="w-full py-2 bg-yellow-400 text-purple-900 font-bold rounded-lg hover:bg-yellow-300 transition">
                เข้าสู่ระบบ / Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm leading-relaxed">
            <p>เพียงแค่โหลดแอป เปลี่ยนที่จ่าย ย้ายที่ซื้อ</p>
            <p>รายได้หลั่งไหลดังสายน้ำ</p>
            <p>สินค้าคุ้มค่าราคาโรงงาน</p>
            <p>รับประกันสินค้า คืนเงินเต็มจำนวน</p>
        </div>
    </div>
</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.all.js"></script>
        <!--Animation-->
    
    @include('layouts.frontend.flash-message')
</html>
