<?php include VIEWS_PATH . '/partials/head.php'; ?>
<?php include VIEWS_PATH . '/partials/header.php'; ?>

<main>
    <h1>Register</h1>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?php echo e($error); ?></p>
    <?php endif; ?>
    <form action="/auth/register" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <p>
            <label>Name:</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Password:</label>
            <input type="password" name="password" required>
        </p>
        <p>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
        </p>
        <p>
            <button type="submit">Register</button>
        </p>
    </form>
    <p>Already have an account? <a href="/auth/login">Login here</a></p>
<main>

<?php include VIEWS_PATH . '/partials/footer.php'; ?>
<?php include VIEWS_PATH . '/partials/tail.php'; ?>