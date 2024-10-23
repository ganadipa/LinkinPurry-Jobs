<div class="main-content">
    <div class="error-container">
        <div id="errorCode" class="error-code">404</div>
        <h1 id="errorMessage" class="error-message"><?= $error ?></h1>
        <p id="errorDescription" class="error-description">
            Sorry, something went wrong on our end. We're working to fix it.
        </p>
        <a href="/" class="home-button">Go to home page</a>
        <div id="errorDetails" class="error-details">
            Status: <span id="statusCode">404</span><br>
            Message: <span id="statusMessage"><?= $error ?></span><br>
            Timestamp: <span id="errorTimestamp"><?= date('Y-m-d H:i:s') ?></span>
        </div>
    </div>
</div>