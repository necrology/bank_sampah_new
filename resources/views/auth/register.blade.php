<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
    <div class="login-form">
        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center d-flex align-items-center" style="height: 100vh">
                <div class="col-lg-6 col-md-6">
                    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-12">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900">
                                                Bank Sampah Mukti Berkah
                                            </h1>
                                            <h1 class="h4 text-gray-900 mb-4">
                                                Buat Akun
                                            </h1>
                                        </div>
                                        <form class="user" method="POST" action="{{ route('register') }}">
                                            @csrf

                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control form-control-user @error('name') is-invalid @enderror"
                                                    placeholder="Masukan Nama" name="name" value="{{ old('name') }}"
                                                    required autocomplete="name" autofocus>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <input type="email"
                                                    class="form-control form-control-user @error('email') is-invalid @enderror"
                                                    placeholder="Masukan Email" name="email" value="{{ old('email') }}"
                                                    required autocomplete="email" autofocus>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <input type="password"
                                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                                    placeholder="Masukan Password" name="password" required
                                                    autocomplete="new-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <input type="password"
                                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                                    placeholder="Konfirmasi Password" name="password_confirmation"
                                                    required autocomplete="new-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                Daftar
                                            </button>
                                        </form>
                                        <hr />
                                        <div class="text-center">
                                            Sudah punya akun?
                                            <a href="{{route('login')}}">Login disini!</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="sbadmin2/js/sb-admin-2.min.js"></script>
</body>

</html>