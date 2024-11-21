document.querySelector('.alert-boxes').classList.add('js-hide-alert');
setTimeout(() => {
    const currUuid = getCookie('loadedUuid');
    linsScrollLoadPresetAjax(currUuid);

    const selectBox = document.querySelector('#select-preset');
    for (let i = 0; i < selectBox.length; i++) {
        if (selectBox[i].value === currUuid) {
            selectBox[i].selected = true;
            break;
        }
    }
    setTimeout(() => {
        document.querySelector('.js-loaded-successfully').classList.add('js-hide-alert');
        document.querySelector('.alert-boxes').classList.remove('js-hide-alert');
    }, 50);
}, 50);
