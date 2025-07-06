<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('payment Failed') }}</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 0 15px;
        }

        .card {
            max-width: 600px;
            width: 100%;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-with-bg {
            background-size: cover;
            background-position: center;
            color: white;
            padding: 35px;
        }

        .card-body {
            height: 350px;
            padding: 20px;
        }

        .heading {
            color: #dc3545;
            margin-bottom: 20px;
        }

        .text {
            color: #dc3545;
            margin-bottom: 30px;
        }

        a.btn-success {
            display: inline-flex;
            align-items: center;
            background-color: #96cb37;
            color: white;
            text-decoration: none;
            padding: 8px 20px;
            padding-left: 0px;
            border-radius: 5px;
            transition: background-color 0.3s ease, padding 0.3s ease;
            width: fit-content;
            position: relative;
        }

        a.btn-success svg {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        a.btn-success:hover svg {
            transition: all 0.3s ease;
            margin-right: 8px;
            opacity: 1;
        }

        .btn-success:hover {
            padding-left: 8px;
            background-color: #aad952;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card card-with-bg" style="background-image: url('{{ asset('images/payment.png') }}');">
            <div class="card-body">
                <h1 class="heading">{{ __('Payment') }}</h1>
                <p class="text">{{ __('Cancelled') }}</p>
                <a href="{{ $redirect }}" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                        fill="currentColor" class="icon">
                        <path
                            d="M14.71 16.29a1 1 0 0 1-1.42 0l-5-5a1 1 0 0 1 0-1.42l5-5a1 1 0 0 1 1.42 1.42L9.83 10H20a1 1 0 0 1 0 2H9.83l4.88 4.88a1 1 0 0 1 0 1.42z" />
                    </svg>
                    {{ __('Back') }}</a>
            </div>
        </div>
    </div>
</body>

</html>
