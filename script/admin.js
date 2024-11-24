function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function linsScrollReloadLoadedPreset() {
    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'reload_preset_select'
        },
        complete: function (repsonse) {
            if (!repsonse.responseText) {
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                The loaded presets could not be displayed. (Error 700)
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                repsonse.responseText = JSON.parse(repsonse.responseText);
                document.querySelector('#remove-preset').innerHTML = '';
                const currUuid = getCookie('loadedUuid');
                repsonse.responseText.forEach(currPreset => {
                    //console.log(currPreset);
                    const value = currPreset.uuid;
                    const name = currPreset.preset_name;
                    if (value === currUuid) {
                        document.querySelector('.preset-name').innerText = name;
                    }
                });
            }
        }
    });
}

function linsScrollReloadRemoveSelect() {
    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'reload_preset_select_remove'
        },
        complete: function (errors) {
            if (!errors.responseText) {
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                The presets in the "Remove Preset" section could not be reloaded after saving a new one. (Error 500)
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                errors.responseText = JSON.parse(errors.responseText);
                //const errorAlerts = (Object.values(errors.responseText));
                //console.log(errors.responseText);
                document.querySelector('#remove-preset').innerHTML = '';
                errors.responseText.forEach(currPreset => {
                    //console.log(currPreset);
                    const value = currPreset.uuid;
                    const name = currPreset.preset_name;
                    document.querySelector('#remove-preset').innerHTML += `<option value="${value}">${name}</option>`;
                });
            }
        }
    });
}

function linsScrollReloadPresetSelect() {
    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'reload_preset_select'
        },
        complete: function (errors) {
            if (!errors.responseText) {
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                The presets could not be reloaded after saving a new one. (Error 123)
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                errors.responseText = JSON.parse(errors.responseText);
                //const errorAlerts = (Object.values(errors.responseText));
                //console.log(errors.responseText);
                document.querySelector('#select-preset').innerHTML = '';
                errors.responseText.forEach(currPreset => {
                    //console.log(currPreset);
                    const value = currPreset.uuid;
                    const name = currPreset.preset_name;
                    let selectedCheck = '';
                    const currUuid = getCookie('loadedUuid');
                    if (value === currUuid) {
                        selectedCheck = 'selected';
                    }
                    document.querySelector('#select-preset').innerHTML += `<option value="${value}" ${selectedCheck}>${name}</option>`;
                });
            }
        }
    });
}

