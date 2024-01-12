<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />

    {{-- /* Poppin font */ --}}
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

        body {
            font-family: "Poppins", sans-serif;
        }
    </style>
    <title>Hari Libur</title>
</head>

<body>
    {{-- @dd($data) --}}
    <section class="d-flex justify-content-center align-items-center flex-column py-5" style="max-height: 100vh">
        <img src="{{ asset('img/forbidden-image.webp') }}" alt="Forbidden Image" width="100%" style="max-width: 600px"
            class="">
        <h1 class="fs-4 mb-3 fw-bold" style="color: #F68248">Mohon Maaf</h1>
        <h2 class="fs-6 text-center" style="line-height: 1.8">Aplikasi hanya dapat diakses pada hari kerja (Senin -
            Jumat). <br>
            Istirahatlah,
            nikmati
            waktu Anda bersama
            keluarga. <br> Salam sehat selalu!</h2>
        <p class="mt-5"><a
                href="https://www.freepik.com/free-vector/tiny-people-standing-near-prohibited-gesture-isolated-flat-illustration_11235950.htm#query=forbidden%20page&position=21&from_view=search&track=ais">Image
                by pch.vector</a> on Freepik</p>
    </section>
</body>

</html>
