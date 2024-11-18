let modalActive = false;
let firstFocusableElement = null;
let focusableContent = null;
let lastFocusableElement = null;
const nameModal = document.querySelector('.name-modal');
const removeModal = document.querySelector('.remove-modal');
const confirmRemoveModal = document.querySelector('.confirm-remove-modal');
const editModal = document.querySelector('.edit-name-modal');
let confirmRemoveModalActive = false;
let removeSelection = null;
let removeId = null;

function linsScrollTopEditAlert() {
    modalActive = true;
    editModal.classList.add('js-modal-active');
    linsScrollCatchFocus(editModal);
}

function linsScrollRemovePreset() {
    const removeSelectBox = document.querySelector('#remove-preset');
    removeId = removeSelectBox.value;
    for (let i = 0; i < removeSelectBox.length; i++) {
        if (removeSelectBox[i].value === removeId) {
            removeSelection = removeSelectBox[i].innerText;
        }
    }
    document.querySelector('.current-preset-modal > span').innerText = removeSelection;
    confirmRemoveModal.classList.add('js-modal-active');
    removeModal.classList.remove('js-modal-active');
    linsScrollCatchFocus(confirmRemoveModal);
    confirmRemoveModalActive = true;
}

function linsScrollRemoveAlert() {
    modalActive = true;
    removeModal.classList.add('js-modal-active');
    const currPreset = document.querySelector('span.preset-name').textContent;
    document.querySelector('.current-preset-modal span').innerHTML = currPreset;
    linsScrollCatchFocus(removeModal);
}

function linsScrollTopShowModal() {
    modalActive = true;
    nameModal.classList.add('js-modal-active');
    linsScrollCatchFocus(nameModal);
}

function linsScrollTopRemoveConfirmCloseModal() {
    confirmRemoveModal.classList.remove('js-modal-active');
    removeModal.classList.add('js-modal-active');
    linsScrollCatchFocus(removeModal);
    confirmRemoveModalActive = false;
}

function linsScrollTopCloseModal() {
    modalActive = false;
    nameModal.classList.remove('js-modal-active');
    removeModal.classList.remove('js-modal-active');
    confirmRemoveModal.classList.remove('js-modal-active');
    editModal.classList.remove('js-modal-active');
    document.body.classList.remove('js-modal-focus');
    linsScrollRemoveFocus();
}

function linsScrollRemoveFocus() {
    document.body.classList.remove('js-modal-focus');
    modalActive = false;
    firstFocusableElement = null;
    focusableContent = null;
    lastFocusableElement = null;
}

function keyLinsScrollRemoveFocus(event) {
    if (event.key === 'Escape') {
        linsScrollTopCloseModal();
    }
}

function linsScrollCatchFocus(modal) {
    document.body.classList.add('js-modal-focus');
    const focusableElements =
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    firstFocusableElement = modal.querySelectorAll(focusableElements);
    focusableContent = modal.querySelectorAll(focusableElements);
    lastFocusableElement = focusableContent[focusableContent.length - 1];
    firstFocusableElement[0].focus();
    //console.log(firstFocusableElement);
}

document.addEventListener('keydown', (event) => {
    if (modalActive && event.key === 'Tab') {
        //console.log(firstFocusableElement);
        //console.log(lastFocusableElement);
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

function keyLinsScrollCatchFocus(event) {
    if (event.key === 'Enter') {
        linsScrollCatchFocus();
    }
}

document.addEventListener('keydown', (event) => {
    if (modalActive && event.key === 'Escape') {
        const isNotCombinedKey = !(event.ctrlKey || event.altKey || event.shiftKey);
        if (isNotCombinedKey) {
            if (confirmRemoveModalActive) {
                linsScrollTopRemoveConfirmCloseModal();
            }
            else {
                linsScrollTopCloseModal();
            }
        }
    }
});

const closeBtn = document.querySelectorAll('.js-close-modal-btn');
closeBtn.forEach(currBtn => {
    currBtn.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            linsScrollTopCloseModal();
        }
    });
});

const closeBtnConfirm = document.querySelector('.js-close-modal-btn-confirm');
closeBtnConfirm.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        linsScrollTopRemoveConfirmCloseModal();
    }
});

document.querySelector('#lins-scroll-preset-name').addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        linsScrollTopSavePreset();
    }
});