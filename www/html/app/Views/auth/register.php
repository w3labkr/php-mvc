<?php include VIEW_PATH . '/partials/head.php'; ?>
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main>
    <h1>Register</h1>
    <div id="message"></div>
    <form id="registerForm">
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

<script>
$(document).ready(function(){
    $("#registerForm").on("submit", function(e){
    e.preventDefault();
    $.ajax({
        url: '/api/v1/auth/register',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.success) {
                window.location.href = '/auth/login';
            }
        },
        error: function(xhr) {
            const res = xhr.responseJSON;
            if (res.message) {
                $("#message").html('<p style="color:red;">'+res.message+'</p>');
            } else {
                $("#message").html('<p style="color:red;">An error occurred. Please try again later.</p>');
            }
        }
    });
    });
});
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
<?php include VIEW_PATH . '/partials/tail.php'; ?>