Le code de l'OpenData du Système Solaire présent sur https://api.le-systeme-solaire.net

- /bodies expose les données des corps du Système solaire (issu d'une table SQL `syssol_tab_donnees`)

L'ensemble des données sont stockées en colonne :

    - CPT_CORPS, 
    - NOM, 
    - BL_PLANETE, 
    - CPTE_CORPS, 
    - NOM_ANGLAIS, 
    - DEMIGRAND_AXE, 
    - DECOUV_QUI, 
    - DECOUV_QD, 
    - DES_TEMPO, 
    - MASS_VAL, 
    - MASS_UNIT, 
    - DENSITY, 
    - GRAVITY, 
    - ESCAPE, 
    - VOL_VAL, 
    - VOL_UNIT, 
    - PERIHELION, 
    - APHELION, 
    - ECCENTRICITY, 
    - INCLINATION, 
    - EQUA_RADIUS, 
    - MEAN_RADIUS, 
    - POLAR_RADIUS, 
    - FLATTENING, 
    - SIDERAL_ORBIT, 
    - SIDERAL_ROTATION, 
    - DIMENSION
    - INCLINAISON_AXE

- /knowncount renvoie le nombre de corps de chaque catégorie (table SQL `syssol_tab_known`)