<link rel="stylesheet" href="https://suitecrm.pure-electric.com.au/cache/themes/SuiteP/css/Dawn/style.css">
<script
src="https://code.jquery.com/jquery-3.4.1.js"
integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
crossorigin="anonymous"></script>
<style>
    body {
        background: white;
    } 
    .input-group {
        display: block;
        margin-bottom: 20px;
    }
    .area-upload {
        display: inline-block;
        width: 100%;
        padding: 200px 0 0 0;
        height: 200px;
        overflow: hidden;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background: url('themes/SuiteP/images/upload-form.png') center center no-repeat;
        border-radius: 20px;
        background-size: 60px 60px;
        border: 2px dashed gray;
    }
    .drap-file {
        width: 1000px;
        margin: 40px auto;
        text-align: center;
    }
    .drap-file h3 {
        text-align: left;
    }
    .drap-file .imageForm {
        max-width: 65%;
        margin: 0 auto;
    }
    .text-area-form {
        width: 100%;
        height: 100px;
    }
    .formGroup {
        width: 1000px;
        margin: 0 auto 20px;
    }
    .formGroup h2 {
        text-align: center;
    }
    .submitForm {
        width: 200px;
        margin: 0 auto;
    }
    .imageSingle {
        height: 215px;
        overflow: hidden;
        cursor: pointer;
        text-align: right;
    }
    .backgroundPopup {
        width: 100%;
        height: 100%;
        position: fixed;
        background: black;
        opacity: 0.7;
        top: 0;
        left: 0;
        display: none;
    }
    .previewImage {
        position: fixed;
        z-index: 2;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }
    .previewImage .closePopup {
        width: 40px;
        position: absolute;
        right: -40px;
        top: -40px;
        cursor: pointer;
    }
    .previewImage .previewTarget{
        width: 900px;
    }
    .imgPrev {
        max-width: 70px;
    }
    .row-item-prev {
        border-radius: 10px;
        padding: 5px 5px;
        text-align: left;
        position: relative;
    }
    .controlFile {
        display: none;
        position: absolute;
        right: 10px;
        top: 13px;
    }
    .removeFile, .editFile {
        width: 20px;
        cursor: pointer;
        display: inline-block;
        margin: 0 10px
    }
    .nameFile {
        margin-left: 20px;
        font-size: 15px;
    }
    .editFile {
        opacity: 0.5;
        cursor: auto;
    }
    .errorToken {
        background: #e6c1c1;
        border: 1px solid #b56262;
        padding: 5px;
        text-align: center;
        border-radius: 4px;
    }
    .notification {
        display: none;
        position: fixed;
        padding: 50px 120px;
        background: white;
        border-radius: 10px;
        top: 50%;
        left: 50%;
        text-align: center;
        transform: translate(-50%, -50%);
    }
    .notification img {
        width: 80px;
        margin-bottom: 20px;
    }
    h3, label {
        font-size: 17px;
        font-weight: 600;
        line-height: 22px;
    }
    .header {
        max-width: 1000px;
        margin: 30px auto 60px;
    }
    .footer {
        padding: 60px 0;
        text-align: center;
    }
    .footer ul {
        margin: 20px 0;
        padding: 0;
    }
    .footer ul li {
        display: inline-block;
        margin: 0 20px;
        font-weight: bold;
    }
    .button {
        margin: 0 20px;
        font-weight: bold;
    }
    .btn-yes {
        background-color: #0077be;
    }
    .btn-yes:hover {
        border-color: #0077be
    }
    .note-text {
        font-style: italic;
        font-size: 14px;
    }
    input {
        margin: 0 10px;
    }
    .solar-system .col-lg-6{
        padding: 0 15px;
    }
    .icon-noti-fail {
        display: none;
    }
    .loading {
        text-align: center;
        display: none;
    }
    .text-noti {
        display: none;
        color: red;
        font-weight: bold;
    }
