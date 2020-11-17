<div id="section_sitedetails" class="row edit-view-row">
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_PE_SITE_DETAILS_NO_C">
      PE Site Details Site Number:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="pe_site_details_no_c">
      <input type="text" name="pe_site_details_no_c" id="pe_site_details_no_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_SG_SITE_DETAILS_NO_C">
      SG Site Details Site Number:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="sg_site_details_no_c">
      <input type="text" name="sg_site_details_no_c" id="sg_site_details_no_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_SOLARGAIN_QUOTE_NUMBER">
      Solargain Quote Number:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="solargain_quote_number_c">
      <input type="text" name="solargain_quote_number_c" id="solargain_quote_number_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item" style="display: none;">
</div>
<div class="clear"></div>
<div class="clear"></div>
<div id="block_image_site_detail">
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
            <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="detail_site_install_address_c">
               <input type="text" name="detail_site_install_address_c" id="detail_site_install_address_c" size="30" maxlength="255" value="" title="" class="ui-autocomplete-input" autocomplete="off">
            </div>
            <!-- [/hide] -->
         </div>
         <div class="col-xs-12 edit-view-row-item">
            <div class="col-xs-12 col-sm-4 label" data-label="LBL_SITE_DETAILS_INSTALL_ADDRESS_CITY">
                City:
            </div>
            <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="detail_site_install_address_city_c">
               <input type="text" name="detail_site_install_address_city_c" id="detail_site_install_address_city_c" size="30" maxlength="100" value="" title="">
            </div>
            <!-- [/hide] -->
         </div>
         <div class="col-xs-12 edit-view-row-item">
            <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS_STATE">
               State:
            </div>
            <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="detail_site_install_address_state_c">
               <input type="text" name="detail_site_install_address_state_c" id="detail_site_install_address_state_c" size="30" maxlength="100" value="" title="">
            </div>
            <!-- [/hide] -->
         </div>
         <div class="col-xs-12 edit-view-row-item">
            <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS_POSTALCODE">
               PostalCode:
            </div>
            <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="detail_site_install_address_postalcode_c">
               <input type="text" name="detail_site_install_address_postalcode_c" id="detail_site_install_address_postalcode_c" size="30" maxlength="20" value="" title="">
            </div>
            <!-- [/hide] -->
         </div>
