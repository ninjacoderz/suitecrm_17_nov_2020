
<?php

$record_id = trim($_GET['record_id']);

$account_bean = new Account();
$account_bean->retrieve($record_id);
$array_result = array (
    'eul_subfloor_100_c' => $account_bean->eul_subfloor_100_c !== '' ? $account_bean->eul_subfloor_100_c : 100,
    'eul_sub_floor_diff_200_c' => $account_bean->eul_sub_floor_diff_200_c !== '' ? $account_bean->eul_sub_floor_diff_200_c : 200,
    'eul_high_wall_85_c' => $account_bean->eul_high_wall_85_c !== '' ? $account_bean->eul_high_wall_85_c : 85,
    'eul_low_wall_30_c' => $account_bean->eul_low_wall_30_c !== '' ? $account_bean->eul_low_wall_30_c : 30,
    'eul_2nd_story_wall_300_c' => $account_bean->eul_2nd_story_wall_300_c !== '' ? $account_bean->eul_2nd_story_wall_300_c : 300,
    'eul_2nd_story_walkable_55_c' => $account_bean->eul_2nd_story_walkable_55_c !== '' ? $account_bean->eul_2nd_story_walkable_55_c : 55,
    'fridge_pipe_run_external15_c' => $account_bean->fridge_pipe_run_external15_c !== '' ? $account_bean->fridge_pipe_run_external15_c : 25,
    'electric_run_ext_wall_c' => $account_bean->electric_run_ext_wall_c !== '' ? $account_bean->electric_run_ext_wall_c : 25,
    'electric_run_roof_cavity_c' => $account_bean->electric_run_roof_cavity_c !== '' ? $account_bean->electric_run_roof_cavity_c : 100,
    'refrigeration_pipe_roof100_c' => $account_bean->refrigeration_pipe_roof100_c !== '' ? $account_bean->refrigeration_pipe_roof100_c : 100,
    'electric_run_sub_floor_c' => $account_bean->electric_run_sub_floor_c !== '' ? $account_bean->electric_run_sub_floor_c : 50,
    'ec_new_circuit_95_c' => $account_bean->ec_new_circuit_95_c !== '' ? $account_bean->ec_new_circuit_95_c : 95,
    'ec_local_add_rcd_45_c' => $account_bean->ec_local_add_rcd_45_c !== '' ? $account_bean->ec_local_add_rcd_45_c : 45,
);

echo json_encode($array_result);