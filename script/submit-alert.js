//console.log(getCookie('loadedUuid'));
//lins_scroll_loaded_preset

jQuery.ajax({
    type: 'post',
    url: `${window.location.origin}/wp-admin/admin-ajax.php`,
    dataType: 'json',
    data: {
        action: 'get_loaded_uuid',
    },
    complete: function (response) {
        uuidJson = {
            uuid: JSON.parse(response.responseText)
        }
        //console.log(uuidJson);
        jQuery.ajax({
            type: 'post',
            url: `${window.location.origin}/wp-admin/admin-ajax.php`,
            dataType: 'json',
            data: {
                action: 'get_preset_name',
                ajax_data: uuidJson
            },
            complete: function (response2) {
                //console.log(response2);
                const presetName = JSON.parse(response2.responseText);
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-warning settings-error lins-scroll-arrow-alert lins-scroll-new-preset-alert">
                        <p>
                            <strong>
                                Setting was saved successfully. However, your settings are different from <b>"${presetName}"</b>. Do you want to save the unsaved changes to <b>"${presetName}"</b>?
                            </strong>
                        </p>
                    </div>`;
                document.querySelector('.save-preset-changes').classList.remove('js-hide-alert');
                document.querySelector('.update-save-preset-changes').addEventListener('click', () => {
                    document.querySelector('.save-preset-changes').classList.add('js-hide-alert');
                });
            }
        });
    }
});


