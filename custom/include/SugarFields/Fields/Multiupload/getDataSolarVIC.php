<?php

date_default_timezone_set('Africa/Lagos');
set_time_limit(0);
ini_set('memory_limit', '-1');
require_once(dirname(__FILE__).'/simple_html_dom.php');

$tmpfname = dirname(__FILE__).'/solarvicCookie.txt';

if(isset($_REQUEST['solarvicID'])){
  $recordId = $_REQUEST['solarvicID'];
}
//set cookie
  function set_cookie($ch,$tmpfname){
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  }
//

//info login
  $data_request = $_REQUEST;
  if($data_request["assigned_user_id"] == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
    $username = urlencode('matthew.wright@solargain.com.au');
  }else{
    $username = urlencode('paul.szuster@solargain.com.au');
  }
  $pass = urlencode('sPureandTrue2019*');
//

//get qcqq before login
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/login');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Connection: keep-alive';
  $headers[] = 'Cache-Control: max-age=0';
  $headers[] = 'Upgrade-Insecure-Requests: 1';
  $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
  $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  $html = str_get_html($result);
  $QCQQ = $html->find('input[name="QCQQ"]',0)->value;
//

//login
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/login');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "pqs=%3FstartURL%3D%252Findustry%252Fs%252F%26ec%3D302&un=".$username."&width=1366&height=768&hasRememberUn=true&startURL=%2Findustry%2Fs%2F&loginURL=&loginType=&useSecure=true&local=&lt=standard&qs=r%3Dhttps%253A%252F%252Fsolarvic.force.com%252Findustry%252Fs%252F&locale=&oauth_token=&oauth_callback=&login=&serverid=&QCQQ=".$QCQQ."&display=page&username=".$username."&ExtraLog=%255B%257B%2522width%2522%3A1366%257D%2C%257B%2522height%2522%3A768%257D%2C%257B%2522language%2522%3A%2522en%2522%257D%2C%257B%2522offset%2522%3A-7%257D%2C%257B%2522scripts%2522%3A%255B%257B%2522size%2522%3A249%2C%2522summary%2522%3A%2522if%2520%28self%2520%3D%3D%2520top%29%2520%257Bdocument.documentElement.style.v%2522%257D%2C%257B%2522size%2522%3A570%2C%2522summary%2522%3A%2522var%2520SFDCSessionVars%3D%257B%255C%2522server%255C%2522%3A%255C%2522https%3A%255C%255C%2F%255C%255C%2Flogin.sal%2522%257D%2C%257B%2522url%2522%3A%2522https%3A%2F%2Fsolarvic.force.com%2Findustry%2Fjslibrary%2FSfdcSessionBase208.js%2522%257D%2C%257B%2522url%2522%3A%2522https%3A%2F%2Fsolarvic.force.com%2Findustry%2Fjslibrary%2FLoginHint208.js%2522%257D%2C%257B%2522size%2522%3A26%2C%2522summary%2522%3A%2522LoginHint.hideLoginForm%28%29%3B%2522%257D%2C%257B%2522size%2522%3A36%2C%2522summary%2522%3A%2522LoginHint.getSavedIdentities%28false%29%3B%2522%257D%2C%257B%2522url%2522%3A%2522https%3A%2F%2Fsolarvic.force.com%2Findustry%2Fjslibrary%2Fbaselogin4.js%2522%257D%2C%257B%2522url%2522%3A%2522https%3A%2F%2Fsolarvic.force.com%2Findustry%2Fjslibrary%2FLoginMarketingSurveyResponse.js%2522%257D%2C%257B%2522size%2522%3A262%2C%2522summary%2522%3A%2522function%2520handleLogin%28%29%257Bdocument.login.un.value%3Ddoc%2522%257D%255D%257D%2C%257B%2522scriptCount%2522%3A9%257D%2C%257B%2522iframes%2522%3A%255B%2522https%3A%2F%2Flogin.salesforce.com%2Flogin%2Fsessionserver212.html%2522%255D%257D%2C%257B%2522iframeCount%2522%3A1%257D%2C%257B%2522referrer%2522%3A%2522https%3A%2F%2Fsolarvic.force.com%2Findustry%2Fs%2F%2522%257D%255D&pw=".$pass."&Login=Log+In");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Connection: keep-alive';
  $headers[] = 'Pragma: no-cache';
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Origin: https://solarvic.force.com';
  $headers[] = 'Upgrade-Insecure-Requests: 1';
  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
  $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
  $headers[] = 'Referer: https://solarvic.force.com/industry/login';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en,vi;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);
//

//get token
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Connection: keep-alive';
  $headers[] = 'Upgrade-Insecure-Requests: 1';
  $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
  $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
  $headers[] = 'Referer: https://solarvic.force.com/industry/apex/CommunitiesLanding';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  $pattern = '/"token":"(.*?)","/';
  $returnValue = preg_match($pattern, $result, $matches);
  $token = '';
  if($matches[1]!=''){
    $token =  urlencode($matches[1]);
  }
//

//get fuid
  $pattern = '/"fwuid":"(.*?)","/';
  $returnValue = preg_match($pattern, $result, $matches);
  $fwuid = '';
  if($matches[1]!=''){
    $fwuid =  urlencode($matches[1]);
  }
//

//get communityApp
  $pattern = '/siteforce:communityApp":"(.*?)"}/';
  $returnValue = preg_match($pattern, $result, $matches);
  $communityApp = '';
  if($matches[1]!=''){
    $communityApp =  urlencode($matches[1]);
  }
//

//get reportChart
  $reportchart_mess = array (
    'actions' => 
    array (
      0 => 
      array (
        'id' => '2;a',
        'descriptor' => 'serviceComponent://ui.comm.runtime.components.aura.components.siteforce.controller.PubliclyCacheableComponentLoaderController/ACTION$getPageComponent',
        'callingDescriptor' => 'UNKNOWN',
        'params' => 
        array (
          'attributes' => 
          array (
            'viewId' => 'b5b1eb95-d363-4e4e-b801-7f0a5bbc4ff1',
            'routeType' => 'home',
            'themeLayoutType' => 'Home',
            'params' => 
            array (
              'viewid' => 'caf2ead6-5671-437d-bee5-e599d442a419',
              'view_uddid' => '',
              'entity_name' => '',
              'audience_name' => '',
              'picasso_id' => '',
              'routeId' => '',
            ),
            'pageLoadType' => 'STANDARD_PAGE_CONTENT',
            'includeLayout' => true,
          ),
          'publishedChangelistNum' => 19,
          'brandingSetId' => 'f3fa8493-625a-4357-abb8-c020abd5771a',
        ),
      ),
    ),
  );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=0&ui-comm-runtime-components-aura-components-siteforce-controller.PubliclyCacheableComponentLoader.getPageComponent=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($reportchart_mess))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2F&aura.token=".$token);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Origin: https://solarvic.force.com';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
  $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  $headers[] = 'Accept: */*';
  $headers[] = 'Referer: https://solarvic.force.com/industry/s/';
  $headers[] = 'Connection: keep-alive';
  $result = curl_exec($ch);
  curl_close($ch);

  $result_decode_new = json_decode($result,true);
  $reportChart = $result_decode_new['context']['loaded']['COMPONENT@markup://forceCommunity:reportChart'];
//

