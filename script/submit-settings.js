setTimeout(() => {
    document.querySelectorAll('.js-loaded-successfully').forEach(currClass => {
        currClass.classList.add('js-hide-alert');
    });
}, 80);
