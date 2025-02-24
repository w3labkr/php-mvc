<?php include VIEW_PATH . '/partials/head.php'; ?>
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main>
    <h1>Forgot Password</h1>
    <div id="message"></div>
    <form id="forgotPasswordForm">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <p>
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>
        </p>
        <button type="submit">Send Reset Link</button>
    </form>
    <p>Already have an account? <a href="/auth/login">Login here</a></p>
</main>

<script>
$(document).ready(function() {
    $("#forgotPasswordForm").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/v1/auth/forgot-password',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $("#message").html('<p>' + res.message + '</p>');
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
