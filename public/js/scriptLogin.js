function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onload = function() {
    if (document.getElementById('errorModal')) {
        document.getElementById('errorModal').style.display = 'block';
    }
    if (document.getElementById('successModal')) {
        document.getElementById('successModal').style.display = 'block';
    }
}