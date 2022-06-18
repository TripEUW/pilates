
function showToast(type, msg) {
    var types = ['success', 'info', 'warning', 'error'];
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: false,
        onclick: null,
        timeOut: 1500
    };
    var $toast = toastr[types[type]](msg, ''); // Wire up an event handler to a button in the toast, if it exists
    if (typeof $toast === 'undefined') {
        return;
    }
}

function showOverlay(){}