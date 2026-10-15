<form action="index.php?page=logout" method="post" class="logout-form">
    <input type="hidden" name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit" class="logout-button">Cerrar sesión</button>
</form>
