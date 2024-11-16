function linsScrollTopSavePreset() {

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
                    `<div id="setting-error-settings_updated" class="notice notice-error settings-error lins-scroll-arrow-alert">
                        <p>
                            <strong>
                                ${errorAlertsSpaces}
                            </strong>
                        </p>
                    </div>`;
            }
            else {
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML += `<div id="setting-error-settings_updated" class="notice notice-success settings-error lins-scroll-arrow-alert">
                <p>
                    <strong>
                        Preset saved successfully! Do you want to  apply the settings to the page? Click "Save Changes" at the bottom!
                    </strong>
                </p>
            </div>`;

            }
            linsScrollTopCloseModal();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}

function linsScrollLoadPreset() {
    const selectElem = document.querySelector('#select-preset');
    const uuid = selectElem.value;
    //console.log(uuid);
    //console.log(presetName);

    uuidJson = {
        uuid: uuid
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
            const presetObject = JSON.parse(presetSettings.responseText);
            try {
                document.querySelector('.preset-name').innerHTML = presetObject.preset_name;
                //console.log(presetObject);
                //document.querySelector('.preset-name').innerHTML = presetSettings.responseText;
                //document.querySelector('[name="lins_scroll_arrow_fill"]').value = '#FFFFFF';
                jQuery('[name="lins_scroll_arrow_fill"]').iris('color', `#${presetObject.arrow_fill}`);
                document.querySelector('[name="lins_scroll_arrow_opacity"]').value = presetObject.arrow_opacity;
                //console.log(presetObject.arrow_opacity);
                jQuery('[name="lins_scroll_arrow_color"]').iris('color', `#${presetObject.arrow_bg}`);
                document.querySelector('[name="lins_scroll_arrow_opacity_hover"]').value = presetObject.arrow_opacity_hover;
                jQuery('[name="lins_scroll_arrow_color_hover"]').iris('color', `#${presetObject.arrow_bg_hover}`);
                document.querySelector('[name="lins_scroll_bg_size"]').value = presetObject.arrow_bg_size;
                document.querySelector('[name="lins_scroll_bg_size_lg"]').value = (presetObject.arrow_bg_size_lg);
                document.querySelector('[name="lins_scroll_bg_size_md"]').value = (presetObject.arrow_bg_size_md);
                document.querySelector('[name="lins_scroll_bg_size_sm"]').value = (presetObject.arrow_bg_size_sm);
                document.querySelector('[name="lins_scroll_arrow_size"]').value = (presetObject.arrow_size);
                document.querySelector('[name="lins_scroll_arrow_margin"]').value = (presetObject.arrow_margin);
                document.querySelector('[name="lins_scroll_arrow_margin_lg"]').value = (presetObject.arrow_margin_lg);
                document.querySelector('[name="lins_scroll_arrow_margin_md"]').value = (presetObject.arrow_margin_md);
                document.querySelector('[name="lins_scroll_arrow_margin_sm"]').value = (presetObject.arrow_margin_sm);
                document.querySelector('[name="lins_scroll_arrow_translate"]').value = (presetObject.arrow_translate);
                document.querySelector('[name="lins_scroll_bg_height"]').value = (presetObject.arrow_shadow_height);
                document.querySelector('[name="lins_scroll_bg_height_lg"]').value = (presetObject.arrow_shadow_height_lg);
                document.querySelector('[name="lins_scroll_bg_height_md"]').value = (presetObject.arrow_shadow_height_md);
                document.querySelector('[name="lins_scroll_bg_height_sm"]').value = (presetObject.arrow_shadow_height_sm);
                jQuery('[name="lins_scroll_bg_color"]').iris('color', `#${presetObject.arrow_shadow_color}`);
                document.querySelector('[name="lins_scroll_bg_opacity"]').value = (presetObject.arrow_shadow_opacity);

                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div id="setting-error-settings_updated" class="notice notice-success settings-success lins-scroll-arrow-alert">
                <p>
                    <strong>
                        Preset loaded successfully. To aply the preset, click on "Save Changes" at the bottom of the page.
                    </strong>
                </p>
            </div>`;

            } catch (error) {
                console.error(error);
                const alerts = document.querySelectorAll('.lins-scroll-arrow-alert');
                alerts.forEach(currAlert => {
                    currAlert.classList.add('js-hide-alert');
                });
                document.querySelector('.alert-boxes').innerHTML +=
                    `<div id="setting-error-settings_updated" class="notice notice-error settings-error lins-scroll-arrow-alert">
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
}