</style>
<?php
    $db = DBManagerFactory::getInstance();
    $token = $_GET['token'];
    $query = "SELECT tstamp, quote_id FROM pending_quote_token WHERE token ='$token'";
    $ret = $db->query($query);
    $row = $db->fetchByAssoc($ret);

    $quote = new AOS_Quotes();
    $quote->retrieve($row['quote_id']);

    // var_dump($quote); die;

    $date = new DateTime();
    $timestampToday = $date->getTimestamp();
    if($timestampToday - $row['tstamp'] >= 86400) {
        ?>
            <div class="container">
                <h3 class='errorToken'>The token has expired or does not exist!
                </h3>
            </div>
        <?php
    } else {
        ?>
        <div class="container">
            <div class="header">
                <img src="https://pure-electric.com.au/sites/default/files/logo_4.png" alt="">
            </div>
            <form id='formClientInfo' method='POST' enctype="multipart/form-data">
                <div class="row">
                    <input type="hidden" name='installation_pictures_c' id='installation_pictures_c' value='<?php echo $quote->pre_install_photos_c ?>'>
                    <input type="hidden" name='quote_id' id='quote_id' value='<?php echo $row['quote_id'] ?>'>
                    <input type="hidden" name='token' id='token' value='<?php echo $_GET['token']?>'>
                    <div class="formGroup">
                        <div class="input-group input-group-lg">
                            <h2>Is the install address for your solar system below correct?</h3>
                        </div>
                        <div class="input-group input-group-lg text-center">
                            <a href="javascript:void(0)" class='button btn btn-yes btn-confirm'>YES</a>
                            <a href="javascript:void(0)" class='button btn btn-no'>NO</a>
                        </div>
                        <div class="input-group input-group-lg text-center">
                            <p class='text-noti'>please enter the correct install address below</h3>
                        </div>
                        <div class="input-group input-group-lg">
                            <label for="">Street:</label>
                            <input type="text" name='billing_street'  value="<?php echo $quote->billing_address_street ?>" required>
                        </div>
                        <div class="input-group input-group-lg">
                            <label for="">City:</label>
                            <input type="text" name='billing_city'  value="<?php echo $quote->billing_address_city ?>" required>
                        </div>
                        <div class="input-group input-group-lg">
                            <label for="">State:</label>
                            <input type="text" name='billing_state' value="<?php echo $quote->billing_address_state ?>" required>
                        </div>
                        <div class="input-group input-group-lg">
                            <label for="">Postal Code:</label>
                            <input type="text" name='billing_postal_code' value="<?php echo $quote->billing_address_postalcode ?>" required>
                        </div>
                        <div class="input-group input-group-lg">
                            <label for="">Country:</label>
                            <input type="text" name='billing_country' value="<?php echo $quote->billing_address_country ?>" required>
                        </div>
                        <div class="input-group input-group-lg text-center">
                            <p class='note-text'>Note: If you have an existing solar system please enter system details (if known).  If you don't have an existing solar system or don't know what it is please skip this section and move on to the upload the file upload section, we can discuss any existing system with you in person</p>
                        </div>
                        <div class="input-group input-group-lg text-center">
                            <a href="javascript:void(0)" class='button btn btn-yes' id='buttonCheckSolarSystem'>I don't have an existing solar system</a>
                        </div>
                        <div class="row solar-system">
                            <div class="col-lg-6">
                                <div class="input-group input-group-lg">
                                    <label for="">Inverter capacity (e.g. 5 kW, 6 kW etc):</label>
                                    <input type="text" name='inverter_capacity'  value="">
                                </div>
                                <div class="input-group input-group-lg">
                                    <label for="">Inverter type (e.g. SMA, Fronius etc):</label>
                                    <input type="text" name='inverter_type'  value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-lg">
                                    <label for="">Solar panel wattage (e.g. 270 W, 300W etc):</label>
                                    <input type="text" name='solar_wattage'  value="">
                                </div>
                                <div class="input-group input-group-lg">
                                    <label for="">Number of solar panels on your roof:</label>
                                    <input type="text" name='number_solar'  value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-lg">
                                    <label for="">Type of solar panels (e.g. Jinko, Q-Cell, Sunpower etc):</label>
                                    <input type="text" name='type_solar'  value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-lg drap-file">
                        <h2>Drag and Drop Files</h2>
                        <div class="row">
                            <h3>Switchboard photo as shown in example</h3>
                            <div class="col-md-8">
                                <input type="file" class='area-upload' accept="image/*" name='switchboardPhoto' onchange='readURL(this)'>
                                <div class="row row-item-prev">
                                    <div class="col-lg-12"><img class='imgPrev' src="" alt=""><span class='nameFile'></span></div>
                                    <div class="controlFile">
                                        <img src="themes/SuiteP/images/edit-item.png" class='editFile' alt="Edit">
                                        <img src="themes/SuiteP/images/remove-item.png" class='removeFile' alt="Remove">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 imageSingle">
                                <img class='imageForm' src="themes/SuiteP/images/switchboard.png" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <h3>Meter photo clearly showing meter number</h3>
                            <div class="col-md-8">
                                <input type="file" class='area-upload' accept="image/*" name='meterPhoto' onchange='readURL(this)'>
                                <div class="row row-item-prev">
                                    <div class="col-lg-12"><img class='imgPrev' src="" alt=""><span class='nameFile'></span></div>
                                    <div class="controlFile">
                                        <img src="themes/SuiteP/images/edit-item.png" class='editFile' alt="Edit">
                                        <img src="themes/SuiteP/images/remove-item.png" class='removeFile' alt="Remove">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 imageSingle">
                                <img class='imageForm' src="themes/SuiteP/images/meter.png" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <h3>Power bill (pdf or photo) showing: Retailer name, account number, name on account, meter number and NMI (you can just send in the entire bill if in doubt)</h3>
                            <div class="col-md-8">
                                <input class='area-upload' name='billPhoto' type="file" onchange='readURL(this)'>
                                <div class="row row-item-prev">
                                    <div class="col-lg-12"><img class='imgPrev' src="" alt=""><span class='nameFile'></span></div>
                                    <div class="controlFile">
                                        <img src="themes/SuiteP/images/edit-item.png" class='editFile' alt="Edit">
                                        <img src="themes/SuiteP/images/remove-item.png" class='removeFile' alt="Remove">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 imageSingle">
                                <img class='imageForm' src="themes/SuiteP/images/bill.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-lg drap-file">
                        <input type="submit" value="SEND" class='submitForm'>
                    </div>
                    <div class='loading'>
                        <img src="themes/SuiteP/images/loading.svg" alt="">
                        <h3>Sending...</h3>
                    </div>
                </div>
            </form>
            <div class="footer">
                <p>© Pure Electric 2019 All Rights Reserved.</p>
                <ul>
                    <li>
                        <a href="https://pure-electric.com.au/website-terms-of-use">Term Of Use</a>
                    </li>
                    <li>
                        <a href="https://pure-electric.com.au/privacy-policy">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="https://pure-electric.com.au/sitemap.xml">Sitemap</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
    }
