@extends('Dashboard.layouts.master2')

@section('css')
    <style>
        :root {
            --hospital-primary: #1a7bc8;
            --hospital-secondary: #0d3c61;
            --hospital-accent: #4cc9f0;
            --hospital-light: #f8f9fa;
        }

        .panel {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hospital-theme {
            background: linear-gradient(135deg, var(--hospital-primary) 0%, var(--hospital-secondary) 100%);
            position: relative;
            overflow: hidden;
        }

        .hospital-theme::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(76, 201, 240, 0.1) 0%, transparent 70%);
            animation: gradientPulse 15s infinite alternate;
        }

        @keyframes gradientPulse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .login-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .login-card:hover {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .login-image-container {
            position: relative;
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            perspective: 1500px;
        }

        .login-image {
            width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 30px 60px -10px rgba(0, 0, 0, 0.4);
            transform-style: preserve-3d;
            transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 10px solid rgba(255, 255, 255, 0.15);
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.4));
            position: relative;
            z-index: 2;
        }

        .login-image:hover {
            transform: translateY(-15px) rotateX(5deg) scale(1.02);
            box-shadow: 0 40px 70px -15px rgba(0, 0, 0, 0.5);
        }

        .login-image-caption {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%) translateZ(40px);
            width: 90%;
            text-align: center;
            background: linear-gradient(135deg, rgba(26, 123, 200, 0.95) 0%, rgba(13, 60, 97, 0.95) 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 3;
        }

        .login-image-caption h3 {
            font-weight: 800;
            margin-bottom: 8px;
            font-size: 1.8rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .login-image-caption p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .hospital-logo {
            font-weight: 800;
            color: var(--hospital-primary);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 2.2rem;
            transition: all 0.3s ease;
        }

        .hospital-logo:hover {
            color: var(--hospital-secondary);
            transform: scale(1.05);
        }

        .hospital-logo span {
            color: var(--hospital-secondary);
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--hospital-accent);
            box-shadow: 0 0 0 0.3rem rgba(76, 201, 240, 0.25);
            transform: translateY(-2px);
        }

        .btn-hospital {
            background: linear-gradient(135deg, var(--hospital-primary) 0%, var(--hospital-secondary) 100%);
            color: white;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            border: none;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 15px rgba(26, 123, 200, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-hospital:hover {
            background: linear-gradient(135deg, var(--hospital-secondary) 0%, var(--hospital-primary) 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(13, 60, 97, 0.4);
        }

        .btn-hospital:active {
            transform: translateY(1px);
        }

        .role-selector {
            border-radius: 12px;
            padding: 14px 20px;
            border: 2px solid #e0e0e0;
            font-weight: 600;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231a7bc8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2em;
        }

        .role-selector:focus {
            border-color: var(--hospital-accent);
            box-shadow: 0 0 0 0.3rem rgba(76, 201, 240, 0.25);
        }

        /* Floating particles animation */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .login-image-container {
                max-width: 450px;
                margin-top: 30px;
            }

            .login-image-caption {
                position: relative;
                bottom: 0;
                left: 0;
                transform: none;
                width: 100%;
                margin-top: 20px;
            }
        }

        /* Micro-interactions */
        .form-group label {
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--hospital-secondary);
        }

        .form-group:focus-within label {
            color: var(--hospital-primary);
            transform: translateX(5px);
        }

        /* Panel header animation */
        .panel h4 {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .panel h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--hospital-primary), var(--hospital-accent));
            border-radius: 2px;
        }

        /* Modern checkbox styling */
        .form-check-input:checked {
            background-color: var(--hospital-primary);
            border-color: var(--hospital-primary);
        }

        /* Link hover effects */
        a {
            transition: all 0.3s ease;
            color: var(--hospital-primary);
            text-decoration: none;
            position: relative;
        }

        a:hover {
            color: var(--hospital-secondary);
        }

        a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--hospital-accent);
            transition: width 0.3s ease;
        }

        a:hover::after {
            width: 100%;
        }

        /* Alert animations */
        .alert {
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .alert-danger {
            border-left: 5px solid #dc3545;
        }
    </style>

    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ asset('Dashboard/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
            <div
                class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex hospital-theme align-items-center justify-content-center">
                <!-- Floating particles -->
                <div class="particles" id="particles-js"></div>

                <div class="login-image-container">
                    <img src="{{ asset('Dashboard/img/media/medical.jpg') }}" class="login-image"
                        alt="Hospital Management System 2025">
                    <div class="login-image-caption">
                        <h3>{{ trans('signinn.Hospital Management System') }}</h3>
                        <p>{{ trans('signinn.Medical Operations and Health Services Management System') }}</p>
                        <div class="mt-3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin login-card">
                                    <div class="mb-5 d-flex justify-content-center align-items-center">
                                        <a href="{{ url('/') }}" class="d-flex align-items-center">
                                            <img src="{{ asset('Dashboard/img/brand/hospital-logo.png') }}"
                                                class="sign-favicon ht-50" alt="Hospital Logo">
                                            <h1 class="hospital-logo ml-3 mr-0 my-auto">{{ trans('signinn.hospital') }} ....
                                            </h1>
                                        </a>
                                    </div>

                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h2 class="text-center mb-4">{{ trans('signinn.Hospital Management System') }}
                                            </h2>
                                            @if ($errors->any())
                                                <div class="alert alert-danger alert-dismissible fade show auto-dismiss"
                                                    role="alert">
                                                    <strong>{{ trans('signinn.Error in registration!') }}</strong>
                                                    <ul class="mb-0">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="sectionChooser"
                                                    class="font-weight-bold">{{ trans('signinn.Entry as:') }}</label>
                                                <select class="form-control role-selector" id="sectionChooser">
                                                    <option value="" selected disabled>
                                                        {{ trans('signinn.Choose account type.') }}..</option>
                                                    <option value="admin">{{ trans('signinn.System Manager') }}</option>
                                                    <option value="doctor">{{ trans('signinn.Doctor') }}</option>
                                                    <option value="ray_employee">{{ trans('signinn.Radiological staff') }}
                                                    </option>
                                                    <option value="laboratorie_employee">
                                                        {{ trans('signinn.laboratory staff') }}</option>
                                                    <option value="patient">{{ trans('signinn.patient') }}</option>
                                                </select>
                                            </div>

                                            <!-- Admin Panel -->
                                            <div class="panel" id="admin">
                                                <h4 class="text-center mb-4">{{ trans('signinn.Login System Manager') }}
                                                </h4>
                                                <form method="POST" action="{{ route('login.admin') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.email') }}</label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('signinn.enter your email') }}"
                                                            type="email" name="email" value="{{ old('email') }}"
                                                            required autofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.password') }}</label>
                                                        <div class="input-group">
                                                            <input class="form-control"
                                                                placeholder="{{ trans('signinn.enter your password') }}"
                                                                type="password" name="password" id="adminpassword" required>
                                                            <span class="input-group-text toggle-password"
                                                                data-target="#adminpassword">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-check mt-3">
                                                        <input type="checkbox" class="form-check-input" id="adminRemember">
                                                        <label class="form-check-label"
                                                            for="adminRemember">{{ trans('signinn.remember me') }}</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-hospital btn-block mt-4"
                                                        style="color: white">
                                                        <i class="fas fa-sign-in-alt mr-2" style="color: white"></i>
                                                        {{ trans('signinn.login') }}
                                                    </button>
                                                </form>
                                                <div class="main-signin-footer mt-4 text-center">
                                                    <p class="mb-2"><a
                                                            href="{{ route('admin.password.request')}}">{{ trans('signinn.forgot password') }}</a>
                                                    </p>
                                                    <p class="mb-0">{{ trans('signinn.Having trouble getting in?') }}<a
                                                            href="#">{{ trans('signinn.Contact Technical Support') }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Doctor Panel -->
                                            <div class="panel" id="doctor">
                                                <h4 class="text-center mb-4">{{ trans('signinn.Doctor Login') }}</h4>
                                                <form method="POST" action="{{ route('login.doctor') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.email') }}</label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('signinn.enter your email') }}"
                                                            type="email" name="email" value="{{ old('email') }}"
                                                            required autofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.password') }}</label>
                                                        <div class="input-group">
                                                            <input class="form-control"
                                                                placeholder="{{ trans('signinn.enter your password') }}"
                                                                type="password" name="password" id="doctorpassword"
                                                                required>
                                                            <span class="input-group-text toggle-password"
                                                                data-target="#doctorpassword">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-check mt-3">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="doctorRemember">
                                                        <label class="form-check-label"
                                                            for="doctorRemember">{{ trans('signinn.remember me') }}</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-hospital btn-block mt-4"
                                                        style="color: white">
                                                        <i class="fas fa-user-md mr-2" style="color: white"></i>
                                                        {{ trans('signinn.login') }}
                                                    </button>
                                                </form>
                                                <div class="main-signin-footer mt-4 text-center">
                                                    <p class="mb-2"><a
                                                            href="{{ route('doctor.password.request') }}">{{ trans('signinn.forgot password') }}</a>
                                                    </p>
                                                    <p class="mb-0">{{ trans('signinn.Having trouble getting in?') }} <a
                                                            href="#">{{ trans('signinn.Contact Technical Support') }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Ray Employee Panel -->
                                            <div class="panel" id="ray_employee">
                                                <h4 class="text-center mb-4">{{ trans('signinn.Radiology Staff Login') }}
                                                </h4>
                                                <form method="POST" action="{{ route('login.ray_employee') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.email') }}</label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('signinn.enter your email') }}"
                                                            type="email" name="email" value="{{ old('email') }}"
                                                            required autofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.password') }}</label>
                                                        <div class="input-group">
                                                            <input class="form-control"
                                                                placeholder="{{ trans('signinn.enter your password') }}"
                                                                type="password" name="password" id="raypassword"
                                                                required>
                                                            <span class="input-group-text toggle-password"
                                                                data-target="#raypassword">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-check mt-3">
                                                        <input type="checkbox" class="form-check-input" id="rayRemember">
                                                        <label class="form-check-label"
                                                            for="rayRemember">{{ trans('signinn.remember me') }}</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-hospital btn-block mt-4"
                                                        style="color: white">
                                                        <i class="fas fa-x-ray mr-2" style="color: white"></i>
                                                        {{ trans('signinn.login') }}
                                                    </button>
                                                </form>
                                                <div class="main-signin-footer mt-4 text-center">
                                                    <p class="mb-2"><a
                                                            href="{{ route('ray_employee.password.request') }}">{{ trans('signinn.forgot password') }}</a>
                                                    </p>
                                                    <p class="mb-0">{{ trans('signinn.Having trouble getting in?') }} <a
                                                            href="#">{{ trans('signinn.Contact Technical Support') }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Laboratory Employee Panel -->
                                            <div class="panel" id="laboratorie_employee">
                                                <h4 class="text-center mb-4">{{ trans('signinn.Laboratory Staff Login') }}
                                                </h4>
                                                <form method="POST" action="{{ route('login.laboratorie_employee') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.email') }}</label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('signinn.enter your email') }}"
                                                            type="email" name="email" value="{{ old('email') }}"
                                                            required autofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.password') }}</label>
                                                        <div class="input-group">
                                                            <input class="form-control"
                                                                placeholder="{{ trans('signinn.enter your password') }}"
                                                                type="password" name="password" id="labpassword"
                                                                required>
                                                            <span class="input-group-text toggle-password"
                                                                data-target="#labpassword">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-check mt-3">
                                                        <input type="checkbox" class="form-check-input" id="labRemember">
                                                        <label class="form-check-label"
                                                            for="labRemember">{{ trans('signinn.remember me') }}</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-hospital btn-block mt-4"
                                                        style="color: white">
                                                        <i class="fas fa-flask mr-2" style="color: white"></i>
                                                        {{ trans('signinn.login') }}
                                                    </button>
                                                </form>
                                                <div class="main-signin-footer mt-4 text-center">
                                                    <p class="mb-2"><a
                                                            href="{{ route('laboratorie_employee.password.request') }}">{{ trans('signinn.forgot password') }}</a>
                                                    </p>
                                                    <p class="mb-0">{{ trans('signinn.Having trouble getting in?') }} <a
                                                            href="#">{{ trans('signinn.Contact Technical Support') }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Patient Panel -->
                                            <div class="panel" id="patient">
                                                <h4 class="text-center mb-4">{{ trans('signinn.Patient Login') }}</h4>
                                                <form method="POST" action="{{ route('login.patient') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.email') }}</label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('signinn.enter your email') }}"
                                                            type="email" name="email" value="{{ old('email') }}"
                                                            required autofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('signinn.password') }}</label>
                                                        <div class="input-group">
                                                            <input class="form-control"
                                                                placeholder="{{ trans('signinn.enter your password') }}"
                                                                type="password" name="password" id="patientpassword"
                                                                required>
                                                            <span class="input-group-text toggle-password"
                                                                data-target="#patientpassword">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-check mt-3">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="patientRemember">
                                                        <label class="form-check-label"
                                                            for="patientRemember">{{ trans('signinn.remember me') }}</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-hospital btn-block mt-4"
                                                        style="color: white">
                                                        <i class="fas fa-user-injured mr-2" style="color: white"></i>
                                                        {{ trans('signinn.login') }}
                                                    </button>
                                                </form>
                                                <div class="main-signin-footer mt-4 text-center">
                                                    <p class="mb-2"><a
                                                            href="{{ route('patient.password.request') }}">{{ trans('signinn.forgot password') }}</a>
                                                    </p>
                                                    <p class="mb-0">{{ trans('signinn.Don\'t have an account?') }} <a
                                                            href="{{ route('register.patient') }}">{{ trans('signinn.Create new account') }}</a>
                                                    </p>
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
        </div>
    </div>
@endsection

@section('js')
    <!-- Particles.js for background animation -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize particles.js
            particlesJS("particles-js", {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });

            // Show the selected panel with animation
            $('#sectionChooser').change(function() {
                var myID = $(this).val();
                $('.panel').each(function() {
                    if (myID === $(this).attr('id')) {
                        $(this).show().addClass('animate__animated animate__fadeInUp');
                    } else {
                        $(this).hide().removeClass('animate__animated animate__fadeInUp');
                    }
                });

                // Add focus to first input field
                $('#' + myID).find('input:first').focus();
            });

            // Toggle password visibility
            $('.toggle-password').click(function() {
                const target = $(this).data('target');
                const input = $(target);
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Add floating label effect
            $('.form-control').each(function() {
                if ($(this).val() !== '') {
                    $(this).parent().find('label').addClass('active');
                }
            });

            $('.form-control').focus(function() {
                $(this).parent().find('label').addClass('active');
            }).blur(function() {
                if ($(this).val() === '') {
                    $(this).parent().find('label').removeClass('active');
                }
            });

            // Initialize with first panel if there's an error
            @if ($errors->any())
                const errorPanel = '{{ old('login_type') }}';
                $('#sectionChooser').val(errorPanel).change();
            @endif
        });

        // Auto-dismiss alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.auto-dismiss').alert('close');
            }, 5000);

            // يمكنك أيضًا إضافة هذا لمنع مشاكل الـ Bootstrap
            $('.alert').alert();
        });


        // داخل $(document).ready() أو DOMContentLoaded في ملف السكربتات الخاص بصفحة تسجيل الدخول

        // التحقق من وجود رسالة خطأ في الجلسة قادمة من الميدل وير
        @if (session('error'))
            // التأكد من أن مكتبة notifIt معرفة قبل استدعائها
            if (typeof notif !== 'undefined') {
                notif({
                    msg: "<b>خطأ:</b> {{ session('error') }}", // الرسالة من الجلسة
                    type: "error", // نوع التنبيه (خطأ)
                    position: "center", // مكان ظهور الرسالة (أعلى، أسفل، وسط، يمين، يسار) - جرب "top-right" أو "top-center" أيضاً
                    width: 550, // عرض الرسالة بالبكسل (اختياري)
                    height: 60, // ارتفاع الرسالة بالبكسل (اختياري)
                    autohide: true, // إخفاء تلقائي: نعم
                    timeout: 8000, // مدة الظهور بالمللي ثانية (8 ثوان)
                    opacity: 1, // درجة الشفافية
                    multiline: true, // السماح بعدة أسطر
                    fade: true, // تأثير التلاشي عند الظهور/الاختفاء
                    bgcolor: "#dc3545", // لون خلفية مخصص للخطأ (أحمر Bootstrap)
                    color: "#ffffff", // لون النص (أبيض)
                    zindex: 1100, // (اختياري) للتحكم في الطبقة فوق العناصر الأخرى
                    sticky: false, // هل تظل ثابتة حتى يغلقها المستخدم؟ (false للاختفاء التلقائي)
                    clickable: true, // هل يمكن النقر عليها لإغلاقها؟
                    callback: function() { // دالة تُنفذ بعد إغلاق الرسالة
                        // إعادة تحميل الصفحة بعد اختفاء الرسالة
                        setTimeout(function() {
                            window.location.reload();
                        }, 5000); // تأخير بسيط قبل إعادة التحميل (اختياري)
                    }
                });

                // إعادة تحميل الصفحة بعد 8.5 ثانية (8 ثواني للرسالة + 0.5 ثانية كهامش)
                setTimeout(function() {
                    window.location.reload();
                }, 8500);

            } else {
                // حل بديل إذا لم يتم تحميل notifIt.js
                alert("{{ session('error') }}");
                // إعادة تحميل الصفحة بعد 8 ثوان في حالة الـ alert البديل
                setTimeout(function() {
                    window.location.reload();
                }, 8000);
            }
        @endif

        // --- باقي أكواد JavaScript الأخرى لديك ---
        // مثل الكود الخاص بإظهار/إخفاء لوحات تسجيل الدخول بناءً على sectionChooser
        // والكود الخاص بـ toggle-password
        // --- عرض رسالة الخطأ (القادمة من الميدل وير) ---
        @if (session('error'))
            showNotification("<b>خطأ:</b><br>{{ session('error') }}", "error", 8000, true); // 8 ثوان وإعادة تحميل
        @endif

        // --- عرض رسالة النجاح (القادمة من Controller تسجيل الدخول) ---
        @if (session('login_success'))
            showNotification("<b>{{ session('login_success') }}</b>", "success", 3000, false,
                "{{ route('dashboard.doctor') }}"); // 3 ثوان ثم توجيه
        @endif

        // --- دالة موحدة لعرض الإشعارات باستخدام notifIt ---
        function showNotification(message, type = 'info', timeout = 5000, reload = false, redirectUrl = null) {
            if (typeof notif !== 'undefined') {
                let options = {
                    msg: message,
                    type: type,
                    position: "top-center", // وضع ثابت أعلى الوسط
                    width: "auto", // عرض تلقائي حسب المحتوى
                    // height: 60,          // يمكن تعديل الارتفاع إذا لزم الأمر
                    multiline: true, // السماح بعدة أسطر
                    autohide: true,
                    timeout: timeout,
                    opacity: 0.95, // زيادة الوضوح قليلاً
                    fade: true,
                    clickable: true, // السماح بالإغلاق بالنقر
                    // إضافة أيقونات مناسبة للنوع
                    icon: true,
                    // تخصيص ألوان أكثر
                    bgcolor: type === 'success' ? '#20a36a' : (type === 'error' ? '#e8565f' : (type === 'warning' ?
                        '#ffb648' : '#508ff4')), // ألوان أكثر حيوية
                    color: "#ffffff",
                    // إضافة تأثير دخول وخروج باستخدام animate.css
                    animation: 'fadeInDown', // تأثير الدخول
                    // لا يوجد خيار مباشر لتأثير الخروج في notifIt, سنستخدم callback
                    callback: function() {
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        } else if (reload) {
                            window.location.reload();
                        }
                    }
                };
                notif(options);
            } else {
                // حل بديل بسيط جداً
                alert(message.replace(/<br>/g, '\n').replace(/<b>|<\/b>/g, '')); // إزالة HTML للـ alert
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else if (reload) {
                    // الانتظار قليلاً قبل إعادة التحميل في حالة الـ alert
                    setTimeout(() => window.location.reload(), 1000);
                }
            }
        }
        // Auto-dismiss Bootstrap alerts (إذا كنت تستخدمها لأخطاء التحقق)
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert[role="alert"]').alert('close');
            }, 5000); // إغلاق بعد 5 ثواني

            // التأكد من عمل dismiss button
            if ($.fn.alert) { // التحقق من وجود دالة alert في jQuery
                $('.alert .close, .alert .btn-close').on('click', function(e) {
                    e.preventDefault();
                    $(this).closest('.alert').alert('close');
                });
            }
        });
    </script>
@endsection

