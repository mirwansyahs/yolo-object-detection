<!DOCTYPE html>
<meta charset="utf-8" />
<title><?= (@@$title) ? @@$title : '' ?> | WNSS - Admin & Dashboard Template</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

<!-- Bootstrap Css -->
<link href="{{ asset('css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('css/icons.min.css') }} " rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />


</head>

    <body>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-success-subtle">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-success p-4">
                                            <h5 class="text-success">Welcome Back !</h5>
                                            <p>Sign in to continue to WNSS.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ asset('images/profile-img.png') }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div class="auth-logo">
                                    <a href="{{ url('/') }}" class="auth-logo-light">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ asset('images/logo-light.svg') }}" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>

                                    <a href="{{ url('/') }}" class="auth-logo-dark">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ asset('images/logo.svg') }}" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form class="form-horizontal" id="loginForm" action="<?php echo htmlspecialchars(@$_SERVER["PHP_SELF"]); ?>" method="post">
        
                                        <div class="mb-3 <?= !empty(@$useremail_err) ? 'has-error' : ''; ?>">
                                            <label for="username" class="form-label">Useremail</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter useremail">
                                            <span class="text-danger"><?php echo @@$useremail_err; ?></span>
                                        </div>
                
                                        <div class="mb-3 <?= !empty(@@$password_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label">Password</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" class="form-control" placeholder="Enter password" id="password" name="password" aria-label="Password" aria-describedby="password-addon">
                                                <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                
                                            </div>
                                            <span class="text-danger"><?php echo @$password_err; ?></span>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-check">
                                            <label class="form-check-label" for="remember-check">
                                                Remember me
                                            </label>
                                        </div>
                                        
                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-success waves-effect waves-light" type="submit">Log In</button>
                                        </div>
            
                                        <div class="mt-4 text-center">
                                            <h5 class="font-size-14 mb-3">Sign in with</h5>
            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <a href="auth-recoverpw.php" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            
                            <div>
                                <p>Don't have an account ? <a href="auth-register.php" class="fw-medium text-success"> Signup now </a> </p>
                                <p>© <script>document.write(new Date().getFullYear())</script> WNSS. Crafted with <i class="mdi mdi-heart text-danger"></i> by M Irwansyah S</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('libs/node-waves/waves.min.js') }}"></script>
        
        <!-- App js -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
        document.getElementById("loginForm").addEventListener("submit", async function(e) {
          e.preventDefault();
          
          const email = document.getElementById("username").value;
          const password = document.getElementById("password").value;

          try {
            const res = await fetch("{{ url('api/login') }}", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            if (res.ok) {
              alert("Login berhasil");
              localStorage.setItem("token", data.token); // simpan token jika pakai Sanctum/JWT
              window.location.href = "/apps"; // redirect ke halaman dashboard
            } else {
              alert("Gagal login: " + (data.message || "Unknown error"));
            }
          } catch (err) {
            console.error("Error:", err);
            alert("Terjadi kesalahan koneksi");
          }
        });
        </script>

    </body>
</html>