//get recordLayoutBroker
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=1&aura.Component.getComponent=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=%7B%22actions%22%3A%5B%7B%22id%22%3A%22238%3Ba%22%2C%22descriptor%22%3A%22aura%3A%2F%2FComponentController%2FACTION%24getComponent%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22name%22%3A%22markup%3A%2F%2Fforce%3ArecordLayoutBroker%22%2C%22attributes%22%3A%7B%22recordId%22%3A%220010o00002MQZk5%22%2C%22recordTypeId%22%3Anull%2C%22entityName%22%3Anull%2C%22mode%22%3A%22VIEW%22%2C%22record%22%3Anull%2C%22showOfflineMessage%22%3Atrue%2C%22type%22%3A%22COMPACT%22%2C%22placeholderNames%22%3A%5B%22record_home_anchor%22%5D%2C%22skipPlaceholderDelay%22%3Atrue%2C%22stencilOverride%22%3A%22force%3AhighlightsStencilDesktop%22%2C%22updateMru%22%3Atrue%2C%22inContextOfRecordId%22%3A%220010o00002MQZk5%22%2C%22inContextOfComponent%22%3A%22force%3Ahighlights%22%7D%7D%7D%5D%7D&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AreportChart%22%3A%22".$reportChart."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2F&aura.token=".$token);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Origin: https://solarvic.force.com';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  $headers[] = 'X-Sfdc-Request-Id: 4394010000ecea3804';
  $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
  $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  $headers[] = 'Accept: */*';
  $headers[] = 'Referer: https://solarvic.force.com/industry/s/';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  $result_decode_new = json_decode($result,true);
  $recordLayoutBroker = $result_decode_new['context']['loaded']['COMPONENT@markup://force:recordLayoutBroker'];
  $outputField = $result_decode_new['context']['loaded']['COMPONENT@markup://force:outputField'];
//

//objectHome
  $objectHome_mess = array (
    'actions' => 
    array (
      0 => 
      array (
        'id' => '297;a',
        'descriptor' => 'aura://ComponentController/ACTION$getComponent',
        'callingDescriptor' => 'UNKNOWN',
        'params' => 
        array (
          'name' => 'markup://siteforce:pageLoader',
          'attributes' => 
          array (
            'pageLoadType' => 'THEME_LAYOUT',
            'themeLayoutType' => 'Inner',
          ),
        ),
      ),
      1 => 
      array (
        'id' => '298;a',
        'descriptor' => 'serviceComponent://ui.comm.runtime.components.aura.components.siteforce.controller.PubliclyCacheableComponentLoaderController/ACTION$getPageComponent',
        'callingDescriptor' => 'UNKNOWN',
        'params' => 
        array (
          'attributes' => 
          array (
            'viewId' => 'e9de2163-db71-4dbc-8da4-9eb08bcf9902',
            'routeType' => 'custom-quotes',
            'themeLayoutType' => 'Inner',
            'params' => 
            array (
              'viewid' => '04c33c33-f342-43f8-a22c-4997b0300035',
              'view_uddid' => '',
              'entity_name' => '',
              'audience_name' => '',
              'picasso_id' => '',
              'routeId' => '',
            ),
            'pageLoadType' => 'STANDARD_PAGE_CONTENT',
            'includeLayout' => true,
          ),
          'publishedChangelistNum' => 19,
          'brandingSetId' => 'f3fa8493-625a-4357-abb8-c020abd5771a',
        ),
      ),
    ),
  );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=2&aura.Component.getComponent=1&ui-comm-runtime-components-aura-components-siteforce-controller.PubliclyCacheableComponentLoader.getPageComponent=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($objectHome_mess))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AreportChart%22%3A%22".$reportChart."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3ArecordLayoutBroker%22%3A%22".$recordLayoutBroker."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Fquotes&aura.token=".$token);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Origin: https://solarvic.force.com';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  $headers[] = 'X-Sfdc-Request-Id: 5346920700001503b7';
  $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
  $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  $headers[] = 'Accept: */*';
  $headers[] = 'Referer: https://solarvic.force.com/industry/s/quotes';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  $result_decode_new = json_decode($result,true);
  $objectHome = $result_decode_new['context']['loaded']['COMPONENT@markup://forceCommunity:objectHome'];
//

//logic for create new quote
  // $genid_mess = array (
  //   'actions' => 
  //   array (
  //     0 => 
  //     array (
  //       'id' => '877;a',
  //       'descriptor' => 'apex://NewQuoteController/ACTION$newQuote',
  //       'callingDescriptor' => 'markup://c:QuotesActionBar',
  //       'params' => 
  //       array (
  //         'accountId' => '0010o00002MQZk5',
  //       ),
  //     ),
  //   ),
  // );
  // $ch = curl_init();
  // curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=3&other.NewQuote.newQuote=1');
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($genid_mess))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AreportChart%22%3A%22".$reportChart."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AoutputField%22%3A%22".$outputField."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3ArecordLayoutBroker%22%3A%22".$recordLayoutBroker."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2Fdetail&aura.token=".$token);
  // curl_setopt($ch, CURLOPT_POST, 1);
  // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  // curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
  // curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  // $headers = array();
  // $headers[] = 'Origin: https://solarvic.force.com';
  // $headers[] = 'Accept-Encoding: gzip, deflate, br';
  // $headers[] = 'Accept-Language: en-US,en;q=0.9';
  // $headers[] = 'X-Sfdc-Request-Id: 7356553000057af7a0';
  // $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
  // $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  // $headers[] = 'Accept: */*';
  // $headers[] = 'Referer: https://solarvic.force.com/industry/s/quotes';
  // $headers[] = 'Connection: keep-alive';
  // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  // $result = curl_exec($ch);
  // curl_close($ch);

  // $result_decode = json_decode($result);
  // $actions = $result_decode->actions;
  // $recordId = $actions[0]->returnValue->params->recordId;
//

//logic for gen ID
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=4&ui-comm-runtime-components-aura-components-siteforce-controller.PubliclyCacheableComponentLoader.getPageComponent=1&ui-force-components-controllers-recordGlobalValueProvider.RecordGvp.getRecord=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=%7B%22actions%22%3A%5B%7B%22id%22%3A%22618%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.comm.runtime.components.aura.components.siteforce.controller.PubliclyCacheableComponentLoaderController%2FACTION%24getPageComponent%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22attributes%22%3A%7B%22viewId%22%3A%229b25b09f-10b5-473c-a96e-d22e15433432%22%2C%22routeType%22%3A%22detail-a06%22%2C%22themeLayoutType%22%3A%22Inner%22%2C%22params%22%3A%7B%22viewid%22%3A%225e2bb3fb-82a9-40ad-952a-74baf4450bb0%22%2C%22view_uddid%22%3A%22%22%2C%22entity_name%22%3A%22%22%2C%22audience_name%22%3A%22%22%2C%22recordId%22%3A%22%22%2C%22recordName%22%3A%22%22%2C%22picasso_id%22%3A%22%22%2C%22routeId%22%3A%22%22%7D%2C%22pageLoadType%22%3A%22STANDARD_PAGE_CONTENT%22%2C%22includeLayout%22%3Atrue%7D%2C%22publishedChangelistNum%22%3A18%2C%22brandingSetId%22%3A%22f3fa8493-625a-4357-abb8-c020abd5771a%22%7D%7D%2C%7B%22id%22%3A%22619%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.force.components.controllers.recordGlobalValueProvider.RecordGvpController%2FACTION%24getRecord%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22recordDescriptor%22%3A%22".$recordId.".undefined.null.null.null.Name.VIEW.false.null.null.null%22%7D%7D%5D%7D&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AreportChart%22%3A%22".$reportChart."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AoutputField%22%3A%22".$outputField."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3ArecordLayoutBroker%22%3A%22".$recordLayoutBroker."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2Fdetail&aura.token=".$token);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  set_cookie($ch,$tmpfname);
  $headers = array();
  $headers[] = 'Pragma: no-cache';
  $headers[] = 'Origin: https://solarvic.force.com';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en,vi;q=0.9';
  $headers[] = 'X-Sfdc-Request-Id: 5208129400009bacdc';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
  $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  $headers[] = 'Accept: */*';
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/detail';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  curl_close($ch);
  $result_decode = json_decode($result);
  $records = '';
  if($records == ''){
    $globalValueProviders = $result_decode->context->globalValueProviders;
    for($i = 0; $i < count($globalValueProviders) ; $i++){
      if($globalValueProviders[$i]->type == '$Record'){
        $records = $globalValueProviders[$i]->values->records->{$recordId}->Installation__c->record->fields->Name->value;
      }
    }
  }
  $recordName = strtolower($records);
