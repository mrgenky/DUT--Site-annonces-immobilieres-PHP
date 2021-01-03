<head>
	<link rel="stylesheet" href="../../../css/mon-compte.css">
	<link rel="stylesheet" href="../../../css/administration.css">
</head>
	<div id="aside-article" class="aside-article-gestion">
		<aside>
			<ul id="menu">
				<li id="menu-pseudo">ADMINISTRATION</li>
				<li><a href="/Panneau-Administration">Journaux <i class="fas fa-list"></i></a></li>
				<li id="menu-actif"><a href="/Gestion-site">Gestion <i class="fas fa-tools"></i></a></li>
			</ul>
		</aside>
		<section>
			<h1 class="h1-custom"><span>Gestion du site</span></h1>
				<ul><li class="liste-gestion">Gérer un utilisateur <a style="color:black; text-decoration:underline;" href="/Panneau-administration">(voir la liste des utilisateurs)</a></li></ul>
				<table id="table-gestion">
					<form action="ManageAsAdmin" method="post">
						<tr>
							<td><label for="email">Adresse mail du compte sur lequel effectuer l'action :</label></td>
							<td><input type="text" id="email" placeholder="Adresse mail" name="email" required/></td>
							<td>
								<button type="submit" name="button" value="modifier" class="pure-button pure-button-primary bouton-gestion" style="background-color:#cdad3a;">Modifier</button>
								<button type="submit" name="button" value="supprimer" class="pure-button pure-button-primary bouton-gestion" style="background-color:#c2262b;">Supprimer</button>
								<button type="submit" name="button" value="bloquer" class="pure-button pure-button-primary bouton-gestion" style="background-color:#771212;">Bloquer</button>
								<button type="submit" name="button" value="envoyermail" class="pure-button pure-button-primary bouton-gestion" style="background-color:#29ac79;">E-mail</button>
							</td>
						</tr>
					</form>
				</table>
				<hr/>
				<ul><li class="liste-gestion">Gérer une annonce <a style="color:black; text-decoration:underline;" href="/Panneau-administration">(voir la liste des annonces)</a></li></ul>
				<table id="table-gestion">
					<form action="ManageAsAdmin" method="post">
						<tr>
							<td><label for="idannonce">ID de l'annonce sur laquelle effectuer l'action :</label></td>
							<td><input type="text" id="idannonce" placeholder="ID de l'annonce" name="idannonce" required/></td>
							<td>
								<button type="submit" name="button" value="modifier" class="pure-button pure-button-primary bouton-gestion" style="background-color:#cdad3a;">Modifier</button>
								<button type="submit" name="button" value="supprimer" class="pure-button pure-button-primary bouton-gestion" style="background-color:#c2262b;">Supprimer</button>
								<button type="submit" name="button" value="bloquer" class="pure-button pure-button-primary bouton-gestion" style="background-color:#771212;">Bloquer</button>
							</td>
						</tr>
					</form>
				</table>
		</section>
	</div>