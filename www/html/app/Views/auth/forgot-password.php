<?php include VIEW_PATH . '/partials/head.php'; ?>
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main>
    <h1>Forgot Password</h1>
    <?php if (isset($error)) : ?>
        <p style="color:red;"><?php echo e($error); ?></p>
    <?php endif; ?>
    <?php if (isset($success)) : ?>
        <p style="color:green;"><?php echo e($success); ?></p>
    <?php endif; ?>
    <form action="/auth/forgot-password" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <p>
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>
        </p>
        <button type="submit">Send Reset Link</button>
    </form>
    <p>Already have an account? <a href="/auth/login">Login here</a></p>
</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
<?php include VIEW_PATH . '/partials/tail.php'; ?>