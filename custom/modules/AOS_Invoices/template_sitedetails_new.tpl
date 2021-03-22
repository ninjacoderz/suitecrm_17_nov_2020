{* <div id="section_sitedetails" class="row edit-view-row"> *}
    <div id="block_image_site_detail" class="row edit-view-row">
        <div class="clear"></div>
        <div class="clear"></div>
        <div class="col-xs-12 edit-view-row-item image_site_detail">
            <div id="group_address_site_detail" class="col-xs-12 edit-view-row-item col-sm-6" style="display:none;">
                <fieldset>
                    <legend> Site Address </legend>
                </fieldset>
                <div class="col-xs-12 edit-view-row-item">
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS">
                        Address:
                    </div>
                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar"
                        field="detail_site_install_address_c">
                        <input type="text" name="detail_site_install_address_c" id="detail_site_install_address_c"
                            size="30" maxlength="255" value="" title="" class="ui-autocomplete-input"
                            autocomplete="off">
                    </div>
                    <!-- [/hide] -->
                </div>
                <div class="col-xs-12 edit-view-row-item">
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_SITE_DETAILS_INSTALL_ADDRESS_CITY">
                        City:
                    </div>
                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar"
                        field="detail_site_install_address_city_c">
                        <input type="text" name="detail_site_install_address_city_c"
                            id="detail_site_install_address_city_c" size="30" maxlength="100" value="" title="">
                    </div>
                    <!-- [/hide] -->
                </div>
                <div class="col-xs-12 edit-view-row-item">
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS_STATE">
                        State:
                    </div>
                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar"
                        field="detail_site_install_address_state_c">
                        <input type="text" name="detail_site_install_address_state_c"
                            id="detail_site_install_address_state_c" size="30" maxlength="100" value="" title="">
                    </div>
                    <!-- [/hide] -->
                </div>
                <div class="col-xs-12 edit-view-row-item">
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS_POSTALCODE">
                        PostalCode:
                    </div>
                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar"
                        field="detail_site_install_address_postalcode_c">
                        <input type="text" name="detail_site_install_address_postalcode_c"
                            id="detail_site_install_address_postalcode_c" size="30" maxlength="20" value="" title="">
                    </div>
                    <!-- [/hide] -->
                </div>
                <div class="col-xs-12 edit-view-row-item">
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS_COUNTRY">
                        Country:
                    </div>
                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar"
                        field="detail_site_install_address_country_c">
                        <input type="text" name="detail_site_install_address_country_c"
                            id="detail_site_install_address_country_c" size="30" maxlength="100" value="" title="">
                    </div>
                    <!-- [/hide] -->
                </div>
                <div class=" col-xs-12 edit-view-field" style="display: block;"> <label>Copy address from billing
                        address:</label><input id="check_addr_site_detail_c" name="check_addr_site_detail_c"
                        type="checkbox">
                    {* </div> *}
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="col-md-12 col-sm-12 col-xs-12" id="maptemplate-img">
                    <div id="popup_image_site_detail_image" style="display:none;">
                        <div id="Map_Template_Image_popup"></div>
                    </div>

                    <div id="Map_Template_Image"
                        style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;">
                        Map Template Image</div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div style="background-color: white;border: 1px solid #9E9E9E;position: absolute;padding: 3px 3px 0px 3px;margin-top: 0px;left: 205px;z-index: 999;width: 150px;display:none"
                        id="import_button">
                        <ul>
                            <li><button type="button" id="open_map_google" style="width:100%" class="button">Map
                                    Google</button></li>
                            <li><button type="button" id="open_nearmap" style="width:100%" class="button">Near
                                    Map</button></li>
                        </ul>
                    </div>
                    <button type="button" id="Upload_Image_Site_Detail" class="button"
                        title="Upload Image Site Detail">Upload</button>
                </div>
            </div>
        </div>
    </div>
{* </div> *}
<div class="clear"></div>