<div class="col-xs-12 edit-view-row-item">
        <div class="col-xs-12 col-sm-4 label" data-label="LBL_INSTALL_ADDRESS_COUNTRY">
            Country:
        </div>
        <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="detail_site_install_address_country_c">
            <input type="text" name="detail_site_install_address_country_c" id="detail_site_install_address_country_c" size="30" maxlength="100" value="" title="">
        </div>
        <!-- [/hide] -->
         <input style="display:none;" type="file" name="image_site_detail" id="image_site_detail" "="">                         
    </div>
         <div class="col-xs-12 edit-view-field" style="display: block;"> <label>Copy address from billing address:</label><input id="check_addr_site_detail_c" name="check_addr_site_detail_c" type="checkbox"></div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-6">
         <div class="col-md-12 col-sm-12 col-xs-12" id="maptemplate-img">
            <div id="popup_image_site_detail_image" style="display:none;">
                <div id="Map_Template_Image_popup" ></div>
            </div>
 
            <div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;">Map Template Image</div>
         </div>
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div style="background-color: white;border: 1px solid #9E9E9E;position: absolute;padding: 3px 3px 0px 3px;margin-top: 0px;left: 205px;z-index: 999;width: 150px;display:none" id="import_button">
               <ul>
                  <li><button type="button" id="open_map_google" style="width:100%" class="button">Map Google</button></li>
                  <li><button type="button" id="open_nearmap" style="width:100%" class="button">Near Map</button></li>
               </ul>
            </div>
            <button type="button" id="Upload_Image_Site_Detail" class="button" title="Upload Image Site Detail">Upload</button>
         </div>
      </div>

   </div>
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item" style="display: none;">
</div>
<div class="clear" style="display: none;"></div>
<div class="clear" style="display: none;"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item" style="display: none;">
</div>
<div class="clear" style="display: none;"></div>
<div class="clear" style="display: none;"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item" style="display: none;">
</div>
<div class="clear" style="display: none;"></div>
<div class="clear" style="display: none;"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item" style="display: none;">
</div>
<div class="clear" style="display: none;"></div>
<div class="clear" style="display: none;"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item" style="display: none;">
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_CUSTOMER_TYPE">
      Customer Type:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="radioenum" field="customer_type_c">
      <label><input type="radio" name="customer_type_c" value="0" checked="checked" id="customer_type_c" title="">Residential</label><br>
      <label><input type="radio" name="customer_type_c" value="1" id="customer_type_c" title="">Business</label><br>
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_ROOF_TYPE">
      Roof Type:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="roof_type_c">
      <select name="roof_type_c" id="roof_type_c" title="">
         <option label="Tin" value="Tin">Tin</option>
         <option label="Tile" value="Tile" selected="selected">Tile</option>
         <option label="Klip Loc" value="klip_loc">Klip Loc</option>
         <option label="Concrete" value="Concrete">Concrete</option>
         <option label="Trim Deck" value="Trim_Deck">Trim Deck</option>
         <option label="Insulated" value="Insulated">Insulated</option>
         <option label="Asbestos" value="Asbestos">Asbestos</option>
         <option label="Ground Mount" value="Ground_Mount">Ground Mount</option>
         <option label="Terracotta" value="Terracotta">Terracotta</option>
         <option label="Other" value="Other">Other</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_GUTTER_HEIGHT">
      Gutter Height:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="gutter_height_c">
      <select name="gutter_height_c" id="gutter_height_c" title="">
         <option label="0-3m" value="1" selected="selected">0-3m</option>
         <option label="3-5m" value="2">3-5m</option>
         <option label="5m - 10m" value="3">5m - 10m</option>
         <option label="10m - 15m" value="4">10m - 15m</option>
         <option label="15m+" value="5">15m+</option>
         <option label="Other" value="6">Other</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_EXPORT_METER_C">
      Export Meter:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="export_meter_c">
      <input type="hidden" name="export_meter_c" value="0"> 
      <input type="checkbox" id="export_meter_c" name="export_meter_c" value="false" title="" tabindex="0">
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_POTENTIAL_ISSUES_C">
      Potential Issues:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="multienum" field="potential_issues_c">
      <input type="hidden" id="potential_issues_c_multiselect" name="potential_issues_c_multiselect" value="true">
      <select id="potential_issues_c" name="potential_issues_c[]" multiple="true" size="6" style="max-width:350px" title="" tabindex="0">
         <option label="Asbestos" value="Asbestos">Asbestos</option>
         <option label="Shading" value="Shading">Shading</option>
         <option label="Raked Ceiling" value="Raked_Ceiling">Raked Ceiling</option>
         <option label="Chimneys / Flues" value="Chimneys_Flues">Chimneys / Flues</option>
         <option label="Roof Space / Pitch / Orientation  Access  Structural  Obstructions" value="RoofSpacePitch_Obstructions">Roof Space / Pitch / Orientation  Access  Structural  Obstructions</option>
         <option label="Access" value="Access">Access</option>
         <option label="Structural" value="Structural">Structural</option>
         <option label="Obstructions" value="Obstructions">Obstructions</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_CABLE_SIZE_C">
      Cable Size:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="cable_size_c">
      <input type="text" name="cable_size_c" id="cable_size_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_CONNECTION_TYPE">
      Connection Type:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="connection_type_c">
      <select name="connection_type_c" id="connection_type_c" title="">
         <option label="Underground" value="Underground" selected="selected">Underground</option>
         <option label="Overhead" value="Overhead">Overhead</option>
         <option label="Semi Rural/Remote Meter" value="Semi_Rural_Remote_Meter">Semi Rural/Remote Meter</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_MAIN_TYPE">
      Main Type:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="main_type_c">
      <select name="main_type_c" id="main_type_c" title="">
         <option label="High Voltage" value="1">High Voltage</option>
         <option label="Low Voltage" value="2" selected="selected">Low Voltage</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_METER_NUMBER">
      Billing Meter number:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="meter_number_c">
      <input type="text" name="meter_number_c" id="meter_number_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_METER_PHASE">
      Billing Meter phase:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="meter_phase_c">
      <select name="meter_phase_c" id="meter_phase_c" title="">
         <option label=" Single Phase" value="1" selected="selected"> Single Phase</option>
         <option label="Two Phase (Rural Only)" value="2">Two Phase (Rural Only)</option>
         <option label="Three Phase" value="3">Three Phase</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_NMI">
      NMI (billing account):
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="nmi_c">
      <input type="text" name="nmi_c" id="nmi_c" size="30" maxlength="255" value="" title="">
      <div id="text_check_nmi"></div>
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_ACCOUNT_NUMBER">
      Billing account number:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="account_number_c">
      <input type="text" name="account_number_c" id="account_number_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_ADDRESS_NMI">
      Address NMI:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="address_nmi_c">
      <input type="text" name="address_nmi_c" id="address_nmi_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_NAME_ON_BILLING_ACCOUNT">
      Name (billing account):
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="name_on_billing_account_c">
      <input type="text" name="name_on_billing_account_c" id="name_on_billing_account_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_DISTRIBUTOR">
      Distributor:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="distributor_c">
      <select name="distributor_c" id="distributor_c" title="">
         <option label="-Blank-" value="0" selected="selected">-Blank-</option>
         <option label="Citipower" value="4">Citipower</option>
         <option label="Jemena" value="5">Jemena</option>
         <option label="Powercor" value="6">Powercor</option>
         <option label="SP Ausnet" value="7">SP Ausnet</option>
         <option label="United Energy Distribution" value="8">United Energy Distribution</option>
         <option label="Western Power" value="1">Western Power</option>
         <option label="South Australia Power Network" value="13">South Australia Power Network</option>
         <option label="Energex" value="2">Energex</option>
         <option label="Ergon" value="3">Ergon</option>
         <option label="Essential Energy" value="9">Essential Energy</option>
         <option label="Ausgrid" value="10">Ausgrid</option>
         <option label="Endeavour Energy" value="12">Endeavour Energy</option>
         <option label="EVO Energy" value="11">EVO Energy</option>
         <option label="AusNet Electricity Services Pty Ltd" value="14">AusNet Electricity Services Pty Ltd</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_ENERGY_RETAILER">
      Energy Retailer:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="energy_retailer_c">
      <select name="energy_retailer_c" id="energy_retailer_c" title="">
         <option label="-Blank-" value="0" selected="selected">-Blank-</option>
         <option label="ACTEW" value="33">ACTEW</option>
         <option label="AGL Sales" value="3">AGL Sales</option>
         <option label="Alinta Energy" value="4">Alinta Energy</option>
         <option label="Amanda Energy" value="44">Amanda Energy</option>
         <option label="Amaysim" value="58">Amaysim</option>
         <option label="Aurora Energy" value="5">Aurora Energy</option>
         <option label="Australian Power &amp; Gas" value="6">Australian Power &amp; Gas</option>
         <option label="Blue NRG" value="7">Blue NRG</option>
         <option label="Change Energy" value="54">Change Energy</option>
         <option label="CleanTech" value="56">CleanTech</option>
         <option label="Click Energy" value="8">Click Energy</option>
         <option label="Comander" value="48">Comander</option>
         <option label="Country Energy" value="32">Country Energy</option>
         <option label="CovaU" value="38">CovaU</option>
         <option label="CS Energy" value="9">CS Energy</option>
         <option label="Diamond Energy" value="10">Diamond Energy</option>
         <option label="Dodo Power &amp; Gas" value="11">Dodo Power &amp; Gas</option>
         <option label="EDL Retail" value="12">EDL Retail</option>
         <option label="Energy Locals" value="51">Energy Locals</option>
         <option label="EnergyAustralia" value="13">EnergyAustralia</option>
         <option label="Ergon" value="35">Ergon</option>
         <option label="ERM Power Retail" value="14">ERM Power Retail</option>
         <option label="Essential Energy" value="31">Essential Energy</option>
         <option label="Flinders Operating Services" value="15">Flinders Operating Services</option>
         <option label="GloBird Energy" value="42">GloBird Energy</option>
         <option label="GoEnergy" value="16">GoEnergy</option>
         <option label="Horizon Power" value="2">Horizon Power</option>
         <option label="Infigen Energy" value="17">Infigen Energy</option>
         <option label="Integral Energy" value="34">Integral Energy</option>
         <option label="Kleenheat" value="57">Kleenheat</option>
         <option label="Landfill Gas &amp; Power" value="39">Landfill Gas &amp; Power</option>
         <option label="Lumo Energy" value="18">Lumo Energy</option>
         <option label="Mojo" value="43">Mojo</option>
         <option label="Momentum Energy" value="19">Momentum Energy</option>
         <option label="Neighborhood Energy" value="30">Neighborhood Energy</option>
         <option label="Next Business Energy" value="49">Next Business Energy</option>
         <option label="No Retailer - Under Construction" value="37">No Retailer - Under Construction</option>
         <option label="Origin Energy" value="20">Origin Energy</option>
         <option label="OzGen Retail" value="21">OzGen Retail</option>
         <option label="Pacific Hyrdro" value="47">Pacific Hyrdro</option>
         <option label="People Energy" value="50">People Energy</option>
         <option label="Perth Energy" value="40">Perth Energy</option>
         <option label="PG Energy" value="41">PG Energy</option>
         <option label="Powerdirect" value="22">Powerdirect</option>
         <option label="Powershop" value="36">Powershop</option>
         <option label="Progressive Green" value="23">Progressive Green</option>
         <option label="Qenergy" value="24">Qenergy</option>
         <option label="Red Energy" value="25">Red Energy</option>
         <option label="Sanctuary Energy" value="26">Sanctuary Energy</option>
         <option label="Select Solutions" value="45">Select Solutions</option>
         <option label="Simply Energy" value="27">Simply Energy</option>
         <option label="Stanwell Corp" value="28">Stanwell Corp</option>
         <option label="Sumo Power" value="46">Sumo Power</option>
         <option label="Synergy" value="1">Synergy</option>
         <option label="Tango Energy" value="52">Tango Energy</option>
         <option label="TRU Energy" value="29">TRU Energy</option>
         <option label="Veeve Energy" value="53">Veeve Energy</option>
         <option label="WINconnect Embedded Network" value="55">WINconnect Embedded Network</option>
      </select>
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
</div>
<div class="col-xs-12 col-sm-6 edit-view-row-item">
   <div class="col-xs-12 col-sm-4 label" data-label="LBL_ACCOUNT_HOLDER_DOB_C">
      Account Holder DoB:
   </div>
   <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="account_holder_dob_c">
      <input type="text" name="account_holder_dob_c" id="account_holder_dob_c" size="30" maxlength="255" value="" title="">
   </div>
   <!-- [/hide] -->
</div>
<div class="clear"></div>
<div class="clear"></div>
</div>