// Function to get URL parameters
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Error messages mapping
const errorMessages = {
    400: { title: 'Bad Request', description: 'The request could not be understood by the server.' },
    401: { title: 'Unauthorized', description: 'Authentication is required to access this resource.' },
    403: { title: 'Forbidden', description: 'You don\'t have permission to access this resource.' },
    404: { title: 'Page Not Found', description: 'The page you\'re looking for isn\'t available. The link may be broken, or the page may have been removed.' },
    405: { title: 'Method Not Allowed', description: 'The requested method is not supported for this resource.' },
    408: { title: 'Request Timeout', description: 'The server timed out waiting for the request.' },
    500: { title: 'Internal Server Error', description: 'Sorry, something went wrong on our end. We\'re working to fix it.' },
    502: { title: 'Bad Gateway', description: 'The server received an invalid response from the upstream server.' },
    503: { title: 'Service Unavailable', description: 'The server is temporarily unable to handle the request.' },
    504: { title: 'Gateway Timeout', description: 'The upstream server failed to respond in time.' }
};

// Function to update error content
function updateErrorContent() {
    const code = getQueryParam('code') || '500';
    const message = getQueryParam('message') || errorMessages[code]?.title || 'Unknown Error';
    const path = getQueryParam('path') || window.location.pathname;
    
    const error = errorMessages[code] || {
        title: message,
        description: 'An unexpected error occurred.'
    };

    document.title = `${code} - ${error.title}`;
    document.getElementById('errorCode').textContent = code;
    document.getElementById('errorMessage').textContent = error.title;
    document.getElementById('errorDescription').textContent = error.description;
    
    // Update error details
    document.getElementById('statusCode').textContent = code;
    document.getElementById('statusMessage').textContent = message;
    document.getElementById('requestPath').textContent = path;
    document.getElementById('errorTimestamp').textContent = new Date().toLocaleString();
}

// Initialize the page
updateErrorContent();