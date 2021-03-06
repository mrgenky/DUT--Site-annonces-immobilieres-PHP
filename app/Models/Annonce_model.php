<?php
namespace App\Models;
use CodeIgniter\Model;

//Requêtes concernant les annonces
class Annonce_Model extends Model
{
	//Variables contenant le nom des tables de la BDD
    protected $table = 't_annonce';
    protected $tablePhoto = 't_photo';
    protected $tableType = 't_typemaison';
    protected $tableEnergie = 't_energie';
    protected $tableUti = 't_utilisateur';

    //[INSERT INTO] Requête qui crée une annonce
    //Utilisation : page "Ajouter-une-annonce" avec un formulaire de création d'annonce
    public function insertAnnonce($mail,$titre,$coutlocation,$coutcharges,$type,$superficie,$typechauffage,$modeenergie,$adresse,$ville,$region,$codepostal,$description,$etat){
        $query = 'INSERT INTO '.$this->table. ' VALUES(\'\',"'.$titre.'","'.$coutlocation.'","'.$coutcharges.'","'.$typechauffage.'","'.$superficie.'","'.$description.'","'.$adresse.'","'.$ville.'","'.$region.'","'.$codepostal.'",CURRENT_TIMESTAMP,"'.$etat.'","'.$mail.'","'.$modeenergie.'","'.$type.'")';
        return $this->simpleQuery($query);
    }

