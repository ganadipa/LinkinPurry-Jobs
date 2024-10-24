<div class="main-content">
    <div class="error-container">
        <div id="errorCode" class="error-code">404</div>
        <h1 id="errorMessage" class="error-message"><?= $error ?></h1>
        <p id="errorDescription" class="error-description">
            Sorry, there might be a mistake in the URL or the page you're looking for is no longer available.
        </p>
        <a href="/" class="home-button">Go to home page</a>
        <div id="errorDetails" class="error-details">
            Status: <span id="statusCode">404</span><br>
            Message: <span id="statusMessage"><?= $error ?></span><br>
            Timestamp: <span id="errorTimestamp" class="server-timestamp" data-timestamp='
                <?= time() ?>
            '>?></span>
        </div>
    </div>
</div>