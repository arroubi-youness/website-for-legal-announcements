<?php

class Commissaire {
    private $pdo;
    private $type;
    private $civilite;
    private $nom;
    private $prenom;
    private $adresse;
    private $code_postal;
    private $denomination;

    private $ville;

    public function __construct($type, $civilite, $nom, $prenom, $adresse, $code_postal,$denomination, $ville) {
        $this->type = $this->sanitizeInput($type);
        $this->civilite = $this->sanitizeInput($civilite);
        $this->nom = $this->sanitizeInput($nom);
        $this->prenom = $this->sanitizeInput($prenom);
        $this->adresse = $this->sanitizeInput($adresse);
        $this->code_postal = $this->sanitizeInput($code_postal);
        $this->denomination = $this->sanitizeInput($denomination);

        $this->ville = $this->sanitizeInput($ville);

        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=annonces_legales", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    public function insertToCommissaire() {
        $stmt = $this->pdo->prepare("
            INSERT INTO commissaire (Type,Dénomination,Civilité,Nom,Prénom,Adresse,Code_Postal,Ville) 
            VALUES (:type,:denominatoin, :civilite, :nom, :prenom, :adresse, :code_postal, :ville)
        ");

        try {
            $stmt->execute([
                ':type' => $this->type,
                ':civilite' => $this->civilite,
                ':nom' => $this->nom,
                ':prenom' => $this->prenom,
                ':adresse' => $this->adresse,
                ':code_postal' => $this->code_postal,
                ':denominatoin' => $this->denomination,

                ':ville' => $this->ville
            ]);
            echo "Record inserted successfully.";
            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    public function __destruct() {
        $this->pdo = null;  
    }
}

?>