function linsScrollUpdatePreset() {
    const scrollArrowFill = document.querySelector('[name="lins_scroll_arrow_fill"]');
    //console.log(scrollArrowFill);

    const scrollArrowOpacity = document.querySelector('[name="lins_scroll_arrow_opacity"]');
    //console.log(scrollArrowOpacity);

    const scrollArrowBg = document.querySelector('[name="lins_scroll_arrow_color"]');
    //console.log(scrollArrowBg);

    const scrollArrowOpacityHover = document.querySelector('[name="lins_scroll_arrow_opacity_hover"]');
    //console.log(scrollArrowOpacityHover);

    const scrollArrowBgHover = document.querySelector('[name="lins_scroll_arrow_color_hover"]');
    //console.log(scrollArrowBgHover);

    const scrollArrowBgSize = document.querySelector('[name="lins_scroll_bg_size"]');
    //console.log(scrollArrowBgSize);

    const scrollArrowBgSizeLg = document.querySelector('[name="lins_scroll_bg_size_lg"]');
    //console.log(scrollArrowBgSizeLg);

    const scrollArrowBgSizeMd = document.querySelector('[name="lins_scroll_bg_size_md"]');
    //console.log(scrollArrowBgSizeMd);

    const scrollArrowBgSizeSm = document.querySelector('[name="lins_scroll_bg_size_sm"]');
    //console.log(scrollArrowBgSizeSm);

    const scrollArrowSize = document.querySelector('[name="lins_scroll_arrow_size"]');
    //console.log(scrollArrowSize);

    const scrollArrowMargin = document.querySelector('[name="lins_scroll_arrow_margin"]');
    //console.log(scrollArrowMargin);

    const scrollArrowMarginLg = document.querySelector('[name="lins_scroll_arrow_margin_lg"]');
    //console.log(scrollArrowMarginLg);

    const scrollArrowMarginMd = document.querySelector('[name="lins_scroll_arrow_margin_md"]');
    //console.log(scrollArrowMarginMd);

    const scrollArrowMarginSm = document.querySelector('[name="lins_scroll_arrow_margin_sm"]');
    //console.log(scrollArrowMarginSm);

    const scrollArrowTranslate = document.querySelector('[name="lins_scroll_arrow_translate"]');
    //console.log(scrollArrowTranslate);

    const scrollBgHeight = document.querySelector('[name="lins_scroll_bg_height"]');
    //console.log(scrollBgHeight);

    const scrollBgHeightLg = document.querySelector('[name="lins_scroll_bg_height_lg"]');
    //console.log(scrollBgHeightLg);

    const scrollBgHeightMd = document.querySelector('[name="lins_scroll_bg_height_md"]');
    //console.log(scrollBgHeightMd);

    const scrollBgHeightSm = document.querySelector('[name="lins_scroll_bg_height_sm"]');
    //console.log(scrollBgHeightSm);

    const scrollBgColor = document.querySelector('[name="lins_scroll_bg_color"]');
    //console.log(scrollBgColor);

    const scrollBgOpacity = document.querySelector('[name="lins_scroll_bg_opacity"]');
    //console.log(scrollBgOpacity);

    const currUuid = getCookie('loadedUuid');
    myPreset = {
        uuid: currUuid,
        scrollArrowFill: scrollArrowFill.value,
        scrollArrowOpacity: scrollArrowOpacity.value,
        scrollArrowBg: scrollArrowBg.value,
        scrollArrowOpacityHover: scrollArrowOpacityHover.value,
        scrollArrowBgHover: scrollArrowBgHover.value,
        scrollArrowBgSize: scrollArrowBgSize.value,
        scrollArrowBgSizeLg: scrollArrowBgSizeLg.value,
        scrollArrowBgSizeMd: scrollArrowBgSizeMd.value,
        scrollArrowBgSizeSm: scrollArrowBgSizeSm.value,
        scrollArrowSize: scrollArrowSize.value,
        scrollArrowMargin: scrollArrowMargin.value,
        scrollArrowMarginLg: scrollArrowMarginLg.value,
        scrollArrowMarginMd: scrollArrowMarginMd.value,
        scrollArrowMarginSm: scrollArrowMarginSm.value,
        scrollArrowTranslate: scrollArrowTranslate.value,
        scrollBgHeight: scrollBgHeight.value,
        scrollBgHeightLg: scrollBgHeightLg.value,
        scrollBgHeightMd: scrollBgHeightMd.value,
        scrollBgHeightSm: scrollBgHeightSm.value,
        scrollBgColor: scrollBgColor.value,
        scrollBgOpacity: scrollBgOpacity.value
    };

    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'update_preset',
            ajax_data: myPreset
        },
        complete: function (errors) {
            if (errors.responseText) {
                //console.log(errors);
                errors.responseText = JSON.parse(errors.responseText);
                const errorAlerts = (Object.values(errors.responseText));
                let errorAlertsSpaces = '';
                for (let i = 0; i < errorAlerts.length; i++) {
                    if (i !== errorAlerts.length - 1) {
                        errorAlertsSpaces += `${errorAlerts[i]}, `;
                    }
                    else {
                        errorAlertsSpaces += errorAlerts[i];
                    }
                }
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                ${errorAlertsSpaces}
                                Preset couldn't be updated.
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML += `<div class="notice notice-success settings-error lins-scroll-arrow-alert">
                                                                        <p>
                                                                            <strong>
                                                                                Preset updated successfully! To apply the preset, click on <span class="js-save-changes-to-page link-look">"Save Changes" at the bottom of the page or here</span>.
                                                                            </strong>
                                                                        </p>
                                                                    </div>`;
                linsScrollPresetApply();
                linsScrollReloadPresetSelect();
                linsScrollReloadRemoveSelect();
                linsScrollReloadLoadedPreset();
                linsScrollLoadPresetAjax(currUuid);
                jQuery.ajax({
                    type: 'post',
                    url: `${window.location.origin}/wp-admin/admin-ajax.php`,
                    dataType: 'json',
                    data: {
                        action: 'unlock_reload'
                    }
                });
            }
        }
    });

}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function linsScrollEditPreset() {
    const editPresetName = document.querySelector('[name="lins_scroll_preset_edit"]').value;
    const currUuid = getCookie('loadedUuid');
    newName = {
        uuid: currUuid,
        newName: editPresetName,
    };
    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'check_preset_name',
            ajax_data: newName
        },
        complete: function (response) {
            console.log((response.responseText));
            if (response.responseText) {
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                    <p>
                        <strong>
                            ${response.responseText}
                        </strong>
                    </p>
                </div>`;
                linsScrollTopCloseModal();
            }
            else {
                jQuery.ajax({
                    type: 'post',
                    url: `${window.location.origin}/wp-admin/admin-ajax.php`,
                    dataType: 'json',
                    data: {
                        action: 'edit_preset_name',
                        ajax_data: newName
                    },
                    complete: function (repsonse) {
                        if (!repsonse.responseText) {
                            document.querySelector('.alert-boxes').innerHTML +=
                                `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                                    <p>
                                        <strong>
                                            Preset name could not be updated! (Error 601)   
                                        </strong>
                                    </p>
                                </div>`;
                        }
                        else {
                            document.querySelector('.alert-boxes').innerHTML +=
                                `<div class="notice notice-success settings-success lins-scroll-arrow-alert">
                                    <p>
                                        <strong>
                                            Preset name was updated successfully! 
                                        </strong>
                                    </p>
                                </div>`;
                            linsScrollReloadPresetSelect();
                            linsScrollReloadRemoveSelect();
                            document.querySelector('.current-preset .preset-name').innerText = editPresetName;
                        }
                        linsScrollTopCloseModal();
                    }
                });
            }
        }
    });
}

function linsScrollRemoveConfirm() {
    confirmRemoveModal.classList.remove('js-modal-active');
    removeModal.classList.remove('js-modal-active');
    linsScrollTopCloseModal();

    deleteUuid = {
        uuid: removeId
    }
    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'remove_preset',
            ajax_data: deleteUuid
        },
        complete: function (repsonse) {
            if (!repsonse) {
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                Preset ${removeSelection} could not be removed! (Error 400)
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-success settings-success lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                Preset ${removeSelection} removed successfully!
                            </strong>
                        </p>
                    </div>`;
                linsScrollReloadPresetSelect();
                linsScrollReloadRemoveSelect();
                if (document.querySelector('.preset-name').innerHTML === removeSelection) {
                    document.querySelector('.preset-name').innerHTML = 'No preset selected.'
                    deleteCookie('loadedUuid');
                    jQuery.ajax({
                        type: 'post',
                        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
                        data: {
                            action: 'remove_loaded_preset'
                        }
                    });
                }
            }
            linsScrollTopCloseModal();
            removeSelection = null;
            removeId = null;
        }
    });
}

