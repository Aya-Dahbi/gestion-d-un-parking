  <?php if (!defined('APP')) { header('Location: ../index.php'); exit(); } ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SpotPark - Connexion / Inscription</title> 
    <link rel="stylesheet" href="views/login.css">
</head>
<body>
    <div class="left-side">
        <div class="logo-container">
            <div class="logo-box">P</div>
            <div class="logo-text">SpotPark</div>
        </div>
        <div class="left-content">
            <h1>Votre Place vous attend .</h1>
            <p>Réservez, stationnez et repartez. ParkEasy transforme chaque trajet en une expérience sans stress.</p>
        </div>  
    </div>

    <div class="right-side">
        <div class="form-container">
            <div class="form-header">
                <?php if (!empty($message)): ?>
          <div class="alert"><?= htmlspecialchars($message) ?></div>
           <?php endif; ?>
                <h2 id="form-title">Connectez-vous</h2>
                <p id="form-subtitle">Accédez à vos réservations et trouvez votre prochaine place.</p>
            </div>

            <div class="tabs">
                <div class="tab active" onclick="switchTab('login')">Connexion</div>
                <div class="tab" onclick="switchTab('register')">Inscription</div>
            </div>

           
            <form id="login-form" class="form-section active" action="" method="POST">
                <input type="hidden" name="action" value="connexion">
                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <input type="email" name="email" placeholder="aya@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="•••••••" required>
                </div>

                <div class="form-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Rester connecté
                    </label>
                </div>
                
                <button type="submit" class="submit-btn">Se connecter →</button>
            </form>

            
            <form id="register-form" class="form-section" action="" method="POST">
                <input type="hidden" name="action" value="inscription">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="nom" placeholder="Aya Dahbi" required>
                </div>

                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <input type="email" name="email" placeholder="aya@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="•••••••" required>
                </div>

                <button type="submit" class="submit-btn">S'inscrire →</button>
            </form>
        </div>
    </div>

 
    <script>
        function switchTab(type) {
            const tabs = document.querySelectorAll('.tab');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const formTitle = document.getElementById('form-title');
            const formSubtitle = document.getElementById('form-subtitle');

            if (type === 'login') {
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
                formTitle.innerText = "Connectez-vous";
                formSubtitle.innerText = "Accédez à vos réservations et trouvez votre prochaine place.";
            } else {
                tabs[1].classList.add('active');
                tabs[0].classList.remove('active');
                registerForm.classList.add('active');
                loginForm.classList.remove('active');
                formTitle.innerText = "Créez un compte";
                formSubtitle.innerText = "Inscrivez-vous pour commencer à réserver vos places.";
            }
        }
    </script>
</body>
</html>