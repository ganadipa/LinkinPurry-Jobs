var quill = new Quill('#reasonEditor', {
    theme: 'snow'
});

function setStatus(status) {
    document.getElementById('statusHidden').value = status;
    document.querySelectorAll('.button-accept, .button-reject').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.button-${status}`).classList.add('active');
}

document.getElementById('updateStatusForm').onsubmit = function() {
    document.getElementById('reasonHidden').value = quill.root.innerHTML;
};

document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
});