<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 21.08.13
 * Time: 19:27
 * To change this template use File | Settings | File Templates.
 */

function h1_search($text) {
    $matches = array();
    if(preg_match('@</h1>@si', $text, $matches)) {

        return $matches;
    } else {
        return false;
    }
}

function onBeforeOpt($text) {
$text = str_replace('<a href="http://www.mcolmed.ru/services/stock/advice-phlebologist-free/" alt="�����!" class="banner"></a>', ' ', $text);
$text = str_replace('<a href="/news/gruppy-voprosov/">������-�����</a>', ' ', $text);
$text = str_replace('<a href="/patients/gallery/mc-olmed-chkalov-124/page-1/" id="id_prev"></a>', '', $text);
$text = str_replace('<a href="/patients/gallery/opening-mc-olmed-on-chkalova-street-124/page-1/" id="id_prev"></a>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/surgery/laser-removal-of-tumors/">�������� �������� ���������������</a></li>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/ultrasonography/ultrasound-examination-of-the-arteries-veins-of-the-lower-extremities/">���� ������� ������ �����������</a></li>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/ultrasonography/ultrasound-of-the-heart/">��� ������</a></li>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/ultrasonography/ultrasound-examination-of-abdominal-cavity-organs/">��� ������� ������� �������</a></li>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/ultrasonography/ultrasound-of-the-neck-vessels/">��� ������� ���</a></li>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/ultrasonography/ultrasound-examination-of-thyroid-gland/">��� ���������� ������</a></li>', '', $text);
$text = str_replace('<li><a href="http://www.mcolmed.ru/services/ultrasonography/ultrasound-examination-of-mammary-glands/">��� �������� �����</a></li>', '', $text);
$text = str_replace('data-flamp-widget-color="red"', 'data-flamp-widget-color="green"', $text);
$text = str_replace('<a href="/patients/question-answer/">������-�����</a>', '', $text);
$text = str_replace('<a href="/services/stock/">�����</a>', '', $text);
$text = str_replace('<a href="/services/161/">���</a>', '', $text);
$text = str_replace('<a href="/services/hospital/">���������</a>', '', $text);
$text = str_replace('<a href="/services/policlinic/">�����������</a>', '', $text);
$text = str_replace('<a href="/services/vacancies/">��������</a>', '', $text);
$text = str_replace('<div class="big-pict" style="background:url(/upload/information_system_6/1/group_12/information_groups_property_26.jpg) no-repeat top left;"></div>', '', $text);
$text = str_replace('<a href="/patients/podgotovka-k-uzi/" title="���������� � ���">���������� � ���</a>', '', $text);
$text = str_replace('  <li>
    
  </li>', '', $text);


if($_SERVER['REQUEST_URI']=='/services/vascular/'){
    $string = '<a href="/services/vascular/varicosity/">���������� �������, ��������������� (���������� ���������)</a>';
    $textArray = explode($string,$text);
    $text = '';
    foreach($textArray AS $key=>$ta){
        $text .= $ta;
        if($key>0 && $key!=sizeof($textArray)-1){
            $text .= $string;
        }
    }




    //$text = preg_replace('/<a href="\/services\/vascular\/varicosity\/">���������� �������, ��������������� \(���������� ���������\)<\/a>/siU','',$text);
    //echo print_r($math_array);
    //$text = preg_replace('<div class="group_list">', '<div class="group_list" style="display:none;">', $text);
}


$text = preg_replace('/<div class="row-button">(.*)<div class="button test">(.*)<a href="http:\/\/www.mcolmed.ru\/test-na-varikoz\/" class="button__item">(.*)<\/a>(.*)<\/div>(.*)<\/div>/siU', '<!--test-na-varikoz-button-->', $text);


$uri = trim($_SERVER['REQUEST_URI'],'/');
if($uri!=''){
    $arrayUri = explode('/',$uri);


    if(sizeof($arrayUri)>2 && $arrayUri[0]=='services'){

        $text = preg_replace('/(<\/h1>)/si','$1<!--sendLineBlock-->',$text);

    }

    if(sizeof($arrayUri)>1){

        if($arrayUri[1]=='vascular' || $arrayUri[1]=='vascular-surgery'){
            $text = preg_replace('/<!--test-na-varikoz-button-->/si',testNaVarikozButton(),$text);
        }
    }
}

$text = preg_replace('/<!--sendLineBlock-->/si',sendLineBlock(),$text);

    if(in_array($_SERVER['HTTP_HOST'],array('serov.mcolmed.ru','ntagil.mcolmed.ru'))){
        $text = preg_replace('/<div id="header">(.*)<!-- #header -->/siU', newHeader($_SERVER['HTTP_HOST']), $text);
    }


    $text = preg_replace('/(<\/div><\!-- #header -->)/siU', blockHeader().'$1', $text);


    if(in_array($_SERVER['HTTP_HOST'],array('ntagil.mcolmed.ru','serov.mcolmed.ru')) ) {
        //������� ��� ���� �� �������
        if($_SERVER['REQUEST_URI']=='/') {
            if (preg_match('/<div class="nav">(.*)<ul class="ls-none">(.*)<\/ul>(.*)<ul class="ls-none">(.*)<\/ul>(.*)<ul class="ls-none">(.*)<\/ul>(.*)<ul class="ls-none">(.*)<\/ul>(.*)<ul class="ls-none">(.*)<\/ul>((.*)<ul class="ls-none">(.*)<\/ul>)?(.*)<div class="clr">/siU', $text, $mathText)) {
                unset($mathText[0]);
                $textNew = array('<div class="nav"><ul class="ls-none">');
                foreach ($mathText As $key => $math) {
                    $textNew[] = $math;
                    if (in_array($key, array(4, 8))) {
                        $textNew[] = '</ul><ul class="ls-none">';
                    }

                }
                $textNew[] = '</ul><div class="clr"></div></div>';
                $text = preg_replace('/<div class="nav">(.*)<div class="clr"><\/div>(.*)<\/div>/siU', implode('', $textNew), $text);
				$text = str_replace('/services/vascular/varicosity/diagnosing/', '/services/vascular-surgery/varicose-disease-telangiectasias-spider-veins/diagnosis-of-varicose-veins/', $text);
				$text = str_replace('/services/vascular/varicosity/treatment-varicose/', '/services/vascular-surgery/varicose-disease-telangiectasias-spider-veins/treatment-of-varicose-veins/', $text);
				$text = str_replace('/services/vascular/varicosity/sosudistye-zvezdochki/', '/services/vascular-surgery/varicose-disease-telangiectasias-spider-veins/treatment-of-spider-veins/', $text);
				$text = str_replace('/services/vascular/varicosity/sclerotherapy/', '/services/vascular-surgery/varicose-disease-telangiectasias-spider-veins/sclerotherapy/', $text);
				$text = str_replace('/services/vascular/varicosity/endovazalnaya/', '/services/vascular-surgery/varicose-disease-telangiectasias-spider-veins/endovenous-laser-coagulation/', $text);
				$text = str_replace('/services/vascular/varicosity/bez-operatsii/', '/services/vascular-surgery/varicose-disease-telangiectasias-spider-veins/treatment-without-surgery/', $text);
            }
        }

        //��������� �������
        $text = preg_replace('/<div class="(send-under|send)">(.*)<\/ul>(.*)<\/div>/siU', '', $text);
        $text = preg_replace('/<div class="flampWidget">(.*)<\/div>/siU', '', $text);
        $text = preg_replace('/<div id="sign-upa">(.*)<\/div>(.*)<\/div>(.*)<\/div>/siU', '<div id="sign-upa">$1</div></div></div>'.flampWinget(), $text);
		$text = str_replace('href="/patients/media-about-us/"', 'href="/news/media/"', $text);
		$text = str_replace('href="/patients/media/"', 'href="/news/media/"', $text);
    }
    return $text;
}

function onAfterOpt($text) {
$text = str_replace('<a href="http://mcolmed.ru/">', '<a href="http://www.mcolmed.ru/">', $text);

    $text = preg_replace('/<!--sendLineBlock-->/si',mb_convert_encoding(sendLineBlock(),'utf-8','windows-1251'),$text);

 if($_SERVER['HTTP_HOST']=='serov.mcolmed.ru'){
		$text = str_replace('<strike>', ' ', $text);
		$text = str_replace('<!--ololo-->', mb_convert_encoding('<tr><td>������������</td><td>1800 ������</td></tr>','utf-8','windows-1251'), $text);
		$text = preg_replace('/<\/strike>(.*)'.mb_convert_encoding('������','utf-8','windows-1251').'/siU', mb_convert_encoding(' ������','utf-8','windows-1251'), $text);

    }	
	
 if($_SERVER['HTTP_HOST']=='serov.mcolmed.ru' && $_SERVER['REQUEST_URI']=='/'){
        $text = str_replace('<li>
    <a href="/services/dermatographia/">', '<li style="width: 161.91px;">
    <a href="/services/dermatographia/">', $text);
		$text = str_replace('<li>
    <a href="/services/163/">', '<li style="width: 136.53px;">
    <a href="/services/163/">', $text);
    }
	
 if($_SERVER['HTTP_HOST']=='serov.mcolmed.ru' && $_SERVER['REQUEST_URI']=='/services/physiotherapy/'){
        $text = str_replace(mb_convert_encoding('<tr><td>������������</td><td>100 ������</td></tr>','utf-8','windows-1251'), '', $text);
    }
	
 if($_SERVER['HTTP_HOST']=='ntagil.mcolmed.ru'){
        $text = str_replace('<strike>', ' ', $text);
        $text = preg_replace('/<\/strike>(.*)'.mb_convert_encoding('������','utf-8','windows-1251').'/siU', mb_convert_encoding(' ������','utf-8','windows-1251'), $text);
    }	
    return $text;
}

function blockHeader(){
    return '<div class="headerRight">
                <div><span class="callibri_phone">8 804 333 000 2<span></div>
                ���������� ������� ����� �� ���� ������
            </div>';
}

function flampWinget(){
    return '<div class="flampWidget" style="margin-top: 1em; margin-bottom: 0;">
                <a class="flamp-widget" href="http://ekaterinburg.flamp.ru/firm/olmed_ooo_medicinskijj_centr-1267166676374976"  data-flamp-widget-type="medium" data-flamp-widget-color="green" data-flamp-widget-id="1267166676374976">������ � ��� �� ������</a><script>!function(d,s){var js,fjs=d.getElementsByTagName(s)[0];js=d.createElement(s);js.async=1;js.src="http://widget.flamp.ru/loader.js";fjs.parentNode.insertBefore(js,fjs);}(document,"script");</script>
        </div>
        <div class="left_banners" style="margin: 1em 0;">
		<div class="years" style="display: inline-block;vertical-align: middle;margin-right: 15px; border:0; width: auto">
		    <img src="/img/klinik.png" title="������ ������� ������� �� 2014" alt="������ ������� ������� �� 2014">
		</div>
			<div class="reestr" style="display: inline-block;vertical-align: middle; position: static"><img src="/img/reestr.png" title="���������� �����: 66 � 0400000178" alt="���������� �����: 66 � 0400000178"></div>

		</div>
';
}
function newHeader($domain){
    $arrCity = array(
        'https://www.mcolmed.ru/'=>'������������',
        'https://serov.mcolmed.ru/'=>'�����',
        'https://ntagil.mcolmed.ru/'=>'������ �����',
        'https://kturinsk.mcolmed.ru/'=>'��������������',
        'https://sevural.mcolmed.ru/'=>'�������������',
    );
    $arrContacts = array(
        'serov.mcolmed.ru'=>array(
            'phone'=>'+7 (34385) 42-933',
            'address'=>'<a href="/contacts/">��. ����������� ���������, �. 7</a>',
        ),
        'ntagil.mcolmed.ru'=>array(
            'phone'=>'+7 (3435) 47-54-18',
            'address'=>'<a href="/contacts/">��. ����������� ���������, �. 7�</a>',
        ),
        'kturinsk.mcolmed.ru'=>array(
            'phone'=>'+7 (34384) 98-998',
            'address'=>'<a href="/contacts/">��. ������, �. 36</a>',
        ),
    );
    $city = '<option value="https://'.$domain.'/">'.$arrCity['https://'.$domain.'/'].'</option>';
    unset($arrCity['https://'.$domain.'/']);

    foreach($arrCity As $link=>$cityName){
        $city .= '<option value="'.$link.'">'.$cityName.'</option>';
    }


    return '<div id="header">
	<div class="logo">
		<a href="/" title="����� - ����������� �����">
			<img src="http://www.mcolmed.ru/img/logo.png" alt="�����">
		</a>
	</div>
	<div class="slk">
		<div>��� �����: </div>
		<span>
			<select onchange="window.location.href=this.options[this.selectedIndex].value">
				'.$city.'
			</select>
		</span>
	</div>


        <div class="adress">
        	<p>'.$arrContacts[$domain]['phone'].'</p>
<div class="ad">'.$arrContacts[$domain]['address'].'</div>        </div>

    <div class="headerRight">
                <div><span>8 804 </span>333 000 2</div>
                ���������� ������� ����� �� ���� ������
            </div></div><!-- #header -->';
}


function testNaVarikozButton(){
    return '<div class="row-button">
                <div class="button test">
                    <a href="http://www.mcolmed.ru/test-na-varikoz/" class="button__item">
                        <span class="button__item-text">���� �� �������</span>
                    </a>
                </div>
            </div>';
}
function sendLineBlock(){
    return '<div class="send new" style="margin: 0;">
               <ul class="ls-none">
                    <li class="call"><a href="#form_1" class="modal-button">�������� ������</a></li>
                    <li class="consult"><a href="#form_2" class="modal-button">������ �� ������������</a></li>
                    <li class="question"><a href="#form_3" class="modal-button">������ �����</a></li>
                    <li class="director"><a href="#form_5" class="modal-button">������ ���������</a></li>
               </ul>
               <span style="clear:both; display: block"></span>
            </div>';
}
