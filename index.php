<?php
include("src/core/request_data.php");
include("src/core/generate_rates_XML.php");
include("src/core/convert.php");
include("src/utils/validate.php");
include("src/utils/validation.php");
include("src/utils/xml_tweaks.php");
include("src/utils/responses.php");
include_once("src/data/config.php");

@date_default_timezone_set("GMT");

define('PARAMS', array('to', 'from', 'amnt', 'format'));

writeXmlRates();

checkConvertParameters();

$curreny_exchange_details = calculate();
displayConvertResult($curreny_exchange_details, $format = $_GET["format"]);
