let modalActive = false;
let firstFocusableElement = null;
let focusableContent = null;
let lastFocusableElement = null;
const nameModal = document.querySelector('.name-modal');

function linsScrollTopShowModal() {
    modalActive = true;
    nameModal.classList.add('js-modal-active');
    catchFocus();
}

function linsScrollTopCloseModal() {
    modalActive = false;
    nameModal.classList.remove('js-modal-active');
    document.body.classList.remove('js-modal-focus');
    removeFocus();
}

function removeFocus() {
    document.body.classList.remove('js-modal-focus');
    modalActive = false;
    firstFocusableElement = null;
    focusableContent = null;
    lastFocusableElement = null;
}

function keyRemoveFocus(event) {
    if (event.key === 'Escape') {
        linsScrollTopCloseModal();
    }
}

function catchFocus() {
    document.body.classList.add('js-modal-focus');
    const focusableElements =
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    const modal = document.querySelector('.name-modal'); // select the modal by it's id
    firstFocusableElement = modal.querySelectorAll(focusableElements); // get first element to be focused inside modal
    focusableContent = modal.querySelectorAll(focusableElements);
    lastFocusableElement = focusableContent[focusableContent.length - 1]; // get last element to be focused inside modal
    firstFocusableElement[0].focus();
}

function keyCatchFocus(event) {
    if (event.key === 'Enter') {
        catchFocus();
    }
}

document.addEventListener('keydown', (event) => {
    if (modalActive && event.key === 'Escape') {
        const isNotCombinedKey = !(event.ctrlKey || event.altKey || event.shiftKey);
        if (isNotCombinedKey) {
            linsScrollTopCloseModal();
        }
    }
});

document.addEventListener('keydown', function (event) {
    if (modalActive && event.key === 'Tab') {
        console.log(firstFocusableElement);
        console.log(lastFocusableElement);
        if (event.shiftKey) { // Shift + Tab
            if (document.activeElement === firstFocusableElement[0]) {
                lastFocusableElement.focus();
                event.preventDefault();
            }
        } else {
            if (document.activeElement === lastFocusableElement) {
                firstFocusableElement[0].focus();
                event.preventDefault();
            }
        }
    }
});


const closeBtn = document.querySelector('.js-close-modal-btn');

// add a click event listener to the div
closeBtn.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        linsScrollTopCloseModal();
    }
    // specify the action to take when the div is clicked
});