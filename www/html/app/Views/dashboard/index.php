<?php include VIEWS_PATH . '/partials/head.php'; ?>
<?php include VIEWS_PATH . '/partials/header.php'; ?>

<main>
    <h1>Dashboard</h1>
    <?php if (isset($user)): ?>
        <p>Welcome, <?php echo e($user['name']); ?>!</p>
        <p>Your email: <?php echo e($user['email']); ?></p>
    <?php else: ?>
        <p>User information not available.</p>
    <?php endif; ?>
    <p><a href="/auth/logout">Logout</a></p>
<main>

<?php include VIEWS_PATH . '/partials/footer.php'; ?>
<?php include VIEWS_PATH . '/partials/tail.php'; ?>