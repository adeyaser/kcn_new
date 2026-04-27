<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KCN Terminal Operating System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ffffff;
            --primary-light: #f8fafc;
            --accent: #0056b3;
            --accent-hover: #004494;
            --accent-glow: rgba(0, 86, 179, 0.1);
            --surface: #ffffff;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #dc3545;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            overflow: hidden;
            position: relative;
        }
        /* Light dynamic background */
        .bg-animation {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e7f1ff 100%);
        }
        .bg-animation::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(0,86,179,0.03) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(14,165,233,0.04) 0%, transparent 50%);
            animation: pulse-bg 8s ease-in-out infinite alternate;
        }
        @keyframes pulse-bg {
            0% { opacity: 0.6; } 100% { opacity: 1; }
        }
        /* Floating particles */
        .particle {
            position: absolute; border-radius: 50%;
            background: rgba(0,86,179,0.1);
            animation: float-particle linear infinite;
        }
        @keyframes float-particle {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }
        /* Ship silhouette */
        .ship-silhouette {
            position: fixed; bottom: -5px; left: 0; right: 0;
            height: 120px; z-index: 1; opacity: 0.05;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M0,120 L50,120 L80,80 L120,60 L200,55 L250,50 L300,48 L500,45 L600,40 L650,38 L700,35 L800,38 L900,45 L950,50 L1000,55 L1050,65 L1100,80 L1150,100 L1200,120 Z' fill='%230056b3'/%3E%3Crect x='350' y='20' width='300' height='25' rx='3' fill='%230056b3'/%3E%3Crect x='370' y='0' width='40' height='20' rx='2' fill='%230056b3'/%3E%3C/svg%3E") no-repeat center bottom;
            background-size: 80% auto;
        }
        /* Login card */
        .login-wrapper {
            position: relative; z-index: 10;
            width: 100%; max-width: 440px; padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border: 2px solid var(--danger); /* Full red thread border */
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
        }
        .brand-section { text-align: center; margin-bottom: 36px; }
        .brand-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, var(--accent), var(--info));
            border-radius: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(0,86,179,0.2);
        }
        .brand-icon i { font-size: 32px; color: white; }
        .brand-title {
            font-size: 24px; font-weight: 700; color: var(--accent);
            letter-spacing: -0.5px; margin-bottom: 6px;
        }
        .brand-subtitle { font-size: 13px; color: var(--text-muted); font-weight: 400; }
        /* Form inputs */
        .form-floating { margin-bottom: 16px; }
        .form-floating .form-control {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 14px; height: 56px;
            color: var(--text); font-size: 14px;
            padding-left: 48px;
            transition: all 0.3s ease;
        }
        .form-floating .form-control:focus {
            background: #ffffff;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            color: var(--text);
        }
        .form-floating label {
            color: var(--text-muted); padding-left: 48px; font-size: 14px;
        }
        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            color: var(--accent); transform: scale(0.8) translateY(-0.6rem) translateX(0.15rem);
        }
        .input-icon {
            position: absolute; left: 18px; top: 50%; transform: translateY(-50%);
            color: var(--text-dim); z-index: 5; font-size: 16px;
            transition: color 0.3s;
        }
        .form-floating:focus-within .input-icon { color: var(--accent); }
        .toggle-password {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: var(--text-dim);
            cursor: pointer; z-index: 5; font-size: 16px;
            transition: color 0.3s;
        }
        .toggle-password:hover { color: var(--accent); }
        .btn-login {
            width: 100%; height: 52px; border: none; border-radius: 14px;
            background: #0ea5e9 !important; /* Solid Light Blue */
            color: #ffffff !important;
            font-size: 15px; font-weight: 700;
            cursor: pointer; position: relative;
            transition: all 0.3s ease; margin-top: 8px;
            box-shadow: 0 4px 15px rgba(14,165,233,0.3) !important;
            display: flex !important; align-items: center; justify-content: center;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0,86,179,0.2);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login .spinner-border { display: none; width: 20px; height: 20px; }
        .btn-login.loading .btn-text { display: none; }
        .btn-login.loading .spinner-border { display: inline-block; }
        .footer-text {
            text-align: center; margin-top: 28px;
            font-size: 12px; color: var(--danger);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    <div class="ship-silhouette"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-section">
                <?php if(isset($settings['terminal_logo']) && !empty($settings['terminal_logo'])): ?>
                    <img src="<?= base_url($settings['terminal_logo']) ?>" alt="Logo" style="max-height: 80px; max-width: 100%; margin-bottom: 20px; object-fit: contain;">
                <?php else: ?>
                    <div class="brand-icon"><i class="fas fa-anchor"></i></div>
                <?php endif; ?>
                <h1 class="brand-title"><?= isset($settings['app_name']) ? $settings['app_name'] : 'KCN Terminal' ?></h1>
                <p class="brand-subtitle">Terminal Operating System</p>
            </div>

            <form id="loginForm" autocomplete="off">
                <div class="form-floating position-relative">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" id="username" name="username" placeholder=" " required autofocus>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating position-relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <button type="button" class="toggle-password" onclick="togglePass()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <button type="submit" class="btn btn-login" id="btnLogin">
                    <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Sign In</span>
                    <span class="spinner-border spinner-border-sm"></span>
                </button>
            </form>

            <div class="footer-text">
                &copy; <?= date('Y') ?> KCN Terminal Petikemas &middot; v1.0
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Create particles
        (function(){
            const body = document.body;
            for(let i=0;i<20;i++){
                const p = document.createElement('div');
                p.className='particle';
                const size = Math.random()*4+2;
                p.style.cssText=`width:${size}px;height:${size}px;left:${Math.random()*100}%;animation-duration:${Math.random()*10+10}s;animation-delay:${Math.random()*10}s;`;
                body.appendChild(p);
            }
        })();

        function togglePass(){
            const p=document.getElementById('password'),e=document.getElementById('eyeIcon');
            if(p.type==='password'){p.type='text';e.classList.replace('fa-eye','fa-eye-slash');}
            else{p.type='password';e.classList.replace('fa-eye-slash','fa-eye');}
        }

        $('#loginForm').on('submit', function(e){
            e.preventDefault();
            const btn=$('#btnLogin');
            btn.addClass('loading').prop('disabled',true);

            $.ajax({
                url: '<?= site_url("auth/login") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res){
                    if(res.status==='success'){
                        Swal.fire({icon:'success',title:'Login Berhasil!',text:'Mengalihkan ke dashboard...',timer:1500,showConfirmButton:false,
                            background:'#ffffff',color:'#1e293b'}).then(()=>{window.location.href=res.redirect;});
                    } else {
                        Swal.fire({icon:'error',title:'Login Gagal',text:res.message,background:'#ffffff',color:'#1e293b',
                            confirmButtonColor:'#0056b3'});
                        btn.removeClass('loading').prop('disabled',false);
                    }
                },
                error: function(){
                    Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan server',background:'#ffffff',color:'#1e293b'});
                    btn.removeClass('loading').prop('disabled',false);
                }
            });
        });
    </script>
</body>
</html>