function linsScrollPresetApply() {
    const saveChangesSpans = document.querySelectorAll('.js-save-changes-to-page');
    saveChangesSpans.forEach(currSave => {
        currSave.addEventListener('click', () => {
            document.querySelector('#submit').click();
        });
    });
}

function linsScrollTopSavePreset() {

    document.querySelector('.save-new-def').classList.add('js-hide-alert');

    const presetName = document.querySelector('[name="lins_scroll_preset_name"]');
    //console.log(presetName);

    const scrollArrowFill = document.querySelector('[name="lins_scroll_arrow_fill"]');
    //console.log(scrollArrowFill);

    const scrollArrowOpacity = document.querySelector('[name="lins_scroll_arrow_opacity"]');
    //console.log(scrollArrowOpacity);

    const scrollArrowBg = document.querySelector('[name="lins_scroll_arrow_color"]');
    //console.log(scrollArrowBg);

    const scrollArrowOpacityHover = document.querySelector('[name="lins_scroll_arrow_opacity_hover"]');
    //console.log(scrollArrowOpacityHover);

    const scrollArrowBgHover = document.querySelector('[name="lins_scroll_arrow_color_hover"]');
    //console.log(scrollArrowBgHover);

    const scrollArrowBgSize = document.querySelector('[name="lins_scroll_bg_size"]');
    //console.log(scrollArrowBgSize);

    const scrollArrowBgSizeLg = document.querySelector('[name="lins_scroll_bg_size_lg"]');
    //console.log(scrollArrowBgSizeLg);

    const scrollArrowBgSizeMd = document.querySelector('[name="lins_scroll_bg_size_md"]');
    //console.log(scrollArrowBgSizeMd);

    const scrollArrowBgSizeSm = document.querySelector('[name="lins_scroll_bg_size_sm"]');
    //console.log(scrollArrowBgSizeSm);

    const scrollArrowSize = document.querySelector('[name="lins_scroll_arrow_size"]');
    //console.log(scrollArrowSize);

    const scrollArrowMargin = document.querySelector('[name="lins_scroll_arrow_margin"]');
    //console.log(scrollArrowMargin);

    const scrollArrowMarginLg = document.querySelector('[name="lins_scroll_arrow_margin_lg"]');
    //console.log(scrollArrowMarginLg);

    const scrollArrowMarginMd = document.querySelector('[name="lins_scroll_arrow_margin_md"]');
    //console.log(scrollArrowMarginMd);

    const scrollArrowMarginSm = document.querySelector('[name="lins_scroll_arrow_margin_sm"]');
    //console.log(scrollArrowMarginSm);

    const scrollArrowTranslate = document.querySelector('[name="lins_scroll_arrow_translate"]');
    //console.log(scrollArrowTranslate);

    const scrollBgHeight = document.querySelector('[name="lins_scroll_bg_height"]');
    //console.log(scrollBgHeight);

    const scrollBgHeightLg = document.querySelector('[name="lins_scroll_bg_height_lg"]');
    //console.log(scrollBgHeightLg);

    const scrollBgHeightMd = document.querySelector('[name="lins_scroll_bg_height_md"]');
    //console.log(scrollBgHeightMd);

    const scrollBgHeightSm = document.querySelector('[name="lins_scroll_bg_height_sm"]');
    //console.log(scrollBgHeightSm);

    const scrollBgColor = document.querySelector('[name="lins_scroll_bg_color"]');
    //console.log(scrollBgColor);

    const scrollBgOpacity = document.querySelector('[name="lins_scroll_bg_opacity"]');
    //console.log(scrollBgOpacity);

    myPreset = {
        presetName: presetName.value,
        scrollArrowFill: scrollArrowFill.value,
        scrollArrowOpacity: scrollArrowOpacity.value,
        scrollArrowBg: scrollArrowBg.value,
        scrollArrowOpacityHover: scrollArrowOpacityHover.value,
        scrollArrowBgHover: scrollArrowBgHover.value,
        scrollArrowBgSize: scrollArrowBgSize.value,
        scrollArrowBgSizeLg: scrollArrowBgSizeLg.value,
        scrollArrowBgSizeMd: scrollArrowBgSizeMd.value,
        scrollArrowBgSizeSm: scrollArrowBgSizeSm.value,
        scrollArrowSize: scrollArrowSize.value,
        scrollArrowMargin: scrollArrowMargin.value,
        scrollArrowMarginLg: scrollArrowMarginLg.value,
        scrollArrowMarginMd: scrollArrowMarginMd.value,
        scrollArrowMarginSm: scrollArrowMarginSm.value,
        scrollArrowTranslate: scrollArrowTranslate.value,
        scrollBgHeight: scrollBgHeight.value,
        scrollBgHeightLg: scrollBgHeightLg.value,
        scrollBgHeightMd: scrollBgHeightMd.value,
        scrollBgHeightSm: scrollBgHeightSm.value,
        scrollBgColor: scrollBgColor.value,
        scrollBgOpacity: scrollBgOpacity.value
    };

    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'save_preset',
            ajax_data: myPreset
        },
        complete: function (errors) {
            //console.log(errors);
            if (errors.responseText) {
                errors.responseText = JSON.parse(errors.responseText);
                const errorAlerts = (Object.values(errors.responseText));
                let errorAlertsSpaces = '';
                for (let i = 0; i < errorAlerts.length; i++) {
                    if (i !== errorAlerts.length - 1) {
                        errorAlertsSpaces += `${errorAlerts[i]}, `;
                    }
                    else {
                        errorAlertsSpaces += errorAlerts[i];
                    }
                }
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                ${errorAlertsSpaces}
                                Preset couldn't be saved.
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                linsScrollPresetApply();
                linsScrollReloadPresetSelect();
                linsScrollReloadRemoveSelect();
                setTimeout(() => {
                    document.querySelector('.preset-name').innerText = presetName.value;
                    const selectBox = document.querySelector('#select-preset');
                    selectBox[selectBox.length - 1].selected = true;
                    document.querySelector('.edit-preset-btn').classList.remove("js-button-disabled");
                    document.querySelector('.update-preset-btn').classList.remove("js-button-disabled");
                }, 50);
                linsScrollLoadPreset();
                setTimeout(() => {
                    document.querySelector('.alert-boxes').innerHTML = `<div class="notice notice-success settings-error lins-scroll-arrow-alert">
                                                                            <p>
                                                                                <strong>
                                                                                    Preset saved and loaded successfully! To apply the preset, click on <span class="js-save-changes-to-page link-look">"Save Changes" at the bottom of the page or here</span>.
                                                                                </strong>
                                                                            </p>
                                                                        </div>`;
                }, 50);
                const newUuid = getCookie('new_uuid');
                //linsScrollLoadPresetAjax(getCookie('new_uuid'));
                document.cookie = `loadedUuid=${newUuid}`;
                //console.log(newUuid);
            }
            linsScrollTopCloseModal();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}

