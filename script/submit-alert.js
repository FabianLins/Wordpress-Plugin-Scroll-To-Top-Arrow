//alert(getCookie('loadedUuid'));
document.querySelector('.alert-boxes').innerHTML +=
    `<div id="setting-error-settings_updated" class="notice notice-warning settings-error lins-scroll-arrow-alert">
        <p>
            <strong>
                Setting was saved successfully. However, your settings are different from 'latest preset'. Do you want to save the unsaved changes to 'the latest preset'?
            </strong>
        </p>
    </div>`;

document.querySelector('.save-preset-changes').classList.remove('js-hide-alert');
document.querySelector('.update-save-preset-changes').addEventListener('click', () => {
    document.querySelector('.save-preset-changes').classList.add('js-hide-alert');
});