<?php
/***************************************************************************
 *                              sql.lib.php
 *                            -------------------
 *   begin			: Mercredi, Dec 27, 2006
 *   copyright		: (C) 2006 Web-Modules.net
 *   email			: jiem78@hotmail.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   UTILISATION
 *   
 *   /!\ la connexion à la BDD est gérée AUTOMATIQUEMENT ! Seule la déconnexion est à gérée (pour rappel, PHP ferme les connexion automatiquement si elles n'ont pas été fermée explicitement). De ce fait la class ne se connecte à la BDD que lors du premier appelle de la méthode escape_str() ou query().
 *   
 *   $mysql = new Mysql('BDD_SERVEUR', 'BDD_USER', 'BDD_PASSWORD', 'BDD_NAME', 'EMAIL_ADMIN', (SQL_BENCH + SQL_NO_BUG_TRACK + SQL_ERROR_FULL)); // les constantes sont crées par le fichier de class :), les paramètres de connexion sont à remplir correctement // instance de class
 *   
 *   $mysql->query('SELECT ...'); // requête
 *   $var_protege_des_injections_sql = $mysql->escape_str($var_non_protegee); // fonction appelant mysql_real_escape_string(), passer par cette méthode IMPERATIVEMENT pour protéger une variable avec la fonction mysql_real_escape_string() !!!
 *   
 *   $stats = $mysql->info(); // retourne : array( 'requetes' => (int)'nombre de requêtes', 'timestamp' => (float)'temps d'execution');
 *   
 *   $mysql->close(); // deconnexion de la BDD, la class se reconnecte toute seule si une requete est générée après une déconnexion
 *   $mysql->destroy(); // déconnection si ce n'est déjà fait, destruction des variables
 *   
 ***************************************************************************/

/**
 * CONSTANTES
 */

// niveau d'affichage des erreurs
define('SQL_ERROR_NONE', 1);
define('SQL_ERROR_TEXT', 2);
define('SQL_ERROR_JS', 4);
define('SQL_ERROR_FULL', 8);

// benchmark
define('SQL_BENCH', 16);
define('SQL_NO_BENCH', 32);

// track des bugs
define('SQL_BUG_TRACKING', 64);
define('SQL_NO_BUG_TRACK', 128);

// aucun email
define('SQL_NO_MAIL', -1);

// PARAMETRES par défaut
define('SQL_DEFAUT_PARAMS', SQL_BENCH + SQL_BUG_TRACKING  + SQL_ERROR_FULL);


class sql
{
	// nombre de requêtes
	var $nb_requetes = 0;
	
	// connection au serveur sql
	var $connecte = FALSE;
	
	// dernier id inséré
	var $lastid;
	
	// infos de connection
	var $logins = array();
	
	// Bench // calcul du temps des actions Mysql
	var $bench;
	var $microtime = 0;
	
	// e-mail où on envoie les rapports d'erreurs
	var $email;
	
	// forcer l'affichage les erreurs SQL
	var $bug_tracker;
	
	// type d'affichage des erreurs
	var $lvl_error;
	
	// sélection d'une base de données
	var $db;
	
