<?php
global $current_user;
 date_default_timezone_set('Australia/Melbourne');
$vardefs_array = array(
    "quote_extra_plumbing" => array(
        "name" => "quote_extra_plumbing",
        'partnumber' => "Sanden_Complex_Install",
        "display_label" => "Plumbing Extra",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_plumbing_add" => array(
                "name" => "quote_extra_plumbing_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_plumbing_itemise" => array(
                "name" => "quote_extra_plumbing_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_plumbing_value" => array(
                "name" => "quote_extra_plumbing_value",
                "display_label" => "Plumbing Extra Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_electric" => array(
        "name" => "quote_extra_electric",
        'partnumber' => "SANDEN_ELEC_EXTRA",
        "display_label" => "Electrical Extra",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_electric_add" => array(
                "name" => "quote_extra_electric_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_electric_itemise" => array(
                "name" => "quote_extra_electric_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_electric_value" => array(
                "name" => "quote_extra_electric_value",
                "display_label" => "Plumbing Extra Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_rcbo" => array(
        "name" => "quote_extra_rcbo",
        'partnumber' => "RCBO",
        "display_label" => "RCBO",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_rcbo_add" => array(
                "name" => "quote_extra_rcbo_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_rcbo_itemise" => array(
                "name" => "quote_extra_rcbo_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_rcbo_value" => array(
                "name" => "quote_extra_rcbo_value",
                "display_label" => "RCBO Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_switch_upgrade" => array(
        "name" => "quote_extra_switch_upgrade",
        'partnumber' => "SwitchUpgrade",
        "display_label" => "SwitchUpgrade",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_switch_upgrade_add" => array(
                "name" => "quote_extra_switch_upgrade_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_switch_upgrade_itemise" => array(
                "name" => "quote_extra_switch_upgrade_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_switch_upgrade_value" => array(
                "name" => "quote_extra_switch_upgrade_value",
                "display_label" => "Switch Upgrade Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_hws_r" => array(
        "name" => "quote_extra_hws_r",
        'partnumber' => "HWS_R",
        "display_label" => "HWS_R",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_hws_r_add" => array(
                "name" => "quote_extra_hws_r_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_hws_r_itemise" => array(
                "name" => "quote_extra_hws_r_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_hws_r_value" => array(
                "name" => "quote_extra_hws_r_value",
                "display_label" => "HWS_R Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_tank_slab" => array(
        "name" => "quote_extra_sanden_tank_slab",
        'partnumber' => "Sanden_Tank_Slab",
        "display_label" => "Sanden Tank Slab",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_tank_slab_add" => array(
                "name" => "quote_extra_sanden_tank_slab_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_tank_slab_itemise" => array(
                "name" => "quote_extra_sanden_tank_slab_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_tank_slab_value" => array(
                "name" => "quote_extra_sanden_tank_slab_value",
                "display_label" => "Sanden Tank Slab Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_hp_pavers" => array(
        "name" => "quote_extra_sanden_hp_pavers",
        'partnumber' => "Sanden_HP_Pavers",
        "display_label" => "Sanden HP Pavers",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_hp_pavers_add" => array(
                "name" => "quote_extra_sanden_hp_pavers_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_hp_pavers_itemise" => array(
                "name" => "quote_extra_sanden_hp_pavers_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_hp_pavers_value" => array(
                "name" => "quote_extra_sanden_hp_pavers_value",
                "display_label" => "Sanden_HP_Pavers Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_hp_pavers" => array(
        "name" => "quote_extra_sanden_hp_pavers",
        'partnumber' => "Sanden_HP_Pavers",
        "display_label" => "Sanden HP Pavers",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_hp_pavers_add" => array(
                "name" => "quote_extra_sanden_hp_pavers_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_hp_pavers_itemise" => array(
                "name" => "quote_extra_sanden_hp_pavers_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_hp_pavers_value" => array(
                "name" => "quote_extra_sanden_hp_pavers_value",
                "display_label" => "Sanden_HP_Pavers Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_site_delivery" => array(
        "name" => "quote_extra_sanden_site_delivery",
        'partnumber' => "Site_Delivery",
        "display_label" => "Site Delivery",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_site_delivery_add" => array(
                "name" => "quote_extra_sanden_site_delivery_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_site_delivery_itemise" => array(
                "name" => "quote_extra_sanden_site_delivery_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_site_delivery_value" => array(
                "name" => "quote_extra_sanden_site_delivery_value",
                "display_label" => "Site Delivery Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_spec_trade_disc" => array(
        "name" => "quote_extra_sanden_spec_trade_disc",
        'partnumber' => "Spec_Trade_Disc",
        "display_label" => "Spec Trade Disc",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_spec_trade_disc_add" => array(
                "name" => "quote_extra_sanden_spec_trade_disc_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_spec_trade_disc_itemise" => array(
                "name" => "quote_extra_sanden_spec_trade_disc_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_spec_trade_disc_value" => array(
                "name" => "quote_extra_sanden_spec_trade_disc_value",
                "display_label" => "Spec Trade Disc Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_san_wall_bracket" => array(
        "name" => "quote_extra_sanden_san_wall_bracket",
        'partnumber' => "san_wall_bracket",
        "display_label" => "San Wall Bracket",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_san_wall_bracket_add" => array(
                "name" => "quote_extra_sanden_spec_trade_disc_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_san_wall_bracket_itemise" => array(
                "name" => "quote_extra_sanden_san_wall_bracket_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_san_wall_bracket_value" => array(
                "name" => "quote_extra_sanden_san_wall_bracket_value",
                "display_label" => "Spec Trade Disc Value",
                "type" => "number",
            )
        )
    ),
    "quote_extra_sanden_travel" => array(
        "name" => "quote_extra_sanden_travel",
        'partnumber' => "Travel",
        "display_label" => "Travel",
        "type" => "custom",
        "dataItem" => array(
            "quote_extra_sanden_travel_add" => array(
                "name" => "quote_extra_sanden_travel_add",
                "display_label" => "Add Extra",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_travel_itemise" => array(
                "name" => "quote_extra_sanden_travel_itemise",
                "display_label" => "Itemise",
                "type" => "checkbox",
            ),
            "quote_extra_sanden_travel_value" => array(
                "name" => "quote_extra_sanden_travel_value",
                "display_label" => "Travel",
                "type" => "number",
            )
        )
    ),
);