//
// get logic status
  $status_mess = array (
    'actions' => 
    array (
      0 => 
      array (
        'id' => '172;a',
        'descriptor' => 'serviceComponent://ui.force.components.controllers.recordGlobalValueProvider.RecordGvpController/ACTION$getRecord',
        'callingDescriptor' => 'UNKNOWN',
        'params' => 
        array (
          'recordDescriptor' => $recordId.'.undefined.FULL.null.null.null.VIEW.true.null.null.null',
        ),
      ),
    ),
  );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=3&ui-force-components-controllers-recordGlobalValueProvider.RecordGvp.getRecord=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($status_mess))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  $headers = array();
  $headers[] = 'Origin: https://solarvic.force.com';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  $headers[] = 'X-Sfdc-Request-Id: 1637030000ac34488c';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
  $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  $headers[] = 'Accept: */*';
  $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  if($_REQUEST['type'] != 'status'){
    $data_return_mess = array (
      'actions' => 
      array (
        0 => 
        array (
          'id' => '1513;a',
          'descriptor' => 'serviceComponent://ui.force.components.controllers.relatedList.RelatedListContainerDataProviderController/ACTION$getRecords',
          'callingDescriptor' => 'UNKNOWN',
          'params' => 
          array (
            'recordId' => $recordId,
            'relatedListApiNames' => 
            array (
              0 => 'Installed_Products__r',
              1 => 'Documentations__r',
            ),
            'numRecordsToShow' => 10,
            'showPartialCount' => true,
          ),
          'storable' => true,
        ),
        1 => 
        array (
          'id' => '1529;a',
          'descriptor' => 'serviceComponent://ui.force.components.controllers.lists.baseListView.BaseListViewController/ACTION$getRecordLayoutComponent',
          'callingDescriptor' => 'UNKNOWN',
          'params' => 
          array (
            'attributeMap' => 
            array (
              'entityName' => 'InstalledProduct__c',
              'type' => 'RELATED_LIST',
              'layoutOverride' => 'Installed_Products__r',
              'inContextOfRecordId' => $recordId,
              'inContextOfComponent' => 'force:relatedListPreviewGrid',
            ),
          ),
          'storable' => true,
        ),
        2 => 
        array (
          'id' => '1551;a',
          'descriptor' => 'serviceComponent://ui.force.components.controllers.lists.baseListView.BaseListViewController/ACTION$getRecordLayoutComponent',
          'callingDescriptor' => 'UNKNOWN',
          'params' => 
          array (
            'attributeMap' => 
            array (
              'entityName' => 'Documentation__c',
              'type' => 'RELATED_LIST',
              'layoutOverride' => 'Documentations__r',
              'inContextOfRecordId' => $recordId,
              'inContextOfComponent' => 'force:relatedListPreviewGrid',
            ),
          ),
          'storable' => true,
        ),
      ),
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=3&ui-force-components-controllers-lists-baseListView.BaseListView.getRecordLayoutComponent=2&ui-force-components-controllers-relatedList.RelatedListContainerDataProvider.getRecords=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($status_mess))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AreportChart%22%3A%22".$reportChart."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AoutputField%22%3A%22".$outputField."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."-A%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $headers = array();
    $headers[] = 'Origin: https://solarvic.force.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'X-Sfdc-Request-Id: 3087418000038d5786';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
    $headers[] = 'Connection: keep-alive';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);
  }

  $result_decode = json_decode($result);
  $globalValueProviders = $result_decode->context->globalValueProviders;
  $data_fields = array();
  for($i = 0; $i < count($globalValueProviders) ; $i++){
    if($globalValueProviders[$i]->type == '$Record'){
      $data_fields = $globalValueProviders[$i]->values->records->{$recordId}->Installation__c->record->fields;
    }
  }
  $data_return = array();
  if($data_fields->Id->value == $recordId){
    $error = '';
    $data_return = $data_fields;
  }else{
    $error = 'Can\'t get data for Solar VIC '.$recordId;
    $data_return = array();
  }

  echo json_encode(array("data" => $data_return,"error" => $error));
  die;
