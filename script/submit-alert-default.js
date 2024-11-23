//alert(getCookie('loadedUuid'));
document.querySelector('.alert-boxes').innerHTML +=
    `<div class="notice notice-warning settings-error lins-scroll-arrow-alert lins-scroll-new-def-alert">
        <p>
            <strong>
					This setting is different from the <b>'Default Preset'</b>. Do you want to save this as a new preset?
            </strong>
        </p>
    </div>`;

document.querySelector('.save-new-def').classList.remove('js-hide-alert');