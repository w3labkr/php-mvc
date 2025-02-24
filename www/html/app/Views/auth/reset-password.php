<?php include VIEW_PATH . '/partials/head.php'; ?>
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main>
    <h1>Reset Password</h1>
    <div id="message"></div>
    <form id="resetPasswordForm">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <p>
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </p>
        <p>
            <button type="submit">Reset Password</button>
        </p>
    </form>
</main>

<script>
$(document).ready(function() {
    $("#resetPasswordForm").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/v1/auth/reset-password',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $("#message").html('<p style="color:green;">' + res.message + '</p>');
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                if (res.message) {
                    $("#message").html('<p style="color:red;">' + res.message + '</p>');
                } else {
                    $("#message").html(
                        '<p style="color:red;">An error occurred. Please try again later.</p>'
                    );
                }
            }
        });
    });
});
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
<?php include VIEW_PATH . '/partials/tail.php'; ?>
