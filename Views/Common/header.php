<header>
    <a href="login" class="logo">Authentification utilisateur</a>
    <ul class="header-menu">
        <?php if ($currentUser) : ?>
            <li class="header-profile">
                <a href="profile"><?= $currentUser->firstname[0] . $currentUser->lastname[0] ?? '' ?></a>
            </li>
            <li>
                <a href="logout">Déconnecter</a>
            </li>
        <?php else : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/auth-user/register' ? 'active' : '' ?>>
                <a href="register">S'enregistrer</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/auth-user/login' ? 'active' : '' ?>>
                <a href="login">Se connecter</a>
            </li>
        <?php endif; ?>

    </ul>
</header>