	/*
	* Constructeur. Mise en place des variables infos de connection
	*/
	function sql($serveur, $utilisateur, $mot_de_passe, $base_de_donnees, $email = SQL_NO_MAIL, $parameters = SQL_DEFAUT_PARAMS)
	{
		// logins de la BDD
		$this->logins['serveur'] = $serveur;
		$this->logins['utilisateur'] = $utilisateur;
		$this->logins['mot_de_passe'] = $mot_de_passe;
		$this->logins['base_de_donnees'] = $base_de_donnees;
		
		// email pour envoie des message d'erreur
		$this->email = $email;
		
		// activer le mode benchmark de la BDD
		$this->bench = ($parameters & SQL_BENCH) ? SQL_BENCH : SQL_NO_BENCH;
		
		// niveau d'affichage des erreurs
		if($parameters & SQL_ERROR_NONE) $this->lvl_error = SQL_ERROR_NONE;
		elseif($parameters & SQL_ERROR_TEXT) $this->lvl_error = SQL_ERROR_TEXT;
		elseif($parameters & SQL_ERROR_JS) $this->lvl_error = SQL_ERROR_JS;
		else $this->lvl_error = SQL_ERROR_FULL;
		
		// tracker les bugs
		if($parameters & SQL_BUG_TRACKING) $this->bug_tracker = SQL_BUG_TRACKING;
		else $this->bug_tracker = SQL_NO_BUG_TRACK;
		
		// mémoire
		unset($serveur);
		unset($utilisateur);
		unset($mot_de_passe);
		unset($base_de_donnees);
		unset($email);
	}
	
	/*
	*
	*/
	function	set_charset($charset)
	{
		mysql_set_charset($charset);
		return true;
	}
	
	/*
	* Destruction des données de l'instance
	*/
	function destroy()
	{
		$this->close();
		
		$this->logins = array();
		$this->nb_requetes = NULL;
		$this->connecte = NULL;
		$this->lastid = NULL;
		$this->microtime = NULL;
		$this->email = NULL;
		$this->bench = NULL;
		$this->bug_tracker = NULL;
		$this->lvl_error = NULL;
		$this->db = NULL;
		
		// mémoire
		unset($this->logins);
		unset($this->nb_requetes);
		unset($this->connecte);
		unset($this->lastid);
		unset($this->microtime);
		unset($this->email);
		unset($this->bench);
		unset($this->bug_tracker);
		unset($this->lvl_error);
		unset($this->db);
	}
	