//
// // get recordDetail
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=5&aura.Component.getComponent=2&other.InstallationActionBar.init=1&other.InstallationLifecycle.init=1&other.NewQuote.doInit=1&ui-comm-runtime-components-aura-components-siteforce-qb.Quarterback.validateRoute=1&ui-communities-components-aura-components-forceCommunity-controller.RecordValidation.getOnLoadErrorMessage=1&ui-communities-components-aura-components-forceCommunity-recordHeadline.RecordHeadline.getInitData=1&ui-communities-components-aura-components-forceCommunity-seoAssistant.SeoAssistant.getSeoData=1&ui-force-components-controllers-recordGlobalValueProvider.RecordGvp.getRecord=1');
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, "message=%7B%22actions%22%3A%5B%7B%22id%22%3A%22901%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.force.components.controllers.recordGlobalValueProvider.RecordGvpController%2FACTION%24getRecord%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22recordDescriptor%22%3A%22".$recordId.".undefined.FULL.null.null.null.VIEW.true.null.Name.null%22%7D%7D%2C%7B%22id%22%3A%22902%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.communities.components.aura.components.forceCommunity.seoAssistant.SeoAssistantController%2FACTION%24getSeoData%22%2C%22callingDescriptor%22%3A%22markup%3A%2F%2FforceCommunity%3AseoAssistant%22%2C%22params%22%3A%7B%22recordId%22%3A%22".$recordId."%22%2C%22fields%22%3A%5B%5D%7D%2C%22version%22%3A%2246.0%22%7D%2C%7B%22id%22%3A%22907%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.communities.components.aura.components.forceCommunity.recordHeadline.RecordHeadlineController%2FACTION%24getInitData%22%2C%22callingDescriptor%22%3A%22markup%3A%2F%2FforceCommunity%3ArecordHeadline%22%2C%22params%22%3A%7B%22recordId%22%3A%22".$recordId."%22%7D%2C%22version%22%3A%2246.0%22%7D%2C%7B%22id%22%3A%22914%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.communities.components.aura.components.forceCommunity.controller.RecordValidationController%2FACTION%24getOnLoadErrorMessage%22%2C%22callingDescriptor%22%3A%22markup%3A%2F%2FforceCommunity%3ArecordHomeTabs%22%2C%22params%22%3A%7B%22recordId%22%3A%22".$recordId."%22%7D%2C%22version%22%3A%2246.0%22%7D%2C%7B%22id%22%3A%22916%3Ba%22%2C%22descriptor%22%3A%22aura%3A%2F%2FComponentController%2FACTION%24getComponent%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22name%22%3A%22markup%3A%2F%2FforceCommunity%3ArecordDetail%22%2C%22attributes%22%3A%7B%22recordId%22%3A%22".$recordId."%22%7D%7D%7D%2C%7B%22id%22%3A%22917%3Ba%22%2C%22descriptor%22%3A%22aura%3A%2F%2FComponentController%2FACTION%24getComponent%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22name%22%3A%22markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%2C%22attributes%22%3A%7B%22recordId%22%3A%22".$recordId."%22%7D%7D%7D%2C%7B%22id%22%3A%22921%3Ba%22%2C%22descriptor%22%3A%22apex%3A%2F%2FNewQuoteController%2FACTION%24doInit%22%2C%22callingDescriptor%22%3A%22markup%3A%2F%2Fc%3ANewQuote%22%2C%22params%22%3A%7B%22installationId%22%3A%22".$recordId."%22%7D%7D%2C%7B%22id%22%3A%22929%3Ba%22%2C%22descriptor%22%3A%22apex%3A%2F%2FInstallationLifecycleController%2FACTION%24init%22%2C%22callingDescriptor%22%3A%22markup%3A%2F%2Fc%3ASubmitInstallation%22%2C%22params%22%3A%7B%7D%7D%2C%7B%22id%22%3A%22935%3Ba%22%2C%22descriptor%22%3A%22apex%3A%2F%2FInstallationActionBarController%2FACTION%24init%22%2C%22callingDescriptor%22%3A%22markup%3A%2F%2Fc%3AInstallationActionBar%22%2C%22params%22%3A%7B%22pInstallationID%22%3A%22".$recordId."%22%7D%7D%2C%7B%22id%22%3A%22938%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.comm.runtime.components.aura.components.siteforce.qb.QuarterbackController%2FACTION%24validateRoute%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22routeId%22%3A%229b25b09f-10b5-473c-a96e-d22e15433432%22%2C%22viewParams%22%3A%7B%22viewid%22%3A%225e2bb3fb-82a9-40ad-952a-74baf4450bb0%22%2C%22view_uddid%22%3A%220I30o00000Bjoby%22%2C%22entity_name%22%3A%22Installation__c%22%2C%22recordId%22%3A%22".$recordId."%22%2C%22recordName%22%3A%22detail%22%2C%22picasso_id%22%3A%229b25b09f-10b5-473c-a96e-d22e15433432%22%2C%22routeId%22%3A%229b25b09f-10b5-473c-a96e-d22e15433432%22%7D%7D%7D%5D%7D&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AreportChart%22%3A%22".$reportChart."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AoutputField%22%3A%22".$outputField."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3ArecordLayoutBroker%22%3A%22".$recordLayoutBroker."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//   curl_setopt($ch, CURLOPT_POST, 1);
//   curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//   curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//   curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//   $headers = array();
//   $headers[] = 'Origin: https://solarvic.force.com';
//   $headers[] = 'Accept-Encoding: gzip, deflate, br';
//   $headers[] = 'Accept-Language: en-US,en;q=0.9';
//   $headers[] = 'X-Sfdc-Request-Id: 74727370000afb4a1b';
//   $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
//   $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//   $headers[] = 'Accept: */*';
//   $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//   $headers[] = 'Connection: keep-alive';
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//   $result = curl_exec($ch);
//   curl_close($ch);

//   $result_decode = json_decode($result,true);
//   $recordDetail =$result_decode['context']['loaded']['COMPONENT@markup://forceCommunity:recordDetail'];
//   $relatedRecords = $result_decode['context']['loaded']['COMPONENT@markup://forceCommunity:relatedRecords'];
// //

// //Agreement term
//   $accept_conditions = array (
//     'actions' => 
//     array (
//       0 => 
//       array (
//         'id' => '998;a',
//         'descriptor' => 'apex://NewQuoteController/ACTION$saveInstallation',
//         'callingDescriptor' => 'markup://c:NewQuote',
//         'params' => 
//         array (
//           'theInstallation' => 
//           array (
//                 'Id' => $recordId,
//                 'Name' => strtoupper($recordName),
//                 'Interest_Free_Loan__c' => false,
//                 'Account__c' => '0010o00002MQZk5AAH',
//                 'Landlord__c' => false,
//                 'Owner_Occupier__c' => false,
//                 'Quote_Expiry_Date__c' =>  date('Y-m-d', strtotime('+90 days')),
//                 'Quote_Final_Agreement__c' => false,
//                 'Status__c' => 'New Quote',
//                 'Retailer_Address__c' => 'Solargain PV Pty Ltd<br><br>7/88 Dynon Road<br>West Melbourne<br>3003',
//                 'Understand_SPS_T_Cs__c' => true,
//                 'CoC_216e_Accepted__c' => false,
//                 'Grid_Connect_Pre_Approval__c' => false,
//                 'Account__r' => 
//                 array (
//                   'Name' => 'Solargain PV Pty Ltd',
//                   'Id' => '0010o00002MQZk5AAH',
//                 ),
//                 'Quote_Rebate_Amount__c' => 0,
//                 'Quote_Loan_Amount__c' => 0,
//                 'Quote_Net_Amount__c' => 0,
//                 'Rebate_Type__c' => '',
//               ),
//             ),
//           ),
//         ),
//       );
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=6&other.NewQuote.saveInstallation=1');
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($accept_conditions))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordID."%2F".$recordName."&aura.token=".$token);
//   curl_setopt($ch, CURLOPT_POST, 1);
//   curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//   curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//   curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//   $headers = array();
//   $headers[] = 'Pragma: no-cache';
//   $headers[] = 'Origin: https://solarvic.force.com';
//   $headers[] = 'Accept-Encoding: gzip, deflate, br';
//   $headers[] = 'Accept-Language: en,vi;q=0.9';
//   $headers[] = 'X-Sfdc-Request-Id: 17875110000e572529';
//   $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
//   $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//   $headers[] = 'Accept: */*';
//   $headers[] = 'Cache-Control: no-cache';
//   $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//   $headers[] = 'Connection: keep-alive';
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//   $result = curl_exec($ch);
//   curl_close($ch);
// //
//     //get address
//     $ch = curl_init();
//     $data1 = array (
//               'actions' => 
//               array (
//                   0 => 
//                   array (
//                   'id' => '1;a',
//                   'descriptor' => 'apex://NewQuoteController/ACTION$lookupAddress',
//                   'callingDescriptor' => 'UNKNOWN',
//                   'params' => 
//                   array (
//                       'searchTerm' => $data_request['slv_installation_address_c'],
//                       'selectedIds' => 
//                       array (
//                       ),
//                   ),
//                   'storable' => true,
//                   ),
//               ),
//             );
//     $data1 = urlencode(json_encode($data1));
//     curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=6&other.NewQuote.lookupAddress=1');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$data1."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

//     $headers = array();
//     $headers[] = 'Origin: https://solarvic.force.com';
//     $headers[] = 'Accept-Encoding: gzip, deflate, br';
//     $headers[] = 'Accept-Language: en-US,en;q=0.9';
//     $headers[] = 'X-Sfdc-Request-Id: 2920046990000658e1';
//     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
//     $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//     $headers[] = 'Accept: */*';
//     $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//     $headers[] = 'Connection: keep-alive';
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//     $result = curl_exec($ch);
//     curl_close($ch);

//     $result_decode = json_decode($result);
//     $actions = $result_decode->actions;
//     for($i = 0; $i < count($actions); $i++){
//       if(count($actions[$i]->returnValue)> 0){
//         if($actions[$i]->returnValue[0]->id){
//           $addressID = $actions[$i]->returnValue[0]->id;
//           break;
//         }
//       }
//     }          
//     $data2 = array (
//         'actions' => 
//         array (
//           0 => 
//           array (
//             'id' => '2;a',
//             'descriptor' => 'apex://NewQuoteController/ACTION$getAddressDetails',
//             'callingDescriptor' => 'markup://c:NewQuote',
//             'params' => 
//             array (
//               'addressId' => $addressID,
//             ),
//           ),
//         ),
//     );
//     $ch = curl_init();
//     $data2 = urlencode(json_encode($data2));
//     curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=7&other.NewQuote.getAddressDetails=1');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$data2."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

