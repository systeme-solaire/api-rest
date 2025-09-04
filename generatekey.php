<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>L'OpenData du Système solaire - Inscription - Clé API</title>
        <style>

        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 320px;
            text-align: center;
        }
        input[type="email"], input[type="text"] {
            width: 100%;
            padding: 0.5rem;
            margin: 1rem 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            background: #007BFF;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        #message {
        margin-top: 1rem;
        font-size: 0.9rem;
        }
        #message.success {
        color: green;
        }
        #message.error {
        color: red;
        }
        .captcha-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 1rem 0;
        }
        .captcha-container img {
        border: 1px solid #ccc;
        border-radius: 6px;
        cursor: pointer;
        }
        </style>
        <meta name="description" content="Inscription pour obtenir une clé API de l'OpenData du système solaire">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="favicon.ico">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootsnav.min.css">
        <link rel="stylesheet" href="assets/css/style.min.css">
        <link rel="stylesheet" href="assets/css/responsive.min.css" />
        <meta property="og:title" content="Inscription pour obtenir une clé API de l'OpenData">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://api.le-systeme-solaire.net/register.html">
        <meta property="og:image" content="https://api.le-systeme-solaire.net/assets/images/api.png">
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:site" content="@systeme_solaire" />
        <meta property="twitter:url" content="https://www.le-systeme-solaire.net/register.html" />
        <meta property="twitter:title" content="L'OpenData du Système solaire" />
        <meta property="twitter:description" content="Inscription pour obtenir une clé API de l'OpenData du système solaire" />
        <meta property="twitter:image" content="https://api.le-systeme-solaire.net/assets/images/api.png" />
        <link rel="canonical" href="https://api.le-systeme-solaire.net/register.html">
        <script type="application/ld+json">
            {
            "@context": "http://schema.org",
            "@type": "WebPage",
            "headline": "Inscription pour obtenir une clé API de l'OpenData du système solaire",
            "dateCreated": "2018-05-02",
            "datePublished": "2020-11-14",
            "dateModified": "2020-11-14",
            "keywords": "OpenData, API",
            "mainEntityOfPage": "https://api.le-systeme-solaire.net",
            "url": "https://api.le-systeme-solaire.net/register.html",
            "image": [
                "https://api.le-systeme-solaire.net/assets/images/api.png"
            ],
            "author": {
                "@type": "Organization",
                "name": "Christophe"
                },
            "publisher": {
                "@type": "Organization",
                "name": "Christophe",
                "logo": {
                    "@type": "ImageObject",
                    "url": "https://api.le-systeme-solaire.net/assets/images/api.png"
                    }
                },
            "description": "Inscription pour obtenir une clé API de l'OpenData du système solaire"
            }
        </script>
    </head>
    <body>
        <div class="culmn">
            <nav class="navbar navbar-default bootsnav navbar-fixed white ">
                <div class="container">  
                    <div class="attr-nav">
                        <ul>
                            <li><a href="/en/" title="English version" class="font-weight">En</a></li>
                            <li><a href="https://github.com/systeme-solaire" target="_blank"><i class="fa fa-github"></i></a></li>
                            <li><a href="https://www.facebook.com/le.systeme.solaire" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/systeme_solaire" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.le-systeme-solaire.net" class="syssollinkmini"></a></li>
                        </ul>
                    </div>        
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="https://api.le-systeme-solaire.net">
                            <img src="assets/images/logo.png" class="logo logo-scrolled" alt="L'OpenData du Système solaire">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-center">
                            <li></li>
                        </ul>
                    </div>
                </div>   
            </nav>

            <section id="home" class="home">
                <div class="container">
                    <div class="row">
                        <div class="main_home">
                            <div class="col-md-12 col-xs-offset-4">
                                <div class="card">
                                    <h2>Obtenez votre clé API</h2>
                                    <p>Entrez votre adresse email pour recevoir une clé gratuite.</p>

                                    <form id="registerForm">
                                        <input type="email" name="email" placeholder="Votre email" required>

                                        <div class="captcha-container">
                                            <img src="include/captcha.php" id="captchaImage" alt="captcha" title="Cliquez pour recharger">
                                            <input type="text" name="captcha" placeholder="Code captcha" required>
                                        </div>
                                        <button type="submit" class="btn btn-access m-top-20">Obtenir ma clé</button>
                                    </form>

                                    <div id="message"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
            
        
            <div class="scrollup">
                <a href="#"><i class="fa fa-chevron-up"></i></a>
            </div>
            
            <footer id="footer" class="footer bg-black m-top-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <nav class="navbar navbar-default bootsnav footer-menu no-background">
                                <div class="container">  
                                    <div class="syssollink">
                                        <p>Par le même auteur : <a href="https://www.le-systeme-solaire.net">Le Système solaire à portée de votre souris</a>.</p>
                                    </div>
                                </div>   
                            </nav>
                        </div>
                        <div class="divider"></div>
                        <div class="col-md-12">
                            <div class="main_footer text-center p-top-40 p-bottom-30">
                                <p class="wow fadeInRight" data-wow-duration="1s">
                                    Fait avec <span class="text-danger">&#9829;</span> en <img src="/assets/images/auvergne.png" class="auvergne" alt="Auvergne"> par Christophe - Copyright &copy; 2019-<script>document.write(new Date().getFullYear());</script> Tous droits réservés
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <script src="assets/js/vendor/jquery-1.11.2.min.js"></script>
        <script src="assets/js/vendor/bootstrap.min.js"></script>
        <script src="assets/js/jquery.easing.1.3.js"></script>
        <script src="assets/js/main.js"></script>

        <script>
            // Reload captcha image on click
            document.getElementById("captchaImage").addEventListener("click", function() {
            this.src = "captcha.php?" + Date.now(); // éviter le cache
            });

            document.getElementById("registerForm").addEventListener("submit", function(e) {
            e.preventDefault(); // Empêche rechargement

            let formData = new FormData(this);

            fetch("register.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("message").textContent = data;
                document.getElementById("message").className = data.includes("❌") ? "error" : "success";
            })
            .catch(error => {
                document.getElementById("message").textContent = "❌ Une erreur est survenue.";
                document.getElementById("message").className = "error";
            });
            });
        </script>
    </body>
</html>
