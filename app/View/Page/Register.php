<div class="container">
    <h1>Registration</h1>
    <form id="registrationForm">
        <div class="step active" id="step1">
            <div class="form-group">
                <label for="userType">Register as:</label>
                <select id="userType" name="userType">
                    <option value="jobSeeker">Job Seeker</option>
                    <option value="company">Company</option>
                </select>
            </div>
            <button type="button" onclick="nextStep(1, 2)">Next</button>
        </div>

        <div class="step" id="step2">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div id="companyDetails" style="display:none;">
                <div class="form-group">
                    <label for="location">Company Location:</label>
                    <input type="text" id="location" name="location">
                </div>
                <div class="form-group">
                    <label for="about">About Company:</label>
                    <textarea id="about" name="about" rows="3"></textarea>
                </div>
            </div>
            <div class="agreement">
                By clicking Agree & Join, you agree to the Job Portal
                <span>User Agreement</span>, <span>Privacy Policy</span>, and <span>Cookie Policy</span>.
            </div>
            <button type="button" onclick="prevStep(2, 1)">Previous</button>
            <button type="submit">Agree & Join</button>
        </div>
    </form>
    <p class="signin-link">Already on LinkinPurry? <a href="/login">Sign in</a></p>
</div>  