//     $headers = array();
//     $headers[] = 'Origin: https://solarvic.force.com';
//     $headers[] = 'Accept-Encoding: gzip, deflate, br';
//     $headers[] = 'Accept-Language: en-US,en;q=0.9';
//     $headers[] = 'X-Sfdc-Request-Id: 292736498000026d7b';
//     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
//     $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//     $headers[] = 'Accept: */*';
//     $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//     $headers[] = 'Connection: keep-alive';
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//     $result = curl_exec($ch);
//     curl_close($ch);

//     $result_decode = json_decode($result);
//     $actions = $result_decode->actions;
//     $addressID = $actions[0]->returnValue->params->Property->Id;
//     $Address_Line_API__c = $actions[0]->returnValue->params->Property->Address_Line_API__c;
//     $postCode__c = $actions[0]->returnValue->params->Property->postCode__c;

//     // get product
//     $invert_type_name = array(
//       'Primo 4'=>'Primo 4.0-1',
//       'Primo 5'=>'Primo 5.0-1',
//       'Primo 6'=>'Primo 6.0-1',
//       'Primo 8.2'=>'Primo 8.2-1',
//       'Symo 5'=>'Symo 5.0-3-M',
//       'Symo 6'=>'Symo 6.0-3-M',
//       'Symo 8.2'=>'Symo 8.2-3-M',
//       'Symo 10'=>'Symo 10.0-3-M',
//       'S Edge 5'=>'SE5000H',
//       'S Edge 6'=>'SE6000H',
//       'S Edge 10'=>'SE10000H',
//       'ENP IQ7 plus'=>'ENP IQ7 plus',
//       'ENP IQ7'=>'ENP IQ7',
//       'Growatt 5'=>'GROWATT 5500MTL-S',
//       'Sungrow 5'=>'Sungrow 5',
//       'Sungrow 5 3P'=>'ungrow 5 3P',
//       'Huawei 5'=>'Huawei 5');

//     $invert_type = array (
//         'actions' => 
//         array (
//           0 => 
//           array (
//             'id' => '3;a',
//             'descriptor' => 'apex://NewQuoteController/ACTION$searchApprovedProducts',
//             'callingDescriptor' => 'markup://c:InsalledProductRow',
//             'params' => 
//             array (
//               'searchTerm' => $invert_type_name[$data_request['slv_inverter_type_c']],
//               'selectedIds' => 
//               array (
//               ),
//             ),
//             'version' => NULL,
//             'storable' => true,
//           ),
//         ),
//     );
//     $invert_type = urlencode(json_encode($invert_type));
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=8&other.NewQuote.searchApprovedProducts=1');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$invert_type."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

//     $headers = array();
//     $headers[] = 'Origin: https://solarvic.force.com';
//     $headers[] = 'Accept-Encoding: gzip, deflate, br';
//     $headers[] = 'Accept-Language: en-US,en;q=0.9';
//     $headers[] = 'X-Sfdc-Request-Id: 38548430000e923fb3';
//     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
//     $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//     $headers[] = 'Accept: */*';
//     $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//     $headers[] = 'Connection: keep-alive';
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//     $result = curl_exec($ch);
//     curl_close($ch);

//     $result_decode = json_decode($result);
//     $actions = $result_decode->actions;
//     $product1 = $actions[0]->returnValue[0]->id;

//     switch($data_request['slv_panel_type_c']){
//       case 'Q CELLS Q.PEAK DUO 330W': 
//         $panel_type_suite = "Q.PEAK DUO-G5 330";
//         break; 
//       case 'Jinko 315W Cheetah Mono PERC':
//         $panel_type_suite = "JKM315M-60";
//         break;
//       case 'Jinko 275W':
//         $panel_type_suite = "Jinko 275W";
//         break;
//       case 'LG NeON 2 345W':
//         $panel_type_suite = "LG345N1C-V5";
//         break;
//       case 'Sunpower P19 320 BLACK':
//         $panel_type_suite = "SPR-P19-320-BLK";
//         break;
//       case 'Sunpower Maxeon 3 400':
//         $panel_type_suite = "SPR-MAX3-400";
//         break;
//       case 'Sunpower Maxeon 2 350':
//         $panel_type_suite = "SPR-MAX2-350";
//         break;
//     }
//     $panel_type = array (
//       'actions' => 
//       array (
//         0 => 
//         array (
//           'id' => '4;a',
//           'descriptor' => 'apex://NewQuoteController/ACTION$searchApprovedProducts',
//           'callingDescriptor' => 'markup://c:InsalledProductRow',
//           'params' => 
//           array (
//             'searchTerm' => $panel_type_suite,
//             'selectedIds' => 
//             array (
//             ),
//           ),
//           'version' => NULL,
//           'storable' => true,
//         ),
//       ),
//     );
//     $panel_type = urlencode(json_encode($panel_type));
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=9&other.NewQuote.searchApprovedProducts=1');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$panel_type."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//     $headers = array();
//     $headers[] = 'Origin: https://solarvic.force.com';
//     $headers[] = 'Accept-Encoding: gzip, deflate, br';
//     $headers[] = 'Accept-Language: en-US,en;q=0.9';
//     $headers[] = 'X-Sfdc-Request-Id: 38548430000e923fb3';
//     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
//     $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//     $headers[] = 'Accept: */*';
//     $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//     $headers[] = 'Connection: keep-alive';
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//     $result = curl_exec($ch);
//     curl_close($ch);

//     $result_decode = json_decode($result);
//     $actions = $result_decode->actions;
//     $product2 = $actions[0]->returnValue[0]->id;