function linsScrollLoadPresetAjax(currUuid) {
    uuidJson = {
        uuid: currUuid
    };

    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'load_preset',
            ajax_data: uuidJson
        },
        complete: function (presetSettings) {
            //console.log(presetSettings.responseText);
            const presetObj = JSON.parse(presetSettings.responseText);
            try {
                let r = '';
                let g = '';
                let b = '';
                let a = '';

                const arrowBgEl = document.querySelector('div.scroll-arrow');
                const svgEl = document.querySelector('div.scroll-arrow > svg');
                const bottomFadeEl = document.querySelector('div.scroll-bottom-fade');

                document.querySelector('.preset-name').innerHTML = presetObj.preset_name;
                document.querySelector('.old-preset-name').innerHTML = presetObj.preset_name;
                //console.log(presetObj);
                //document.querySelector('.preset-name').innerHTML = presetSettings.responseText;
                //document.querySelector('[name="lins_scroll_arrow_fill"]').value = '#FFFFFF';
                jQuery('[name="lins_scroll_arrow_fill"]').iris('color', `#${presetObj.arrow_fill}`);
                svgEl.style.fill = `#${presetObj.arrow_fill}`;

                document.querySelector('[name="lins_scroll_arrow_opacity"]').value = presetObj.arrow_opacity;

                jQuery('[name="lins_scroll_arrow_color"]').iris('color', `#${presetObj.arrow_bg}`);

                presetObj.arrow_opacity;
                const arrowBgRgb = hexToRgb(`#${presetObj.arrow_bg}`);
                const arrowBgHoverRgb = hexToRgb(`#${presetObj.arrow_bg_hover}`);
                const shadowRgb = hexToRgb(`#${presetObj.arrow_shadow_color}`);

                r = arrowBgRgb.r;
                g = arrowBgRgb.g;
                b = arrowBgRgb.b;
                a = presetObj.arrow_opacity;

                //arrowBgEl.style.background = `rgba(${r}, ${g}, ${b}, ${a})`;

                document.querySelector('[name="lins_scroll_arrow_opacity_hover"]').value = presetObj.arrow_opacity_hover;

                arrowBgEl.addEventListener("mouseout", () => {
                    r = arrowBgRgb.r;
                    g = arrowBgRgb.g;
                    b = arrowBgRgb.b;
                    a = presetObj.arrow_opacity;
                    arrowBgEl.style.backgroundColor = `rgba(${r}, ${g}, ${b}, ${a})`;
                });

                jQuery('[name="lins_scroll_arrow_color_hover"]').iris('color', `#${presetObj.arrow_bg_hover}`);

                arrowBgEl.addEventListener("mouseover", () => {
                    r = arrowBgHoverRgb.r;
                    g = arrowBgHoverRgb.g;
                    b = arrowBgHoverRgb.b;
                    a = presetObj.arrow_opacity_hover;
                    arrowBgEl.style.backgroundColor = `rgba(${r}, ${g}, ${b}, ${a})`;
                });

                document.querySelector('[name="lins_scroll_bg_size"]').value = presetObj.arrow_bg_size;

                document.querySelector('[name="lins_scroll_bg_size_lg"]').value = (presetObj.arrow_bg_size_lg);


                const mqDesktop = window.matchMedia('(min-width: 993px)');
                const mqLg = window.matchMedia('(min-width: 769px) and (max-width: 992px)');
                const mqMd = window.matchMedia('(min-width: 577px) and (max-width: 768px)');
                const mqSm = window.matchMedia('(max-width: 576px)');

                //Desktop
                function scrollDesktopView(e) {
                    if (e.matches) {
                        console.log('Desktop');
                        arrowBgEl.style.width = `${presetObj.arrow_bg_size}px`;
                        arrowBgEl.style.height = `${presetObj.arrow_bg_size}px`;
                        arrowBgEl.style.right = `${presetObj.arrow_margin}px`;
                        arrowBgEl.style.bottom = `${presetObj.arrow_margin}px`;
                        bottomFadeEl.style.height = `${presetObj.arrow_shadow_height}px`;
                    }
                }

                mqDesktop.addEventListener('change', scrollDesktopView);

                scrollDesktopView(mqDesktop);

                //Large
                function scrollLgView(e) {
                    if (e.matches) {
                        console.log('Large');
                        arrowBgEl.style.width = `${presetObj.arrow_bg_size_lg}px`;
                        arrowBgEl.style.height = `${presetObj.arrow_bg_size_lg}px`;
                        arrowBgEl.style.right = `${presetObj.arrow_margin_lg}px`;
                        arrowBgEl.style.bottom = `${presetObj.arrow_margin_lg}px`;
                        bottomFadeEl.style.height = `${presetObj.arrow_shadow_height_lg}px`;
                    }
                }

                mqLg.addEventListener('change', scrollLgView);

                scrollLgView(mqLg);

                //Medium
                function scrollMdView(e) {
                    if (e.matches) {
                        console.log('Medium');
                        arrowBgEl.style.width = `${presetObj.arrow_bg_size_md}px`;
                        arrowBgEl.style.height = `${presetObj.arrow_bg_size_md}px`;
                        arrowBgEl.style.right = `${presetObj.arrow_margin_md}px`;
                        arrowBgEl.style.bottom = `${presetObj.arrow_margin_md}px`;
                        bottomFadeEl.style.height = `${presetObj.arrow_shadow_height_md}px`;
                    }
                }

                mqMd.addEventListener('change', scrollMdView);

                scrollMdView(mqMd);

                //Small
                function scrollSmView(e) {
                    if (e.matches) {
                        console.log('Small');
                        arrowBgEl.style.width = `${presetObj.arrow_bg_size_sm}px`;
                        arrowBgEl.style.height = `${presetObj.arrow_bg_size_sm}px`;
                        arrowBgEl.style.right = `${presetObj.arrow_margin_sm}px`;
                        arrowBgEl.style.bottom = `${presetObj.arrow_margin_sm}px`;
                        bottomFadeEl.style.height = `${presetObj.arrow_shadow_height_sm}px`;
                    }
                }

                mqSm.addEventListener('change', scrollSmView);

                scrollSmView(mqSm);

                document.querySelector('[name="lins_scroll_bg_size_md"]').value = (presetObj.arrow_bg_size_md);
                document.querySelector('[name="lins_scroll_bg_size_sm"]').value = (presetObj.arrow_bg_size_sm);

                document.querySelector('[name="lins_scroll_arrow_size"]').value = (presetObj.arrow_size);

                svgEl.style.width = `${presetObj.arrow_size}%`;

                document.querySelector('[name="lins_scroll_arrow_margin"]').value = (presetObj.arrow_margin);
                document.querySelector('[name="lins_scroll_arrow_margin_lg"]').value = (presetObj.arrow_margin_lg);
                document.querySelector('[name="lins_scroll_arrow_margin_md"]').value = (presetObj.arrow_margin_md);
                document.querySelector('[name="lins_scroll_arrow_margin_sm"]').value = (presetObj.arrow_margin_sm);

                document.querySelector('[name="lins_scroll_arrow_translate"]').value = (presetObj.arrow_translate);

                document.querySelector('[name="lins_scroll_bg_height"]').value = (presetObj.arrow_shadow_height);
                document.querySelector('[name="lins_scroll_bg_height_lg"]').value = (presetObj.arrow_shadow_height_lg);
                document.querySelector('[name="lins_scroll_bg_height_md"]').value = (presetObj.arrow_shadow_height_md);
                document.querySelector('[name="lins_scroll_bg_height_sm"]').value = (presetObj.arrow_shadow_height_sm);

                jQuery('[name="lins_scroll_bg_color"]').iris('color', `#${presetObj.arrow_shadow_color}`);
                document.querySelector('[name="lins_scroll_bg_opacity"]').value = (presetObj.arrow_shadow_opacity);

                r = shadowRgb.r;
                g = shadowRgb.g;
                b = shadowRgb.b;
                a = presetObj.arrow_shadow_opacity;

                bottomFadeEl.style.backgroundColor = `rgba(${r}, ${g}, ${b}, ${a})`;

                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-success settings-success lins-scroll-arrow-alert js-loaded-successfully">
                        <p>
                            <strong>
                                Preset loaded successfully. To apply the preset, click on <span class="js-save-changes-to-page link-look">"Save Changes" at the bottom of the page or here</span>.
                            </strong>
                        </p>
                    </div>`;

                linsScrollPresetApply();
                document.querySelector('.edit-preset-btn').classList.add("js-show-btn");
                document.querySelector('.update-preset-btn').classList.add("js-show-btn");
                if (currUuid === '00000000-0000-0000-0000-000000000000') {
                    document.querySelector('.edit-preset-btn').classList.add("js-button-disabled");
                    document.querySelector('.update-preset-btn').classList.add("js-button-disabled");
                }
                else {
                    document.querySelector('.edit-preset-btn').classList.remove("js-button-disabled");
                    document.querySelector('.update-preset-btn').classList.remove("js-button-disabled");
                }

            } catch (error) {
                console.error(error);
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                Preset could not be loaded! (Error 300)
                                ${error}
                            </strong>
                        </p>
                    </div>`;
            }
        }
    });
};

function linsScrollLoadPreset() {
    const selectElem = document.querySelector('#select-preset');
    document.cookie = `loadedUuid=${selectElem.value}`;
    const currUuid = getCookie('loadedUuid');
    jQuery.ajax({
        type: 'post',
        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
        dataType: 'json',
        data: {
            action: 'unlock_reload'
        }
    });
    //alert(currUuid);
    //console.log(presetName);
    //console.log(currUuid)
    linsScrollLoadPresetAjax(currUuid);
}

function linsScrollSaveChanges() {
    document.querySelector('#submit').click();
}

function linsScrollCloseSaveNewDef() {
    document.querySelector('.save-new-def').classList.add('js-hide-alert');
    document.querySelector('.lins-scroll-new-def-alert').classList.add('js-hide-alert');
}

function linsScrollCloseSaveNewPreset() {
    document.querySelector('.save-preset-changes').classList.add('js-hide-alert');
    document.querySelector('.lins-scroll-new-preset-alert').classList.add('js-hide-alert');
}