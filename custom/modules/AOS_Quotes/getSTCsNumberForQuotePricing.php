<?php
ini_set('memory_limit', '-1');

$total_kw = $_REQUEST['total_kw'];
$postcode = $_REQUEST['postcode'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu/stc');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"sguType":"SolarDeemed","expectedInstallDate":"2020-12-31T00:00:00.000Z","ratedPowerOutputInKw":'.$total_kw.',"deemingPeriod":"ELEVEN_YEARS","postcode":"'.$postcode.'","sguDisclaimer":true,"useDefaultResourceAvailability":"true","sguTypeOptions":[{"sguDeemingPeriodsStrategies":[{"years":[2016,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Fifteen years","name":"FIFTEEN_YEARS"}]},{"years":[2017],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Fourteen years","name":"FOURTEEN_YEARS"}]},{"years":[2018],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Thirteen years","name":"THIRTEEN_YEARS"}]},{"years":[2019],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Twelve years","name":"TWELVE_YEARS"}]},{"years":[2020],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eleven years","name":"ELEVEN_YEARS"}]},{"years":[2021],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Ten years","name":"TEN_YEARS"}]},{"years":[2022],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Nine years","name":"NINE_YEARS"}]},{"years":[2023],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eight years","name":"EIGHT_YEARS"}]},{"years":[2024],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Seven years","name":"SEVEN_YEARS"}]},{"years":[2025],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Six years","name":"SIX_YEARS"}]},{"years":[2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - solar (deemed)","name":"SolarDeemed"},{"sguDeemingPeriodsStrategies":[{"years":[2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - wind (deemed)","name":"WindDeemed"},{"sguDeemingPeriodsStrategies":[{"years":[2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - hydro (deemed)","name":"HydroDeemed"}],"deemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eleven years","name":"ELEVEN_YEARS"}],"helpWithSolarCreditsVisible":true}');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
    "Content-Type: application/json; charset=UTF-8",
    "Accept: application/json, text/javascript, */*; q=0.01",
    "Accept-Language:  en-US,en;q=0.9",
    "Accept-Encoding:   gzip, deflate, br",
    "Connection: keep-alive",
    "Origin: https://www.rec-registry.gov.au",
    "Referer: https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu-stc-calculator"
));
$result = curl_exec($ch);
curl_close($ch);

$data_return =  json_decode($result);
if($data_return->status == 'Completed'){
    echo json_encode(array("NumberOfSTCs"=>$data_return->result->numberOfStcs));
    die;
}else{
    echo json_encode(array("NumberOfSTCs"=>''));
    die;
}