<?php 
    // $templateId = $_REQUEST('template_id');
    $smsTemplate = BeanFactory::getBean(
        'pe_smstemplate',
        '4cfa35e8-c49c-6b3c-b6de-5de8b14da844' 
        // 'a36070a9-e51b-f1a7-8d7e-5d96adaf4300'
    );
    $body = trim(strip_tags(html_entity_decode(parse_sms_template($smsTemplate),ENT_QUOTES)));

    echo $body;

    function parse_sms_template($smsTemplate)
    {
        $body =  $smsTemplate->body_c;
        // $body = str_replace("\$first_name", $first_name, $body);
        return $body;
    }