//     // Q.PEAK DUO-G5 330
//     $data_mess_string = array (
//       'actions' => 
//       array (
//         0 => 
//         array (
//           'id' => '1100;a',
//           'descriptor' => 'apex://NewQuoteController/ACTION$saveInstallation',
//           'callingDescriptor' => 'markup://c:NewQuote',
//           'params' => 
//           array (
//             'theInstallation' => 
//             array (
//               'Id' => $recordId,
//               'Name' => $recordName,
//               'Interest_Free_Loan__c' => ($data_request['slv_interested_solar_loan_c'] == 'false') ? false : true,
//               'Rebate_Type__c' => $data_request['slv_ebate_type_c'],
//               'Account__c' => '0010o00002MQZk5AAH',
//               'Landlord__c' => ($data_request['slv_ebate_type_c'] == 'Solar PV (Landlord)')? true : false,
//               'Owner_Occupier__c' => ($data_request['slv_ebate_type_c'] == 'Solar PV (Owner Occupier)')? true : false,
//               'Quote_Expiry_Date__c' => date('Y-m-d', strtotime('+90 days')),
//               'Quote_Final_Agreement__c' => false,
//               'Status__c' => 'New Quote',
//               'Quote_Number__c' => $data_request['slv_quote_sg_number_c'],
//               'Quote_Amount__c' => floatval($data_request['slv_total_price_c']),
//               'First_Name__c' => $data_request['slv_firstname_c'],
//               'Last_Name__c' => $data_request['slv_lastname_c'],
//               'Retailer_Address__c' => 'Solargain PV Pty Ltd<br><br>7/88 Dynon Road<br>West Melbourne<br>3003',
//               'Email__c' => $data_request['slv_email_c'],
//               'Understand_SPS_T_Cs__c' => true,
//               'CoC_216e_Accepted__c' => ($data_request['customer_benefits_c'] == 'false') ? false : true,
//               'Quote_STC_Amount__c' => floatval($data_request['slv_estimated_value_c']),
//               'Grid_Connect_Pre_Approval__c' =>  ($data_request['slv_dnsp_approval_c'] == 'false') ? false : true,
//               'Account__r' => 
//               array (
//                 'Name' => 'Solargain PV Pty Ltd',
//                 'Id' => '0010o00002MQZk5AAH',
//               ),
//               'property__c' => $addressID,
//               'Quote_Rebate_Amount__c' => 2225,
//               'Quote_Loan_Amount__c' => 0,
//               'Quote_Net_Amount__c' => floatval($data_request['slv_net_payable_c']),
//             ),
//           ),
//         ),
//         1 => 
//         array (
//           'id' => '4700;a',
//           'descriptor' => 'apex://NewQuoteController/ACTION$saveInstalledProducts',
//           'callingDescriptor' => 'markup://c:InstalledProductList',
//           'params' => 
//           array (
//             'products' => 
//             array (
//               0 => 
//               array (
//                 'sobjectType' => 'InstalledProduct__c',
//                 'authorisedProduct__c' => $product1,
//                 'installation__c' => $recordId,
//                 'Quantity__c' => '1',
//                 'Status__c' => 'Pending Installation',
//                 'IsDeleted' => false,
//                 'Index__c' => 1,
//               ),
//               1 => 
//               array (
//                 'sobjectType' => 'InstalledProduct__c',
//                 'authorisedProduct__c' =>  $product2,
//                 'installation__c' => $recordId,
//                 'Quantity__c' => $data_request['slv_total_panel_c'],
//                 'Status__c' => 'Pending Installation',
//                 'IsDeleted' => false,
//                 'Index__c' => 2,
//               ),
//             ),
//           ),
//         ),
//       ),
//     );
//     if($data_request['customer_benefits_c'] == 'true'){
//       $data_mess_string['actions'][0]['params']['theInstallation']['Quote_Estimated_Daily_Yield__c'] = floatval($data_request['estimate_energy_yield_c']);
//       $data_mess_string['actions'][0]['params']['theInstallation']['Estimated_Financial_Saving__c'] = floatval($data_request['estimated_financial_saving_c']);
//     }
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=10&other.NewQuote.saveInstallation=1&other.NewQuote.saveInstalledProducts=2');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($data_mess_string))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//     $headers = array();
//     $headers[] = 'Pragma: no-cache';
//     $headers[] = 'Origin: https://solarvic.force.com';
//     $headers[] = 'Accept-Encoding: gzip, deflate, br';
//     $headers[] = 'Accept-Language: en,vi;q=0.9';
//     $headers[] = 'X-Sfdc-Request-Id: 14658571490000063a';
//     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
//     $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//     $headers[] = 'Accept: */*';
//     $headers[] = 'Cache-Control: no-cache';
//     $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//     $headers[] = 'Connection: keep-alive';
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
//     $result = curl_exec($ch);
//     curl_close($ch);

//     $crm_quoteID = $data_request['crm_quoteID'];
//     $sg_quote =  $data_request['slv_quote_sg_number_c'];
//     $quote = new AOS_Quotes();
//     $quote = $quote->retrieve($crm_quoteID);
//     $content_pdf = '';
//     $filename = '';
//     if($quote->id !=''){
//       $generate_ID = $quote->pre_install_photos_c;
//       if($generate_ID) {
//         $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;
//         $files = scandir($folder);
//         foreach($files as $file) {
//           if(strpos($file, 'Quote_#'.$sg_quote) !== false) {
//               $content_pdf  = file_get_contents($folder.'/'.$file);
//               $filename = $file;
//               break;
//           }
//         }
//       }
//     }

//     if( $content_pdf != ''){
//     // get fileUploadAction  
//       $data_mess = array (
//         'actions' => 
//         array (
//           0 => 
//           array (
//             'id' => '847;a',
//             'descriptor' => 'aura://ComponentController/ACTION$getComponent',
//             'callingDescriptor' => 'UNKNOWN',
//             'params' => 
//             array (
//               'name' => 'markup://forceContent:fileUploadAction',
//               'attributes' => 
//               array (
//                 'parentRecordId' => $recordId,
//                 'accept' => '.pdf, .png, .jpg, .jpeg, .doc, .docx, .xls, .xlsx',
//                 'disabled' => false,
//                 'multiple' => false,
//                 'onError' => 'a=>{a&&this.connected&&(this.inputElement.setCustomValidity(a),this.inputElement.showHelpMessageIfInvalid())}',
//                 'onUpload' => 'a=>{this.connected&&(this.inputElement.setCustomValidity(""),this.inputElement.showHelpMessageIfInvalid(),this.dispatchEvent(new CustomEvent("uploadfinished",{detail:a})))}',
//               ),
//             ),
//           ),
//         ),
//       );
//       $ch = curl_init();
//       curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=11&aura.Component.getComponent=1');
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($data_mess))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AoutputField%22%3A%22".$outputField."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       $headers = array();
//       $headers[] = 'Origin: https://solarvic.force.com';
//       $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       $headers[] = 'X-Sfdc-Request-Id: 84457050000925830f';
//       $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
//       $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//       $headers[] = 'Accept: */*';
//       $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//       $headers[] = 'Connection: keep-alive';
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//       $result = curl_exec($ch);
//       curl_close($ch);
//       $result_decode = json_decode($result,true);
//       $fileUploadAction =$result_decode['context']['loaded']['COMPONENT@markup://forceContent:fileUploadAction'];
//     //
//     //
//       $ch = curl_init();
//       curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=21&ui-chatter-components-aura-components-forceChatter-chatter.PublisherFileAttachment.getFileUploaderParams=1');
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS, "message=%7B%22actions%22%3A%5B%7B%22id%22%3A%221339%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.chatter.components.aura.components.forceChatter.chatter.PublisherFileAttachmentController%2FACTION%24getFileUploaderParams%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%7D%7D%5D%7D&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AoutputField%22%3A%22".$outputField."%22%2C%22COMPONENT%40markup%3A%2F%2FforceContent%3AfileUploadAction%22%3A%22".$fileUploadAction."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordID."%2F".$recordName."&aura.token=".$token);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       $headers = array();
//       $headers[] = 'Origin: https://solarvic.force.com';
//       $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       $headers[] = 'X-Sfdc-Request-Id: 22277544800005601e';
//       $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
//       $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//       $headers[] = 'Accept: */*';
//       $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//       $headers[] = 'Connection: keep-alive';
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//       $result = curl_exec($ch);
//       curl_close($ch);

//       $result_decode = json_decode($result);
//       $actions = $result_decode->actions;
//       $csrfToken = $actions[0]->returnValue->csrfToken;

//       $eol = "\r\n";
//       $BOUNDARY = md5(time());
//       $BODY="";
//       $BODY.= '-----------------------------'.$BOUNDARY. $eol;
//       $BODY .= 'Content-Disposition: form-data; name="token"' . $eol . $eol;
//       $BODY .= $csrfToken . $eol;
//       $BODY.= '-----------------------------'.$BOUNDARY. $eol;
//       $BODY.= 'Content-Disposition: form-data; name="fromUITier"'.$eol . $eol;
//       $BODY.= 'true'. $eol;
//       $BODY.= '-----------------------------'.$BOUNDARY. $eol;
//       $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
//       $BODY.= 'Content-Type: application/pdf'. $eol. $eol;
//       $BODY.= $content_pdf. $eol;
//       $BODY.= '-----------------------------'.$BOUNDARY. $eol;
//       $BODY.= 'Content-Disposition: form-data; name="target"'.$eol . $eol;
//       $BODY.= 'ContentVersion'. $eol;
//       $BODY.= '-----------------------------'.$BOUNDARY.'--'. $eol;
      
