<?php

// ================== INTERFACES ==================

interface Authentifiable {
    public function seConnecter();
}

interface Affichable {
    public function afficher();
}


class Personne {
    private $id;
    private $nom;
    private $email;

    public function __construct($id, $nom, $email) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }

    public function setNom($nom) { $this->nom = $nom; }
    public function setEmail($email) { $this->email = $email; }

    public function afficherInfos() {
        return "ID: $this->id, Nom: $this->nom, Email: $this->email";
    }
}


abstract class Utilisateur extends Personne implements Authentifiable, Affichable {
    protected $login;
    protected $motDePasse;
    public static $nombreUtilisateurs = 0;

    public function __construct($id, $nom, $email, $login, $motDePasse) {
        parent::__construct($id, $nom, $email);
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        self::$nombreUtilisateurs++;
    }

    public function seConnecter() {
        return "Connexion de $this->login réussie";
    }

    public static function afficherNombre() {
        return "Nombre d'utilisateurs: " . self::$nombreUtilisateurs;
    }

    abstract public function afficherRole();

    public function afficher() {
        return $this->afficherInfos();
    }
}


class Client extends Utilisateur {
    private $typeClient;

    const TAUX_SIMPLE = 0.05;
    const TAUX_PREMIUM = 0.15;

    public function __construct($id, $nom, $email, $login, $motDePasse, $typeClient) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->typeClient = $typeClient;
    }

    public function calculerReduction($montant) {
        if ($this->typeClient == "premium") {
            return $montant * self::TAUX_PREMIUM;
        } else {
            return $montant * self::TAUX_SIMPLE;
        }
    }

    public function afficherInfos() {
        return parent::afficherInfos() . ", Type: $this->typeClient";
    }

    public function afficherRole() {
        return "Je suis un client";
    }

    public function afficher() {
        return $this->afficherInfos();
    }
}



class Employe extends Utilisateur {
    private $salaire;

    public function __construct($id, $nom, $email, $login, $motDePasse, $salaire) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->salaire = $salaire;
    }

    public function calculerSalaireAnnuel() {
        return $this->salaire * 12;
    }

    public function afficherRole() {
        return "Je suis un employé";
    }

    public function afficher() {
        return $this->afficherInfos() . ", Salaire: $this->salaire";
    }
}



class Administrateur extends Utilisateur {

    public function supprimerUtilisateur($user) {
        return "Utilisateur supprimé";
    }

    public function afficherRole() {
        return "Je suis un administrateur";
    }

    public function afficher() {
        return $this->afficherInfos();
    }
}

// ================== POLYMORPHISME =================

function afficherUtilisateur(Affichable $u) {
    echo $u->afficher() . "<br>";
}

// ================== TEST ==================

$client = new Client(1, "Fatou", "fatou@mail.com", "fatou123", "pass", "premium");
$employe = new Employe(2, "Ali", "ali@mail.com", "ali123", "pass", 200000);
$admin = new Administrateur(3, "Admin", "admin@mail.com", "admin", "pass");

echo "<h3>Connexion</h3>";
echo $client->seConnecter() . "<br>";

echo "<h3>Client</h3>";
echo "Réduction: " . $client->calculerReduction(10000) . "<br>";

echo "<h3>Employé</h3>";
echo "Salaire annuel: " . $employe->calculerSalaireAnnuel() . "<br>";

echo "<h3>Admin</h3>";
echo $admin->supprimerUtilisateur($client) . "<br>";

echo "<h3>Polymorphisme</h3>";
afficherUtilisateur($client);
afficherUtilisateur($employe);
afficherUtilisateur($admin);

echo "<h3>Statique</h3>";
echo Utilisateur::afficherNombre();


?>
