<?php include VIEWS_PATH . '/partials/head.php'; ?>
<?php include VIEWS_PATH . '/partials/header.php'; ?>

<main>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?php echo e($error); ?></p>
    <?php endif; ?>
    <form action="/auth/login" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <p>
            <label>Email:</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Password:</label>
            <input type="password" name="password" required>
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
    </form>
    <p>Don't have an account? <a href="/auth/register">Register here</a></p>
</main>

<?php include VIEWS_PATH . '/partials/footer.php'; ?>
<?php include VIEWS_PATH . '/partials/tail.php'; ?>