//       //echo $BODY;
      
            
//       $ch = curl_init();
//       curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/chatter/handlers/file/body');
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS,$BODY);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       $headers = array();
//       $headers[] = 'Origin: https://solarvic.force.com';
//       $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
//       $headers[] = "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY;
//       $headers[] = 'Accept: */*';
//       $headers[] = 'Content-Length: ' .strlen($BODY);
//       $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//       $headers[] = 'Connection: keep-alive';
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//       $result = curl_exec($ch);
//       curl_close($ch);

//       $pattern = '/"content_body_id":"(.*?)"}/';
//       $returnValue = preg_match($pattern, $result, $matches);
//       $content_body_id = '';
//       if($matches[1]!=''){
//         $content_body_id =  urlencode($matches[1]);
//       }
//       $ch = curl_init();
//       curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=17&ui-chatter-components-aura-components-forceChatter-chatter.PublisherFileAttachment.saveContentVersion=1');
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS, "message=%7B%22actions%22%3A%5B%7B%22id%22%3A%221291%3Ba%22%2C%22descriptor%22%3A%22serviceComponent%3A%2F%2Fui.chatter.components.aura.components.forceChatter.chatter.PublisherFileAttachmentController%2FACTION%24saveContentVersion%22%2C%22callingDescriptor%22%3A%22UNKNOWN%22%2C%22params%22%3A%7B%22title%22%3A%22".str_replace(".pdf","",$filename)."%22%2C%22firstPublishLocationId%22%3A%22".$recordId."%22%2C%22pathOnClient%22%3A%22".$filename."%22%2C%22contentBodyId%22%3A%22".$content_body_id."%22%7D%7D%5D%7D&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3AobjectHome%22%3A%22".$objectHome."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%2C%22COMPONENT%40markup%3A%2F%2FforceContent%3AfileUploadAction%22%3A%22".$fileUploadAction."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       $headers = array();
//       $headers[] = 'Origin: https://solarvic.force.com';
//       $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       $headers[] = 'X-Sfdc-Request-Id: 22287358000001de4d';
//       $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
//       $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//       $headers[] = 'Accept: */*';
//       $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//       $headers[] = 'Connection: keep-alive';
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//       $result = curl_exec($ch);
//       curl_close($ch);

     

      
//       $result_decode = json_decode($result);
//       $actions = $result_decode->actions;
//       $docID = $actions[0]->returnValue->docid;

//       $document = array (
//         'actions' => 
//         array (
//           0 => 
//           array (
//             'id' => '11155;a',
//             'descriptor' => 'apex://UploadInstallationDocumentsController/ACTION$updateDoc',
//             'callingDescriptor' => 'markup://c:uploadInstallationDocuments',
//             'params' => 
//             array (
//               'pFileId' => $docID,
//               'pDescription' => 'Quote',
//             ),
//           ),
//         ),
//       );
//       $ch = curl_init();
//       curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=13&other.UploadInstallationDocuments.updateDoc=1');
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($document))."&aura.context=%7B%22mode%22%3A%22PROD%22%2C%22fwuid%22%3A%22".$fwuid."%22%2C%22app%22%3A%22siteforce%3AcommunityApp%22%2C%22loaded%22%3A%7B%22APPLICATION%40markup%3A%2F%2Fsiteforce%3AcommunityApp%22%3A%22".$communityApp."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArecordDetail%22%3A%22".$recordDetail."%22%2C%22COMPONENT%40markup%3A%2F%2FforceCommunity%3ArelatedRecords%22%3A%22".$relatedRecords."%22%2C%22COMPONENT%40markup%3A%2F%2Fforce%3AsocialPhotoController%22%3A%2262Gpg5wuG9-WbsvVirqERg%22%2C%22COMPONENT%40markup%3A%2F%2FforceContent%3AfileUploadAction%22%3A%22".$fileUploadAction."%22%7D%2C%22dn%22%3A%5B%5D%2C%22globals%22%3A%7B%22density%22%3A%22VIEW_ONE%22%7D%2C%22uad%22%3Afalse%7D&aura.pageURI=%2Findustry%2Fs%2Finstallation%2F".$recordId."%2F".$recordName."&aura.token=".$token);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       $headers = array();
//       $headers[] = 'Origin: https://solarvic.force.com';
//       $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       $headers[] = 'X-Sfdc-Request-Id: 677390064000041a11';
//       $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
//       $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//       $headers[] = 'Accept: */*';
//       $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//       $headers[] = 'Connection: keep-alive';
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//       $result = curl_exec($ch);
//       curl_close($ch);

//       // $product_action = array (
//       //   'actions' => 
//       //   array (
//       //     0 => 
//       //     array (
//       //       'id' => '8964;a',
//       //       'descriptor' => 'apex://NewQuoteController/ACTION$getInstalledProducts',
//       //       'callingDescriptor' => 'markup://c:InstalledProductList',
//       //       'params' => 
//       //       array (
//       //         'intallationId' => $recordId,
//       //       ),
//       //     ),
//       //     1 => 
//       //     array (
//       //       'id' => '8988;a',
//       //       'descriptor' => 'apex://UploadInstallationDocumentsController/ACTION$init',
//       //       'callingDescriptor' => 'markup://c:uploadInstallationDocuments',
//       //       'params' => 
//       //       array (
//       //         'pInstallationID' => $recordId,
//       //         'stage' => 'Quote',
//       //       ),
//       //     ),
//       //   ),
//       // );      
//       // $ch = curl_init();
//       // curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=14&other.NewQuote.getInstalledProducts=1&other.UploadInstallationDocuments.init=1');
//       // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       // curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($product_action))."&aura.context=^%^7B^%^22mode^%^22^%^3A^%^22PROD^%^22^%^2C^%^22fwuid^%^22^%^3A^%^22".$fwuid."^%^22^%^2C^%^22app^%^22^%^3A^%^22siteforce^%^3AcommunityApp^%^22^%^2C^%^22loaded^%^22^%^3A^%^7B^%^22APPLICATION^%^40markup^%^3A^%^2F^%^2Fsiteforce^%^3AcommunityApp^%^22^%^3A^%^22".$communityApp."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3AobjectHome^%^22^%^3A^%^22".$objectHome."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3ArecordDetail^%^22^%^3A^%^22".$recordDetail."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3ArelatedRecords^%^22^%^3A^%^22".$relatedRecords."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2Fforce^%^3ApreviewPanel^%^22^%^3A^%^22GHEtEp6s-K0-GRMAF_hF2Q^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3ArelatedList^%^22^%^3A^%^22gRO3RliS3FFRAssiSSK77Q^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3AreportChart^%^22^%^3A^%^22".$reportChart."^%^22^%^7D^%^2C^%^22dn^%^22^%^3A^%^5B^%^5D^%^2C^%^22globals^%^22^%^3A^%^7B^%^22density^%^22^%^3A^%^22VIEW_ONE^%^22^%^7D^%^2C^%^22uad^%^22^%^3Afalse^%^7D^&aura.pageURI=^%^2Findustry^%^2Fs^%^2Fquotes^&aura.token=".$token);
//       // curl_setopt($ch, CURLOPT_POST, 1);
//       // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       // curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       // curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       // $headers = array();
//       // $headers[] = 'Origin: https://solarvic.force.com';
//       // $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       // $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       // $headers[] = 'X-Sfdc-Request-Id: 20403293500007085d';
//       // $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
//       // $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//       // $headers[] = 'Accept: */*';
//       // $headers[] = 'Referer: https://solarvic.force.com/industry/s/quotes';
//       // $headers[] = 'Connection: keep-alive';
//       // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//       // $result = curl_exec($ch);
//       // curl_close($ch);

