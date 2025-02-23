<?php include VIEW_PATH . '/partials/head.php'; ?>
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?php echo e($error); ?></p>
    <?php endif; ?>
    <form action="/auth/login" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="remember">Remember:</label>
            <input type="checkbox" name="remember" id="remember" value="1">
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
    </form>
    <p><a href="/auth/forgot-password">Forgot your password?</a></p>
    <p>Don't have an account? <a href="/auth/register">Register here</a></p>
</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
<?php include VIEW_PATH . '/partials/tail.php'; ?>