//alert(getCookie('loadedUuid'));
document.querySelector('.alert-boxes').innerHTML +=
    `<div id="setting-error-settings_updated" class="notice notice-warning settings-error lins-scroll-arrow-alert">
        <p>
            <strong>
					This setting is different from the Default Preset. Do you want to save this as a new preset?
            </strong>
        </p>
    </div>`;

document.querySelector('.save-new-def').classList.remove('js-hide-alert');