?>
<div class='backgroundPopup'>
</div>
<div class='previewImage'>
    <img src="https://www.tradeoakbuildingkits.com/wp-content/plugins/portfolio-filter-gallery-premium/img/x-close-icon-white.png" class='closePopup' alt="">
    <img src="" class='previewTarget' alt="">
</div>
<div class='notification'>
    <img class='icon-noti' src="themes/SuiteP/images/complete.png" alt="">
    <img class='icon-noti-fail' src="themes/SuiteP/images/fail.png" alt="">
    <h2>Success! Files sent</h2>
</div>
<script>
    $(document).ready(function() {
        var address = $('input[name=billing_street], input[name=billing_city], input[name=billing_state], input[name=billing_postal_code], input[name=billing_country]')
        var solarInfo = $('input[name=inverter_capacity], input[name=inverter_type], input[name=solar_wattage], input[name=number_solar], input[name=type_solar]')
        var state = 1;
        $('.imageForm').click(function() {
            var imgURL = $(this).attr('src');
            $('.previewTarget').attr('src',imgURL);
            $('.backgroundPopup, .previewImage').show();
        });
        $('.closePopup').click(function() {
            $('.backgroundPopup, .previewImage').hide();
        });
        $('.btn-confirm').click(function(){
            address.attr('disabled', 'disabled');
            $('.text-noti').hide();
        });
        $('.btn-no').click(function(){
            address.removeAttr('disabled');
            $('.text-noti').show();
        });
        $('#buttonCheckSolarSystem').click(function(){
            if(state == 1) {
                solarInfo.val('');
                solarInfo.attr('disabled', 'disabled');
                $('.solar-system').css('opacity', '0.4');
                state = 2;
            } else {
                solarInfo.removeAttr('disabled');
                $('.solar-system').css('opacity', '1');
                state = 1;
            }
            
        })
        $("#formClientInfo").submit(function(e) {
            $('.loading').show();
            e.preventDefault();
            var form = $(this);
            var url = 'index.php?entryPoint=sendFormClientInfo';
            var formData = new FormData($(this)[0]);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(data)
                {
                    $('.loading').hide();
                    $('.backgroundPopup, .notification').show();
                    setTimeout(function(){
                        window.location.href = 'https://pure-electric.com.au/';
                    }, 1000)
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('.loading').hide();
                    $('.notification').find('.icon-noti').hide();
                    $('.notification').find('.icon-noti-fail').show();
                    $('.notification').find('h2').text('Send error, request new information input form via email');
                    $('.backgroundPopup, .notification').show();
                    setTimeout(function(){
                        window.location.href = 'https://pure-electric.com.au/';
                    }, 1000)
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        $('.removeFile').click(function(){
            var parentFile = $(this).parent().parent().find('.col-lg-12');
            parentFile.find('.imgPrev').attr('src', '');
            parentFile.find('.nameFile').text('');
            $(this).parent().hide();
        })
    });
    function readURL(input) {
        var name = $(input).attr('name');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.fileName = input.files[0].name;
            reader.onload = function(e) {
                $(input).parent().find('.imgPrev').attr('src',e.target.result);
                if(name == 'switchboardPhoto') {
                    $(input).parent().find('.nameFile').text('switchboard.' + e.target.fileName.split('.')[1]);
                } else if(name == 'meterPhoto') {
                    $(input).parent().find('.nameFile').text('meter.' + e.target.fileName.split('.')[1]);
                } else {
                    $(input).parent().find('.nameFile').text('bill.' + e.target.fileName.split('.')[1]);
                }
                $(input).parent().find('.controlFile').show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    };
    
</script>