//       // $result_decode = json_decode($result);
//       // $actions = $result_decode->actions;
//       // $Product_id1 = $actions[0]->returnValue->params->Products[0]->Id;
//       // $Product_id2 = $actions[0]->returnValue->params->Products[1]->Id;


//       // $data_save_end = array (
//       //   array (
//       //     'actions' => 
//       //     array (
//       //       0 => 
//       //       array (
//       //         'id' => '660;a',
//       //         'descriptor' => 'apex://NewQuoteController/ACTION$saveInstallation',
//       //         'callingDescriptor' => 'markup://c:NewQuote',
//       //         'params' => 
//       //         array (
//       //           'theInstallation' => 
//       //           array (
//       //             'Id' => $recordId,
//       //             'Name' => $recordName,
//       //             'Interest_Free_Loan__c' => false,
//       //           'Rebate_Type__c' => $data_request['slv_ebate_type_c'],
//       //             'Account__c' => '0010o00002MQZk5AAH',
//       //           'Landlord__c' => ($data_request['slv_ebate_type_c'] == 'Solar PV (Landlord)')? true : false,
//       //           'Owner_Occupier__c' => ($data_request['slv_ebate_type_c'] == 'Solar PV (Owner Occupier)')? true : false,
//       //             'Quote_Expiry_Date__c' => date('Y-m-d', strtotime('+90 days')),
//       //             'Quote_Final_Agreement__c' => false,
//       //             'Status__c' => 'New Quote',
//       //           'Quote_Number__c' => $data_request['slv_quote_sg_number_c'],
//       //           'Quote_Amount__c' => floatval($data_request['slv_total_price_c']),
//       //             'property__c' =>  $addressID,
//       //           'First_Name__c' => $data_request['slv_firstname_c'],
//       //           'Last_Name__c' => $data_request['slv_lastname_c'],
//       //             'Retailer_Address__c' => 'Solargain PV Pty Ltd<br><br>7/88 Dynon Road<br>West Melbourne<br>3003',
//       //           'Email__c' => $data_request['slv_email_c'],
//       //             'Understand_SPS_T_Cs__c' => true,
//       //           'CoC_216e_Accepted__c' =>($data_request['customer_benefits_c'] == 'false') ? false : true,
//       //           'Quote_STC_Amount__c' => floatval($data_request['slv_estimated_value_c']),
//       //           'Grid_Connect_Pre_Approval__c' => true,
//       //             'Account__r' => 
//       //             array (
//       //               'Name' => 'Solargain PV Pty Ltd',
//       //               'Id' => '0010o00002MQZk5AAH',
//       //             ),
//       //             'property__r' => 
//       //             array (
//       //               'Address_Line_API__c' => $Address_Line_API__c,
//       //               'postCode__c' => $postCode__c,
//       //               'Id' => $addressID,
//       //             ),
//       //             'Quote_Rebate_Amount__c' => 2225,
//       //             'Quote_Loan_Amount__c' => 0,
//       //           'Quote_Net_Amount__c' => floatval($data_request['slv_net_payable_c']),
//       //           ),
//       //         ),
//       //       ),
//       //       1 => 
//       //       array (
//       //         'id' => '662;a',
//       //         'descriptor' => 'apex://NewQuoteController/ACTION$saveInstalledProducts',
//       //         'callingDescriptor' => 'markup://c:InstalledProductList',
//       //         'params' => 
//       //         array (
//       //           'products' => 
//       //           array (
//       //             0 => 
//       //             array (
//       //               'Id' => $Product_id1,
//       //               'installation__c' => $recordId,
//       //               'authorisedProduct__c' =>  $product1,
//       //               'Quantity__c' => 1,
//       //               'Status__c' => 'Pending Installation',
//       //               'IsDeleted' => false,
//       //               'Index__c' => 1,
//       //             ),
//       //             1 => 
//       //             array (
//       //               'Id' => $Product_id2,
//       //               'installation__c' => $recordId,
//       //               'authorisedProduct__c' =>  $product2,
//       //               'Quantity__c' => $data_request['slv_total_panel_c'],
//       //               'Status__c' => 'Pending Installation',
//       //               'IsDeleted' => false,
//       //               'Index__c' => 2,
//       //             ),
//       //           ),
//       //         ),
//       //       ),
//       //     ),
//       //   )); 
      

//       // $ch = curl_init();
//       // curl_setopt($ch, CURLOPT_URL, 'https://solarvic.force.com/industry/s/sfsites/aura?r=23&other.NewQuote.saveInstallation=1&other.NewQuote.saveInstalledProducts=1');
//       // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//       // curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".urlencode(json_encode($data_save_end))."&aura.context=^%^7B^%^22mode^%^22^%^3A^%^22PROD^%^22^%^2C^%^22fwuid^%^22^%^3A^%^22".$fwuid."^%^22^%^2C^%^22app^%^22^%^3A^%^22siteforce^%^3AcommunityApp^%^22^%^2C^%^22loaded^%^22^%^3A^%^7B^%^22APPLICATION^%^40markup^%^3A^%^2F^%^2Fsiteforce^%^3AcommunityApp^%^22^%^3A^%^22".$communityApp."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3AobjectHome^%^22^%^3A^%^224JMOFR4zk9bTatPjvpIf0A^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3ArecordDetail^%^22^%^3A^%^22".$recordDetail."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceCommunity^%^3ArelatedRecords^%^22^%^3A^%^22".$relatedRecords."^%^22^%^2C^%^22COMPONENT^%^40markup^%^3A^%^2F^%^2FforceContent^%^3AfileUploadAction^%^22^%^3A^%^22gADlU76vyacEshCjSeWE1g^%^22^%^7D^%^2C^%^22dn^%^22^%^3A^%^5B^%^5D^%^2C^%^22globals^%^22^%^3A^%^7B^%^22density^%^22^%^3A^%^22VIEW_ONE^%^22^%^7D^%^2C^%^22uad^%^22^%^3Afalse^%^7D^&aura.pageURI=^%^2Findustry^%^2Fs^%^2Finstallation^%^2F".$recordId."^%^2F".$recordName."^&aura.token=".$token);
//       // curl_setopt($ch, CURLOPT_POST, 1);
//       // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//       // curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//       // curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//       // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//       // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//       // $headers = array();
//       // $headers[] = 'Origin: https://solarvic.force.com';
//       // $headers[] = 'Accept-Encoding: gzip, deflate, br';
//       // $headers[] = 'Accept-Language: en-US,en;q=0.9';
//       // $headers[] = 'X-Sfdc-Request-Id: 749228941000081b2d';
//       // $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
//       // $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
//       // $headers[] = 'Accept: */*';
//       // $headers[] = 'Referer: https://solarvic.force.com/industry/s/installation/'.$recordId.'/'.$recordName;
//       // $headers[] = 'Connection: keep-alive';
//       // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//       // $result = curl_exec($ch);
//       // curl_close($ch);
//     }
//     $data_return = array();
//     $data_return['recordId'] = $recordId;
//     $data_return['recordName'] = $recordName;
//     $data_return['status'] = $status;
//     $data_return['error'] = '';
//     echo json_encode($data_return);
//   die;
?>
