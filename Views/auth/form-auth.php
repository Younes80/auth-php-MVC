<div class="block form-container">
	<?php if ($_SERVER['REQUEST_URI'] === '/auth-user/register') : ?>
		<div class="form-img">
			<img class="img-fluid" src="./assets/img/signup.jpg" alt="signup">
		</div>
	<?php else : ?>
		<div class="form-img">
			<img class="img-fluid" src="./assets/img/signin.jpg" alt="signup">
		</div>
	<?php endif; ?>
	<form class="p-30" action="" method="POST">
		<?php if ($_SERVER['REQUEST_URI'] === '/auth-user/register') : ?>
			<div class="form-info-user">
				<div class="form-control">
					<!-- <label for="firstname">Prénom</label> -->
					<input type="text" name="firstname" id="firstname" placeholder="Votre prénom" value="<?= $firstname ?? "" ?>">
					<?php if ($errors['firstname']) : ?>
						<p class="text-danger"><?= $errors['firstname'] ?></p>
					<?php endif; ?>
				</div>
				<div class="form-control">
					<!-- <label for="lastname">Nom</label> -->
					<input type="text" name="lastname" id="lastname" placeholder="Votre nom" value="<?= $lastname ?? "" ?>">
					<?php if ($errors['lastname']) : ?>
						<p class="text-danger"><?= $errors['lastname'] ?></p>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-control">
			<!-- <label for="email">Email</label> -->
			<input type="text" name="email" id="email" placeholder="Votre email" value="<?= $email ?? "" ?>">
			<?php if ($errors['email']) : ?>
				<p class="text-danger"><?= $errors['email'] ?></p>
			<?php endif; ?>
		</div>
		<div class="form-control">
			<!-- <label for="password">Mot de passe</label> -->
			<input type="password" name="password" id="password" placeholder="Votre mot de passe" value="<?= $password ?? "" ?>">
			<?php if ($errors['password']) : ?>
				<p class="text-danger"><?= $errors['password'] ?></p>
			<?php endif; ?>
		</div>
		<?php if ($errors['general']) : ?>
			<p class="text-danger"><?= $errors['general'] ?></p>
		<?php endif; ?>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit"><?= $_SERVER['REQUEST_URI'] === '/auth-user/register' ? "S'enregistrer" : "Se connecter" ?></button>
		</div>
		<div class="form-actions">
			<?php if ($_SERVER['REQUEST_URI'] === '/auth-user/register') : ?>
				<a href="login">Vous avez déjà un compte ?</a>
			<?php else : ?>
				<a href="register">Pas encore de compte ?</a>
			<?php endif; ?>
		</div>
	</form>
</div>