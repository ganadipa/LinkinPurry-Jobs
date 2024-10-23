document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
});

const statusText = document.querySelector('#statusIndicator span').textContent;

if (statusText === 'Waiting') {
    var quill = new Quill('#reasonEditor', {
        theme: 'snow'
    });

    document.getElementById('acceptButton').addEventListener('click', function() {
        document.getElementById('statusHidden').value = 'accepted';
        console.log(document.getElementById('statusHidden').value);
    
        var reason = document.getElementById('reasonEditor').children[0].innerHTML;
        document.getElementById('reasonHidden').value = reason;
        console.log(document.getElementById('reasonHidden').value);
    
        const path = window.location.pathname;
        const jobId = path.split('/')[3];
        const applicationId = path.split('/')[5];
    
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/company/job/' + jobId + '/application/' + applicationId + '/accept', true);
        
        const formData = new FormData();
        formData.append('status', document.getElementById('statusHidden').value);
        formData.append('reason', document.getElementById('reasonHidden').value);

        xhr.send(formData);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = '/company/job/' + jobId + '/application/' + applicationId;
            }
        }
    });
    
    document.getElementById('rejectButton').addEventListener('click', function() {
        document.getElementById('statusHidden').value = 'rejected';
        console.log(document.getElementById('statusHidden').value);
    
        var reason = document.getElementById('reasonEditor').children[0].innerHTML;
        document.getElementById('reasonHidden').value = reason;
        console.log(document.getElementById('reasonHidden').value);

        const path = window.location.pathname;
        const jobId = path.split('/')[3];
        const applicationId = path.split('/')[5];
    
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/company/job/' + jobId + '/application/' + applicationId + '/reject', true);
        
        const formData = new FormData();
        formData.append('status', document.getElementById('statusHidden').value);
        formData.append('reason', document.getElementById('reasonHidden').value);

        xhr.send(formData);
    
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = '/company/job/' + jobId + '/application/' + applicationId;
            }
        }
    });
}

