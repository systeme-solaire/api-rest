<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>L'OpenData du Système solaire - Validation - Clé API</title>
        <meta name="description" content="Validation de la clé API de l'OpenData du système solaire">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="favicon.ico">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootsnav.min.css">
        <link rel="stylesheet" href="assets/css/style.min.css">
        <link rel="stylesheet" href="assets/css/responsive.min.css" />
        <link rel="stylesheet" href="assets/css/apikey.css" />
        <meta property="og:title" content="Validation de la clé API de l'OpenData">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://api.le-systeme-solaire.net/register.html">
        <meta property="og:image" content="https://api.le-systeme-solaire.net/assets/images/api.png">
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:site" content="@systeme_solaire" />
        <meta property="twitter:url" content="https://www.le-systeme-solaire.net/register.html" />
        <meta property="twitter:title" content="L'OpenData du Système solaire" />
        <meta property="twitter:description" content="Validation de la clé API de l'OpenData du système solaire" />
        <meta property="twitter:image" content="https://api.le-systeme-solaire.net/assets/images/api.png" />
        <link rel="canonical" href="https://api.le-systeme-solaire.net/register.html">
        <script type="application/ld+json">
            {
            "@context": "http://schema.org",
            "@type": "WebPage",
            "headline": "Validation de la clé API de l'OpenData du système solaire",
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
            "description": "Validation de la clé API de l'OpenData du système solaire"
            }
        </script>
    </head>
    <body>
        <div class="culmn">
            <nav class="navbar navbar-default bootsnav navbar-fixed white ">
                <div class="container">  
                    <div class="attr-nav">
                        <ul>
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
                </div>   
            </nav>

            <section id="home" class="home">
                <div class="container">
                    <div class="row">
                        <div class="main_home">
                            <div class="col-md-12 col-xs-offset-1">
                                <div class="card">
                                <?php
                                    define("LOADED_AS_MODULE","1");
                                    include_once('include/dbaccess.php');
                                    $GLOBALS['DEBUG']=0;
                                    $lang = 'fr';
                                    DBAccess::ConfigInit();

                                    $lang = $_GET['lang'] ?? 'fr';
                                    $key = $_GET['key'] ?? '';
                                    $stmt = $GLOBALS['BDD']->prepare("UPDATE syssol_tab_api_keys SET is_validated = 1 WHERE api_key = ? LIMIT 1");
                                    $stmt->execute([$key]);

                                    if ($stmt->rowCount()) {
                                        if ($lang=='en'){
                                            echo '<p>✅ Validated key :</p>';
                                        }else{
                                            echo '<p>✅ Clé validée :</p>';
                                        }?>
                                        <div class="key-box" id="apiKey"><?= htmlspecialchars($key) ?></div><br>
                                        <button onclick="copyKey()" class="copy-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            width="18" height="18" viewBox="0 0 24 24" 
                                            fill="none" stroke="currentColor" stroke-width="2" 
                                            stroke-linecap="round" stroke-linejoin="round" 
                                            class="icon">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4
                                                    a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                        <?php if ($lang=='en'){echo 'Copy';}else{echo 'Copie';}?>
                                        </button><br>
                                    <?php } else {
                                        if ($lang=='en'){echo '<p>Invalid key or already active.</p>';}else{echo '<p>Clé invalide ou déjà validée.</p>';}
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
                       
            <footer id="footer" class="footer bg-black m-top-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <nav class="navbar navbar-default bootsnav footer-menu no-background">
                                <div class="container">  
                                    <div class="syssollink">
                                        <?php if ($lang=='en'){
                                            echo '<p>By the same author: <a href="https://www.le-systeme-solaire.net">Le Système solaire à portée de votre souris</a>.</p>';
                                        }else{
                                            echo '<p>Par le même auteur : <a href="https://www.le-systeme-solaire.net">Le Système solaire à portée de votre souris</a>.</p>';
                                        }?>
                                    </div>
                                </div>   
                            </nav>
                        </div>
                        <div class="divider"></div>
                        <div class="col-md-12">
                            <div class="main_footer text-center p-top-40 p-bottom-30">
                                <p class="wow fadeInRight" data-wow-duration="1s">
                                <?php if ($lang=='en'){
                                            echo 'Made with <span class="text-danger">&#9829;</span> in <img src="../assets/images/auvergne.png" class="auvergne" alt="Auvergne"> by Christophe - Copyright &copy; 2019-<script>document.write(new Date().getFullYear());</script> All rights reserved.';
                                        }else{
                                            echo 'Fait avec <span class="text-danger">&#9829;</span> en <img src="/assets/images/auvergne.png" class="auvergne" alt="Auvergne"> par Christophe - Copyright &copy; 2019-<script>document.write(new Date().getFullYear());</script> Tous droits réservés';
                                        }?>    
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
            function copyKey() {
                const key = document.getElementById("apiKey").innerText;
                navigator.clipboard.writeText(key).then(() => {
                }).catch(err => {
                    console.error("Erreur lors de la copie :", err);
                });
            }
        </script>
    </body>
</html>