	/*
	* Connection au serveur de base de données
	*/
	function connect()
	{
		if($this->connecte === FALSE)
		{
			// connexion
			$this->connecte = @mysql_connect($this->logins['serveur'], $this->logins['utilisateur'], $this->logins['mot_de_passe']) or die($this->erreur('Connexion au serveur de bases de données <q>' .$this->logins['serveur']. '</q> avec le pseudo <q>' .$this->logins['utilisateur']. '</q>'));
			@mysql_select_db($this->logins['base_de_donnees'], $this->connecte) or die($this->erreur('Connexion à la base de données <q>' .$this->logins['base_de_donnees']. '</q>'));
			
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	* Sélection d'une base de données
	*/
	function change_db($db)
	{
		if ($this->connecte !== FALSE)
		{
			$this->db = mysql_select_db($db,$this->connecte);
			if ($this->db === FALSE)
			{
				mysql_close($this->connecte);
				$this->connecte = FALSE;
				die('Impossible de sélectionner une base de données.');
			}
			return TRUE;
		}
		return FALSE;
	}
	
	
	/*
	* Execution d'une requête
	*/
	function query($sql, $lign = 0, $no_error = FALSE)
	{
		// début du chrono mysql
		if($this->bench === SQL_BENCH)
			$microtime_1 = microtime();
		
		// connexion au serveur
		if($this->connecte === FALSE)
			$this->connect();
		
		// requête
		if($no_error === FALSE)
			$requete = mysql_query($sql, $this->connecte) or die($this->erreur($sql, $lign));
		else
			$requete = mysql_query($sql, $this->connecte);
		
		$this->lastid = mysql_insert_id($this->connecte);
		
		if($this->bench === SQL_BENCH)
		{
			// fin du chrono mysql
			$microtime_2 = microtime();
			$this->microtime += (array_sum(explode(' ', $microtime_2)) - array_sum(explode(' ', $microtime_1))) * 1000;
			
			// incrémentation du nombre de requêtes
			$this->nb_requetes++;
			
			// mémoire
			unset($microtime_1);
			unset($microtime_2);
		}
		// mémoire
		unset($sql);
		
		// on retuourne le résultat
		return $requete;
	}
	
	/*
	* Construction d'une requête avec une fonction à partir de tableaux
	* Accepte seulement : SELECT, UPDATE, INSERT
	*/
	function build_query($query, $row, $from, $where = '', $limit = '')
	{
		foreach($row as $key => $value)
		{
			$row[$key] = $this->only_escape($row[$key]);
		}
		
		if ($query == 'SELECT')
		{
			if (!empty($row))
			{
				if (is_array($row))
				{
					$i = 1;
					$fields = $row[0];
					while (isset($row[$i]))
					{
						$fields .= ','.$row[$i];
						$i++;
					}
					$sql = $query . ' ' . $fields;
				}
				else $sql = $query . ' ' . $row;
			}
			
			if (!empty($from))
			{
				$sql .= ' ';
				if (is_array($from))
				{
					$i = 0;
					foreach ($from as $key => $value)
					{
						if ($i != 0)
						{
							$sql .= ' LEFT JOIN ' . $key . ' ON ' . $value;
						}
						else
						{
							$sql .= 'FROM ' . $key;
							$i++;
						}
					}
					$sql .= 'FROM ' . $from[0];
				}
				else $sql .= 'FROM ' . $from;
			}
			else return false;
			
			if (!empty($where))
			{
				$sql .= ' ';
				if (is_array($where))
				{
					break;
					/*
					Inutilité d'un tableau dans la circonstance d'un where
					$sql .= 'WHERE ';
					$i = 0;
					while (isset($where[$i]))
					{
						$sql .= $where[$i] ''
						$i++;
					}
					*/
				}
				else $sql .=' WHERE ' . $where;
			}
			
			if (!empty($limit))
			{
				if (is_array($limit))
				{
					if ($limit['total'] > 0 AND isset($limit['offset']))
					{
						$sql .= ' LIMIT ' . $limit['offset'] . ',' . $limit['total'];
					}
				}
			}
		}
		elseif ($query == 'UPDATE')
		{
			if (!empty($from))
			{
				if (is_array($from))
				{
					$i = 1;
					while (isset($from[$i]))
					{
						$tables .= ',' . $from[$i];
						$i++;
					}
				}
				else $tables = $from;
				
				$sql = $query . ' ' . $tables . ' ';
				if (is_array($row))
				{
					$i = 0;
					$count_rows = count($row[0]);
					while ($i < $count_rows)
					{
						list(, $field) = each($row[0]);
						list(, $value) = each($row[1]);
						$fields[] = '`' . $field . '` = \'' . $value . '\'';
						$i++;
					}
					$sql .= 'SET ' . implode(',', $fields);
				}
				else $sql .= 'SET ' . $row;
				unset($fields);
			}
			else return false;
			
			if (!empty($where))
			{
				$sql .= ' WHERE ';
				if (is_array($where))
				{
					foreach ($where as $key => $value)
					{
						$fields[] = '`' . $key . '` = \'' . $value . '\'';
					}
					$sql .= implode(' AND ',$fields);
				}
				else $sql .= $where;
				unset($fields);
			}
			
			if (!empty($limit))
			{
				if (is_array($limit))
				{
					if ($limit['total'] > 0 AND isset($limit['offset']))
					{
						$sql .= ' LIMIT ' . $limit['offset'] . ',' . $limit['total'];
					}
				}
			}
		}
		elseif ($query == 'INSERT')
		{
			if (!empty($from))
			{
				if (is_array($from))
				{
					$table = $from[0];
				}
				else $table = $from;
			
				$sql = 'INSERT INTO ' . $table . ' ';
				
				if (!empty($row) AND is_array($row))
				{
					// Récupèration des entrées
					$i = 0;
					while (isset($row[$i]))
					{
						foreach ($row[$i] as $value)
						{
							$data[$i][] = $value;
						}
						$i++;
					}
					// Les colonnes sont prises, on la supprime
					$fields = '(`' . implode('`,`', $data[0]) . '`)';
					unset($data[0]);
					
					// Mise à la chaine des valeurs
					$z = 1;
					while ($z < $i)
					{
						$data[$z] = implode('","', $data[$z]);
						$z++;
					}
					// Mise à la chaine des entrées
					$values = implode('"),("', $data);
					
					$sql .= $fields . ' VALUES ("' . $values . '")';
				}
				else return false;
			}
			else return false;
		}
		return $sql;
	}
	
	/*
	* Transformation d'une requête en tableau
	*/
	function fetch_array($sql)
	{
		$row = mysql_fetch_array($sql);
		return $row;
	}
	
	/*
	* Renvoit une ligne d'une requête
	*/
	function result($sql,$line)
	{
		$row = mysql_result($sql,$line);
		return $row;
	}

	/*
	* Renvoit une requête en tableau associatif
	*/
	function fetch_assoc($sql)
	{
		$row = mysql_fetch_assoc($sql);
		return $row;
	}
	
	/*
	* Libère la mémoire
	*/
	function free_result($sql)
	{
		$row = mysql_free_result($sql);
		return $row;
	}
	
	/*
	* Compte le nombre de lignes affectées par un SELECT
	*/
	function num_rows($result)
	{
		return mysql_num_rows($result);
	}
	
	/*
	* Compte le nombre de lignes affectées par un UPDATE, DELETE, INSERT
	*/
	function affected_rows()
	{
		return mysql_affected_rows();
	}
	
	/*
	* Protection de la chaine
	*/
	function only_escape($string)
	{
		if (is_array($string))
		{
			foreach ($string as $key => $value)
			{
				$string[$key] = mysql_real_escape_string($value);
			}
		}
		else
		{
			$string = mysql_real_escape_string($string);
		}
		return $string;
	}
	
	private function escape_($string)
	{
		$string = mysql_real_escape_string($string);
		//$string = htmlentities($string);
		$string = urldecode($string);
		//$string = str_replace($special_char,$code_ascii,$string);
		$string = htmlspecialchars($string);
		return trim($string);
	}
	
	function escape($chaine)
	{
		$special_char = array(
			);
		$code_ascii = array(
			);
		// Ne peut protéger que des tableaux double-dimensionnel
		if(is_array($chaine))
		{
			foreach($chaine as $key => $value)
			{
				if (is_array($value))
				{
					$chaine[$key] = $this->escape($value);
				}
				else
				{
					$chaine[$key] = $this->escape_($value);
				}
			}
			return $chaine;
		}
		
		// connexion au serveur
		if($this->connecte === FALSE) $this->connect();
		
		// on vérifie si les put*** de magic_quotes sont actives ^^ (vous comprenez mon point de vue je suppose :P)
		if (get_magic_quotes_gpc())
		{
			$chaine = stripslashes($chaine);
		}
		// émulation de la fonction mysql_real_escape_string()
		return $this->escape_($chaine);
	}
	
	/*
	* Fermeture de la connexion
	*/
	function close()
	{
		if($this->connecte !== FALSE)
		{
			mysql_close();
			$this->connecte = FALSE;
			
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	* Récupération des statistiques
	*/
	function info()
	{
		// le mode benchmark n'est *PAS* activé
		if($this->bench === SQL_NO_BENCH)
		{
			return FALSE;
		}
		
		return array(
			'requetes' => $this->nb_requetes,
			'timestamp' => $this->microtime
		);
	}
	
	/*
	* Erreur dans une requête ou dans la connection au serveur
	*/
	function erreur($infos, $lign = 0)
	{
		global $user;
		echo '<pre>' . mysql_error() . '<br />';
		echo $infos;
		echo '</pre>';
		exit;
		//exit(var_dump(debug_backtrace()));
		// message d'erreur à envoyer par e-mail
		$message_erreur = '<h1>Une erreur mysql est survenue</h1>
							Erreur survenue : <strong>' .mysql_error(). '</strong><br />
							Erreur n°' .mysql_errno(). '<br />
							Page où l\'erreur à eut lieu : "' .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']. '"<br />
							Ligne ' . (($lign != 0) ? $lign : 'inconnue') . '<br />
							Heure de l\'erreur : "' .date('d\/m\/Y \- H\hi'). '"<br />
							Requête Mysql : <blockquote><pre>' .$infos. '</pre></blockquote>' . '<br />
							<br />
							Autres infos :<br />
							$_SESSION : <pre>'.print_r($_SESSION, true). '</pre><br />
							$_POST : <pre>'.print_r($_POST, true). '</pre><br />
							$_GET : <pre>'.print_r($_GET, true). '</pre><br />
							$_COOKIE : <pre>'.print_r($_COOKIE, true). '</pre>';
							
		$sql = 'INSERT INTO ' . CONTACT_TICKETS_TABLE . ' (title,subject,autor,date) VALUES
			("Erreur MySQL",5,' . (int) $user->data['id']. ',' . time() . ')';
		
		if ($this->query($sql))
		{
			$sql = 'INSERT INTO ' . CONTACT_MESSAGES_TABLE .  ' (autor,date,text,ticket) VALUES
				(' . (int) $user->data['id'] . ',' . time() . ',"' . mysql_real_escape_string($message_erreur) . '","' . mysql_insert_id() . '")';	
			if ($this->query($sql)) $answer = '<u><b>Le webmaster vient d\'être prevénu de cette erreur.</b></u><br />';
			else $answer = '<u><b>Le webmaster n\'a pas pu être prevenu.</b></u><br />';
		}
		die($message_erreur);
		
		if($this->email != SQL_NO_MAIL AND $this->bug_tracker !== SQL_NO_BUG_TRACK)
		{
			$headers  = 'MIME-Version: 1.0' ."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' ."\r\n";
			$headers .= 'To: WebMaster <' .$this->email. '>' . "\r\n";
			$headers .= 'From: Erreur Base De Données <noreply@erreur_bdd.com>';
			
			if(@mail($this->email,'WebMaster :: bug Mysql (automatique)', $message_erreur, $headers) === FALSE)
			{
				echo '<div style="color: red">L\'e-mail n\'a pu être envoyé !</div>';
			}
			
			// mémoire
			unset($headers);
		}
		
		// mémoire
		unset($infos);
		
		// erreur non affichée si 'lvl_error' est a SQL_ERROR_NONE
		if($this->lvl_error === SQL_ERROR_NONE)
		{
			// mémoire
			unset($message_erreur);
			$this->destroy();
			
			die();
		}
		
		// erreur affichage en texte court si 'lvl_error' est a SQL_ERROR_TEXT
		if($this->lvl_error === SQL_ERROR_TEXT)
		{
			echo 'Le serveur est surchargé, le script ne peut pas continuer.';
			
			// mémoire
			unset($message_erreur);
			$this->destroy();
			
			die();
		}
		
		// erreur affichage en texte court si 'lvl_error' est a SQL_ERROR_JS
		if($this->lvl_error === SQL_ERROR_JS)
		{
			echo 'document.write(\'Le serveur est surchargé, le script ne peut pas continuer.\');';
			
			// mémoire
			unset($message_erreur);
			$this->destroy();
			
			die();
		}
		
		// affichage
		echo '<h1>Une erreur Mysql est survenue !</h1>
			<p>
				' . $answer. '
				Tentez de rafraichir la page mais si l\'erreur persiste, informez-en le webmaster via le formulaire de contact, merci.
			</p>';
		
		// affichage du message d'erreur
		if($this->bug_tracker === SQL_BUG_TRACKING OR $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		{
			echo $message_erreur;
		}
		
		
		// mémoire
		unset($message_erreur);
		$this->destroy();
		
		// on termine la page
		die();
	}
}
?>