    //[INSERT INTO] Requête qui insère une image dans la BDD
    //Utilisation : lorsqu'une annonce est créée et qu'au moins une image est ajoutée dans le formulaire
    public function insertImageAnnonce($nom,$idannonce){
        $query = 'INSERT INTO '.$this->tablePhoto. ' VALUES(\'\',\'\',"'.$nom.'","'.$idannonce.'")';
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie les informations de base d'une annonce, sans limite, par ordre décroissant
    //Utilisation : page "Annonces" qui liste toutes les annonces
	public function getAnnonce($limit){
        $query = 'SELECT A_idannonce,A_titre,A_superficie,A_cout_loyer,A_T_type,A_U_mail,A_type_chauffage,A_ville,A_CP FROM ' .$this->table.' WHERE A_etat = \'publiée\' ORDER BY A_idannonce DESC LIMIT '.$limit;
        return $this->simpleQuery($query);
	}

    //[SELECT] Requête qui renvoie les informations de base d'une annonce, occurrences limitées à 6
    //Utilisation : page d'accueil qui liste les 6 dernières annonces publiées
	public function getAnnonceAccueil(){
		$query = 'SELECT A_idannonce,A_titre,A_superficie,A_cout_loyer,A_T_type,A_U_mail,A_type_chauffage,A_ville,A_CP FROM '.$this->table.' WHERE A_etat = \'publiée\' ORDER BY A_idannonce DESC LIMIT 6';
        return $this->simpleQuery($query);
	}

    //[SELECT] Requête qui renvoie l'etat d'une annonce
    //Utilisation : Utile pour savoir si l'annonce est bloquée ou en brouillon
    public function getEtatAnnonce($idannonce){
	    $query = 'SELECT A_etat FROM '.$this->table.' WHERE A_idannonce = '.$idannonce;
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie toutes les photos
    //Utilisation : partout où on affiche des annonces
    public function getAllphoto(){
        $query = 'SELECT P_nom,P_A_idannonce FROM '.$this->tablePhoto;
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie les informations de base d'une annonce publiée par un utilisateur spécifique
    //Utilisation : page "Mes-annonces" qui liste les annonces d'un utilisateur
    public function getAnnonceUtilisateur($mail){
        $query = 'SELECT A_idannonce,A_titre,A_superficie,A_cout_loyer,A_T_type,A_type_chauffage,A_ville,A_CP,A_etat FROM '.$this->table.' WHERE A_U_mail = \''.$mail.'\' ORDER BY A_idannonce DESC';
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie l'id de l'annonce si elle appartient bien à l'utilisateur
    //Utilisation : page d'accueil qui liste les annonce de l'utilisateur
    public function verifAnnonce($mail,$id){
        return $this->asArray()->select('A_idannonce')->where(['A_U_mail' => $mail])->where(['A_idannonce' => $id])->first();
    }

    //[SELECT] Requête qui renvoie toutes les infos d'une annonce
    //Utilisation : page correspondant à une annonce en particulier
    public function getAnnonceEntiere($idannonce){
        $query = 'SELECT *, DATE_FORMAT(A_date_creation, \'%d-%m-%Y\') AS "A_date_modifiee" FROM '.$this->table.' a JOIN '.$this->tableType.' t ON a.A_T_type = t.T_type JOIN '.$this->tableEnergie.' e ON a.A_E_id_engie = e.E_id_engie JOIN '.$this->tableUti.' u ON a.A_U_mail = u.U_mail WHERE A_idannonce = \''.$idannonce.'\'';
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie toutes les photos d'une annonce
    //Utilisation : page correspondant à une annonce en particulier
    public function getPhotos($idannonce){
        $query = 'SELECT P_nom,P_idphoto FROM ' .$this->table.' INNER JOIN '.$this->tablePhoto.' WHERE A_idannonce = \''.$idannonce.'\' AND A_idannonce = P_A_idannonce ORDER BY A_idannonce DESC';
        return $this->simpleQuery($query);
    }
    //[SELECT] Requête qui renvoie combien de photo l'annonce possède
    //Utilisation : pour éviter que l'utilisateur supprime toutes ses photos
    public function getHowManyPhotos($idannonce){
        $query = 'SELECT COUNT(P_idphoto) AS "nbrphoto" FROM ' .$this->table.' INNER JOIN '.$this->tablePhoto.' WHERE A_idannonce = \''.$idannonce.'\' AND A_idannonce = P_A_idannonce ORDER BY A_idannonce DESC';
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie l'id de la dernière annonce publiée par un utilisateur
    //Utilisée pour associer les photos à une annonce
    public function getLastAnnonce($mail){
        return $this->asArray()->select('A_idannonce')->where(['A_U_mail' => $mail])->orderBy('A_idannonce', 'DESC')->first();
    }

    //[SELECT] Requête qui renvoie le mail de l'utilisateur en fonction de l'id de l'anonce
    //Utilisée pour envoyer mail à uti si annonce supprimée
    public function getMailAnnonce($idannonce){
        return $this->asArray()->select('A_U_mail')->where(['A_idannonce' => $idannonce])->first();
    }

    //[SELECT] Requête qui renvoie infos des annonces pour l'administration
    //Utilisation : page "Panneau-Administration" qui liste toutes les annonces
    public function getAnnonceAdministration(){
        $query = 'SELECT A_idannonce,A_titre,A_etat,A_U_mail,DATE_FORMAT(A_date_creation, \'%d-%m-%Y\') AS "A_date_modifiee" FROM '.$this->table.' ORDER BY A_date_creation DESC, 1 DESC';
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie le nb d'annonces créées par un utilisateur pour l'administration
    //Utilisation : page "Panneau-Administration" qui liste les utilisateurs créés
    public function getNbAnnonce(){
        $query = 'SELECT A_U_mail, COUNT(A_U_mail) AS "nb_annonces" FROM '.$this->table.' a INNER JOIN '.$this->tableUti.' u ON a.A_U_mail = u.U_mail GROUP BY A_U_mail ORDER BY 2 DESC';
        return $this->simpleQuery($query);
    }

    //[SELECT] Requête qui renvoie idAnnonce à partir de l'id de la photo
    //Utilisation : suppression photo par un administrateur
    public function idPhotoToidAnnonce($idphoto){
        return $this->asArray()->select('P_A_idannonce')->from($this->tablePhoto)->where(['P_idphoto' => $idphoto])->first();
    }

    //[SELECT] Requête qui renvoie l'id si l'annonce existe
    //Utilisation : page administrateur pour vérifier que l'admin a rentré un id existant
    public function verifIDAnnonce($id){
        return $this->select('A_idannonce')->where(['A_idannonce' => $id])->first();
    }

    //[UPDATE] Requête qui modifie les informations d'une annonce
    //Utilisation : modifie une annonce
    public function updateAnnonce($id,$titre,$coutlocation,$coutcharges,$type,$superficie,$typechauffage,$modeenergie,$adresse,$ville,$region,$codepostal,$description,$etat){
        $query = 'UPDATE '.$this->table .' SET A_titre = "'.$titre.'", A_cout_loyer = "'.$coutlocation.'", A_cout_charges = "'.$coutcharges.'", A_type_chauffage = "'.$typechauffage.'", A_superficie = "'.$superficie.'", A_description = "'.$description.'", A_adresse = "'.$adresse.'", A_ville = "'.$ville.'", A_region = "'.$region.'", A_CP = "'.$codepostal.'", A_etat = "'.$etat.'", A_E_id_engie = "'.$modeenergie.'", A_T_type = "'.$type.'" where A_idannonce = "'.$id .'"';
        return $this->simpleQuery($query);
    }

    //[UPDATE] Requête qui bloque une annonce en fonction de l'utilisateur
    //Utilisation : administration, lorsqu'un utilisateur est bloqué
    public function blockUserAnnonce($mail){
        $query = 'UPDATE '.$this->table.' SET A_etat = "bloquée" WHERE A_U_mail = "'.$mail.'"';
        return $this->simpleQuery($query);
    }

    //[UPDATE] Requête qui débloque une annonce
    //Utilisation : administration, lorsqu'un utilisateur est débloqué
    public function unblockUserAnnonce($mail){
        $query = 'UPDATE '.$this->table.' SET A_etat = "publiée" WHERE A_U_mail = "'.$mail.'"';
        return $this->simpleQuery($query);
    }

    //[UPDATE] Requête qui bloque une annonce en fonction de l'id
    //Utilisation : administration
    public function blockAnnonce($id){
        $query = 'UPDATE '.$this->table.' SET A_etat = "bloquée" WHERE A_idannonce = "'.$id.'"';
        return $this->simpleQuery($query);
    }

    //[UPDATE] Requête qui débloque une annonce en fonction de l'id
    //Utilisation : administration
    public function unblockAnnonce($id){
        $query = 'UPDATE '.$this->table.' SET A_etat = "publiée" WHERE A_idannonce = "'.$id.'"';
        return $this->simpleQuery($query);
    }

    //[DELETE] Requête qui supprime les annonces d'un utilisateur
    //Utilisation : lorsqu'un utilisateur supprime son compte
    public function deleteAnnonce($mail){
        $this->where('A_U_mail',$mail);
        $this->delete();
    }

    //[DELETE] Requête qui supprime une seule annonce
    //Utilisation : lorsqu'un utilisateur clique pour supprimer une annonce
    public function deleteOneAnnonce($mail,$id){
        $this->where('A_U_mail',$mail);
        $this->where('A_idannonce', $id);
        $this->delete();
    }

    //[DELETE] Requête qui supprime une seule annonce en fonction de l'id fourni
    //Utilisation : administration
    public function deleteOneAnnonceID($id){
        $this->where('A_idannonce', $id);
        $this->delete();
    }

    //[DELETE] Requête qui supprime une ou plusieurs photos
    //Utilisation : lorsqu'un utilisateur clique pour supprimer une photo
    public function deletePhoto($id){
        $query = 'DELETE FROM t_photo WHERE P_idphoto = "'.$id.'"';
        return $this->simpleQuery($query);
    }
}