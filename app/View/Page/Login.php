<div class="sign-in-card">
    <h1>Sign in</h1>

    <form id='login-form'>
        <div class="input-group email-container">
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">Email</label>
        </div>

        <div class="input-group password-container">
            <input type="password" id="password" name="password" placeholder=" " required>
            <label for="password">Password</label>
            <span class="show-password">Show</span>
        </div>
        <div class='error-message text-center response-container hidden' id='response-container'>
            <p>Invalid email or password</p>
        </div>
        <button type="submit" class="sign-in-button">Sign in</button>
    </form>
</div>
<p class="join-now ">New to LinkinPurry? <a href="/register">Join now</a></p>