<?php

/* SOAP API functions wordpress - innovo (MarketingEngineer)

    uses wpsyslog plugin for logging.   
    
*/
class innovo_api {

  const var $host = '';
  const var $user_name = '';
  const var $pwd = '';
  
  var $location;
  var $uri;
  var $trace = 1;
  var $user_name = "";
  var $pwd = "";
  var $user_auth;
  var $client;
  var $login;
  var $timeout =1800;
  
  var $services = array();
  var $service_instances = array();
  
  var $actual_contact_id = array();

  //status constants
  var $ABC_SERVICE_NAME = 'ABC_csomag_regisztráció';
  var $ABC_INSTANCE = 'ABC_általános';
  var $ABC_INSTANCE_A = 'ABC_A-csomag';
  var $ABC_INSTANCE_B = 'ABC_B-csomag';
  var $ABC_INSTANCE_C = 'ABC_C-csomag';
  var $ABC_STATUS_ORDER = 'Megrendelés';
  var $ABC_STATUS_REG = 'Regisztrálás';
  
  var $ABC_INSTANCE_WP = 'WP regisztráció';
  var $ABC_STATUS_REG_WP = 'Regisztrálás WP'; 
  
  //status constants Andió megrendelés
  var $ORDER_SERVICE_NAME = 'Andió megrendelés';
  var $ORDER_INSTANCE_ORDER = 'Aktuális rendelés';
  var $ORDER_INSTANCE_1X = '1x rendelt';
  var $ORDER_INSTANCE_2X = '2x rendelt';
  var $ORDER_INSTANCE_3X = '3x rendelt';
  var $ORDER_INSTANCE_4X = '4x rendelt';
  var $ORDER_INSTANCE_5X = '5x rendelt';
  var $ORDER_INSTANCE_6X = '5+ rendelés törzsvásárló';
  var $ORDER_STATUS_ORDER = 'Rendelés leadva';
  var $ORDER_STATUS_WAITING = 'Fizetésre várunk';
  var $ORDER_STATUS_RETAIL_WAITING = 'Viszonteladói fizetésre várunk';
  var $ORDER_STATUS_PERSONAL_WAITING = 'Személyes elvitelre várunk';
  var $ORDER_STATUS_PAYED = 'Fizetés rendben';
  var $ORDER_STATUS_CANCEL = 'Lemondta';
  
  //status constants Andió általános
  var $COMMON_SERVICE_NAME = 'Andió általános';
  var $COMMON_INSTANCE = 'Andió általános';

  var $COMMON_STATUS_VASARLO = 'Vásárló';    
  var $COMMON_STATUS_2X = '2x rendelt';   
  var $COMMON_STATUS_3X = '3x rendelt';   
  var $COMMON_STATUS_4X = '4x rendelt';   
  var $COMMON_STATUS_5X = '5x rendelt';   
  var $COMMON_STATUS_5P = 'Törzsvásárló';   
  var $COMMON_STATUS_ARANYKARTYAS = 'Aranykártyás';
  var $COMMON_STATUS_VISZONTELADO = 'Viszonteladó';  
  
  //status constants
  var $KARACSONY2012_SERVICE_NAME = '2012_karácsony';
  var $KARACSONY2012_INSTANCE = '2012_karácsony';
  var $KARACSONY2012_STATUS_ORDER = 'Megrendelés';  
  
  //karaktertípus
  var $KARAKTER_SERVICE_NAME = "Karaktertípus";
  var $KARAKTER_INSTANCE_NAME = "Karaktertípus";
  var $KARAKTER_STATUS_PORGOS = "Pörgős";
  var $KARAKTER_STATUS_ELEMZO = "Megrendelés"; //ideiglenes!!
  var $KARAKTER_STATUS_HUMAN = "Humán";
  var $KARAKTER_STATUS_URALKODO = 'Uralkodó';
  
  // termékrendelés
  var $PROD_SERVICE_NAME = "Andió termékrendelés";
  var $PROD_STATUS_1X = "1x rendelt";
  var $PROD_STATUS_2X = "2x rendelt";
  var $PROD_STATUS_3X = "3x rendelt";
  var $PROD_STATUS_4X = "4x rendelt";
  var $PROD_STATUS_5X = "5x rendelt";
  
  // törzsvásárló
  var $CLIENT_SERVICE_NAME = "Törzsvásárló";
  var $CLIENT_INSTANCE_NAME = "Törzsvásárló 2012 karácsony";
  var $CLIENT_STATUS_ORDER = "Rendelt";
  
  // 2012 FB karácsony
  var $FB2012KAR_SERVICE_NAME = "2012 Facebook karácsony";
  var $FB2012KAR_INSTANCE_NAME = "2012 FB karácsony általános";
  var $FB2012KAR_STATUS_ORDER = "Megrendelés";  
  
    //2013 kavezacc
  var $FB_KAVEZACC_SERVICE = "2013 FB kavezacc";
  var $FB_KAVEZACC_INSTANCE = "2013 FB kavezacc";
  var $FB_KAVEZACC_INSTANCE_TOJASHEJ = "2013 FB tojashej";
  var $FB_KAVEZACC_STATUS_REG = 'játszott';
  var $FB_KAVEZACC_STATUS_ORDER = 'rendelt';
  
  //Kateogoria
  var $KATEGORIA_SERVICE_NAME = "KATEGÓRIA";
  var $KATEGORIA_INSTANCE_SHEA = "Kategória: Sheavaj";
  var $KATEGORIA_INSTANCE_OKO = "Kategória: Öko háztartás";
  var $KATEGORIA_INSTANCE_BOR = "Kategória: Bőrprobléma";
  var $KATEGORIA_INSTANCE_ELETMOD = "Kategória: Életmód";
  var $KATEGORIA_INSTANCE_FERFI = "Kategória: Férfiak";
  var $KATEGORIA_STATUS_CHOOSE = "Választott";
  
  // Blog 20130122
  var $BLOG_SERVICE_NAME = "2013 Blog";
  var $BLOG_INSTANCE_20130122 = "2013_január_22";
  var $BLOG_INSTANCE_20130222 =  "2013 február 22";
  var $BLOG_INSTANCE_20130307 =  "2013_márc_07";
  var $BLOG_INSTANCE_20130327 =  "2013_marc_27";
  var $BLOG_INSTANCE_20130416 =  "2013_ápr_16";
  var $BLOG_INSTANCE_20130522 = "2013_május 22";
  var $BLOG_STATUS_ORDER = "rendelt";
  
    //2013 cekla
  var $FB_CEKLA_SERVICE = "2013 FB Cékla";
  var $FB_CEKLA_INSTANCE = "2013 FB kavezacc";
  var $FB_CEKLA_STATUS_REG = 'játszott';
  var $FB_CEKLA_STATUS_ORDER = 'Megrendelte';
  
  // Futó sorozat
  var $FUTO_SERVICE = "Futó sorozat";
  var $FB_FUTO_INSTANCE = "Futó sorozat: Cékla";
  var $FB_FUTO_STATUS_REG = 'Fut';    
  
  var $INSTANCE_ID_TABLE_NAME = '';

  // 2013 szivcsippento

  var $FB_SZIV_STATUS = "FB játék";
  var $FB_STATUS_ORDER = "Megrendelte";
  

  var $FB_SERVICE = "2013 FB";
  var $FB_INSTANCE_1 = "2013 FB Szív";
  var $FB_INSTANCE_2 = "2013 FB Nonap";
  
  
  var $DOBOZ_SERVICE ="Dobozkupon";
  var $DOBOZ_INSTANCE ="Dobozkupon";
  var $DOBOZ_INIT ="Init";
  var $DOBOZ_ACTIVATE ="Rendelt";
  
  var $WIDGET_SERVICE="Widget";
  var $WIDGET_TEAFA_INSTANCE="Widget teafa";
  var $WIDGET_COUPON_RECEIVED="Kupont megkapta";
  
    // Szodabikarbona
  var $FB_SZODA_SERVICE="Szódabikarbóna";
  var $FB_SZODA_INSTANCE="Szódabikarbóna";
  var $FB_SZODA_STATUS_ORDER="Rendelt";
 
     // Szodabikarbona rendeltek
  var $FB_SZODARENDELT_SERVICE="Szódabikarbóna rendeltek";
  var $FB_SZODARENDELT_INSTANCE="Szódabikarbóna rendeltek";
  var $FB_SZODARENDELT_STATUS_ORDER="Rendelt";
  
    // szolomagolaj
  var $FB_SZOLOMAG_SERVICE = "Szőlőmag";
  var $FB_SZOLOMAG_INST_TEST = "Szőlőmag teszt";
  var $FB_SZOLOMAG_INST_EBOOK = "Szőlőmag ebook";
  var $FB_SZOLOMAG_INST_KOLESZT = "Szőlőmag koleszterin";
  var $FB_SZOLOMAG_INST_MEREG = "Szőlőmag méregtelenítés";
  var $FB_SZOLOMAG_INST_RANC = "Szőlőmag ránctalanítás";
  var $FB_SZOLOMAG_STATUS_REG = 'Olvasta';
  var $FB_SZOLOMAG_STATUS_REG_SPEC = 'Regisztrált Spec';
  var $FB_SZOLOMAG_STATUS_REG_ORDER = 'Rendelt';
  
    // méz
  var $MEZ_SERVICE = "Méz";
  var $MEZ_INSTANCE = "Méz";
  var $MEZ_STATUS_ORDER = 'Rendelt';
  
  var $SZUL_2013_SERVICE = "Születésnap 2013";
  var $SZUL_2013_INSTANCE = "Születésnap 2013";
  var $SZUL_2013_STATUS_INIT_REGI = "Init régen rendelt";
  var $SZUL_2013_STATUS_INIT_30 = "Init 30 napon belül rendelt";
  var $SZUL_2013_STATUS_ORDER = "Rendelt";
  
    //karácsony 2013
    var $FB_KARACSONY13_SERVICE = "2013 Karácsony";
    var $FB_KARACSONY13_INSTANCE = "2013 Karácsony";
    var $FB_KARACSONY13_STATUS_OLDORDER = "Korábban rendelt";
    var $FB_KARACSONY13_STATUS_ORDER = "Rendelt";
    
    var $FM2014_SERVICE = "2014 Felmérés";
    var $FM2014_INSTANCE = "2014 Felmérés";
    var $FM2014_STATUS_ORDER = "Megrendelés";

    var $OKO_SERVICE = "ÖKO Háztartás 2014";
    var $OKO_INSTANCE = "ÖKO Háztartás 2014";    
    var $OKO_STATUS_ORDER = "Rendelt";
    var $FUTO_INSTANCE_OKO = "Futó sorozat: ÖKO Háztartás 2014";
    var $FUTO_STATUS_END = 'Befejezve';
    
    var $KOKUSZ_SERVICE = "Kókusz";
    var $KOKUSZ_INSTANCE = "Kókusz";
    var $KOKUSZ_STATUS_UPLOAD = "Feltöltött";
    var $KOKUSZ_STATUS_VOTE = "Szavazott";
    var $KOKUSZ_STATUS_ORDER = "rendelt";
    
    //BIoquiz
    var $BIOQUIZ_SERVICE = "Bioquiz";
    var $BIOQUIZ_INSTANCE = "Bioquiz";
    var $BIOQUIZ_STATUS_TEST = "Tesztet kitöltötte";
    
    var $BIOQUIZTEST_SERVICE = "Bioquiz teszt";
    var $BIOQUIZTEST_INSTANCE = "Bioquiz teszt";
    var $ADVENTTEST_INSTANCE = "Advent teszt";
    var $ADVENT15_INSTANCE = "Advent 2015";
    
    var $BIOQUIZTEST_STATUS_GOLD = "Arany";
    var $BIOQUIZTEST_STATUS_SILVER = "Ezüst";
    var $BIOQUIZTEST_STATUS_BRONZE = "Bronz";   
    
    var $KARACSONY2014_SERVICE = "Karácsony 2014";
    var $KARACSONY2014_INSTANCE = "Bioquiz";
    var $KARACSONY2014_STATUS_TEST = "Tesztet kitöltötte";
    var $KARACSONY2014_STATUS_REG = "Reg Teszt";
    var $KARACSONY2014_STATUS_ORDER = "rendelt";    
    
    var $QUIZ_SERVICE = "Quiz kampány";
    var $QUIZ_INSTANCE_SPICE = "Quiz fűszer";
    var $QUIZ_STATUSZ_ORDER = "rendelt";
    var $QUIZ_STATUSZ_QUIZ = "Quiz";
    var $QUIZ_STATUSZ_REG = "Reg exit popup";
    
    var $QUIZ_RESULT_SERVICE = "Quiz kampány eredmény";
    var $QUIZ_RESULT_INSTANCE_SPICE = "Quiz fűszer eredmény";
    var $QUIZ_RESULT_INSTANCE_OKO = "Öko háztartás eredmény";
    var $QUIZ_RESULT_INSTANCE_OKO16 = "Öko háztartás 16 eredmény";
    var $QUIZ_RESULT_GOLD = "Arany";
    var $QUIZ_RESULT_SILVER = "Ezüst";
    var $QUIZ_RESULT_BRONZ = "Bronz";
    
    // Teafa
    var $TEAFA_SERVICE = "Minikampány";    
    var $MINI_INSTANCE_KOROMGOMBA = "Minikampány körömgomba";
    var $MINI_INSTANCE_OKO = "Minikampány öko háztartás";
    var $MINI_STATUS_REG = "Reg exitpopup";
    var $MINI_STATUS_MAGNET = "Mágnes";
    
    
    /*KARÁCSONY 2015 */
    var $KAR2015_SERVICE = "Karácsony 2015";
    var $KAR2015_INSTANCE = "Karácsony 2015";
    var $KAR2015_STATUS_REG_GAME = "Reg_game";
    var $KAR2015_STATUS_REG_GAME_NEW = "Reg_game_new";
    var $KAR2015_STATUS_REG_UPLOAD = "Reg_upload";    
    var $KAR2015_STATUS_REG_UPLOAD_NEW = "Reg_upload_new";
    var $KAR2015_STATUS_REG_VOTE = "Reg_vote";    
    var $KAR2015_STATUS_REG_VOTE_NEW = "Reg_vote_new";
    var $KAR2015_STATUS_ORDER_GAME = "Rendelt_game";
    var $KAR2015_STATUS_ORDER_GAME_NEW = "Rendelt_game_new";    
    var $KAR2015_STATUS_ORDER_UPLOAD = "Rendelt_upload";
    var $KAR2015_STATUS_ORDER_NEW = "Rendelt_vote";
    var $KAR2015_STATUS_ORDER = "Rendelt";
        
/* KAMPANY 
     **********************************************************
    */
    var $CAMPAIGN_SERVICE = "Kampány";
    var $CAMPAIGN_INSTANCE_OKO = "Öko háztartás";
    var $CAMPAIGN_INSTANCE_NAPTEJ = "Naptej 2016"; 
    var $CAMPAIGN_INSTANCE_KOROMGOMBA = "Körömgomba";
    var $CAMPAIGN_STATUS_ORDER = "rendelt";    
    var $CAMPAIGN_STATUS_ORDER_REM = "rendelt remarketing";   
    var $CAMPAIGN_STATUS_ORDER_LIST = "rendelt lista"; 
    var $CAMPAIGN_STATUS_ORDER_DOWN = "rendelt downsell";   
    var $CAMPAIGN_STATUS_INIT = "Init";      
    var $CAMPAIGN_STATUS_REG = "Mágnes";      
    var $CAMPAIGN_STATUS_REG_LIST = "Mágnes 2";      
    
    /* 
       constructor with params SOAP username and password
     */
    function __construct($host, $user_name, $pwd) {
        $this->host = $host;
        $this->user_name = $user_name;
        $this->pwd = MD5($pwd);        
        $this->init();
    }
     /* 
        constructor 
     */
    function __construct() {
        $this->init();        
    }
    
    public function init() {
        global $wpdb;
        
        $this->services[0] = array('type'=>'i', 'id'=>'8868ffb8-b13c-2bf4-bdf7-508e3f93a15b', 'name'=>$this->ABC_SERVICE_NAME);
        $this->services[1] = array('type'=>'i', 'id'=>'7fcb3f32-9d6f-28ea-be26-509a2c8ff633', 'name'=>$this->ORDER_SERVICE_NAME);
        $this->services[2] = array('type'=>'i', 'id'=>'32084514-35a8-4fcd-d41d-509a2cab1736', 'name'=>$this->COMMON_SERVICE_NAME);
        $this->services[3] = array('type'=>'i', 'id'=>'c8fd0271-4c6e-e2e1-d79b-50a3a37066d4', 'name'=>$this->KARACSONY2012_SERVICE_NAME);
        $this->services[4] = array('type'=>'i', 'id'=>'d9fa80d1-91a5-45ac-e7e9-50b73198074b', 'name'=>$this->KARAKTER_SERVICE_NAME);
        $this->services[5] = array('type'=>'i', 'id'=>'291aa373-a286-eb49-00a3-50c1d932b689', 'name'=>$this->CLIENT_SERVICE_NAME);
        $this->services[6] = array('type'=>'i', 'id'=>'3ab8173c-4c07-104f-7ecd-50a7af0e1826', 'name'=>$this->FB2012KAR_SERVICE_NAME);
        $this->services[7] = array('type'=>'i', 'id'=>'20fc0ecf-b061-c331-6b8e-50bc826f7070', 'name'=>$this->PROD_SERVICE_NAME);
        $this->services[8] = array('type'=>'i', 'id'=>'79c204ae-e411-a66e-fdd7-50ea961cf768', 'name'=>$this->FB_KAVEZACC_SERVICE);
        $this->services[9] = array('type'=>'i', 'id'=>'37ecdd76-68db-0a71-c830-508cdf29e688', 'name'=>$this->KATEGORIA_SERVICE_NAME);
        $this->services[10]= array('type'=>'i', 'id'=>'9faa251d-c945-c1e8-fafc-50ffa6ec5fac', 'name'=>$this->BLOG_SERVICE_NAME);
        $this->services[11] = array('type'=>'i', 'id'=>'2aae7793-2d74-c07c-6b87-5103eeff728e', 'name'=>$this->FB_CEKLA_SERVICE);
        $this->services[12] = array('type'=>'i', 'id'=>'af8f6556-4744-d41f-e420-50fd496e5949', 'name'=>$this->FUTO_SERVICE);      
        $this->services[13] = array('type'=>'i', 'id'=>'4161b53a-1537-7d4e-d1d0-5116c3525d29', 'name'=>$this->FB_SERVICE);          
        $this->services[14] = array('type'=>'i', 'id'=>'35ad0af5-d41d-838b-c90a-51b9aaf34d30', 'name'=>$this->DOBOZ_SERVICE);  
        $this->services[15] = array('type'=>'i', 'id'=>'57510456-7e7e-1610-2ef6-51acb8d90a21', 'name'=>$this->WIDGET_SERVICE);  
        $this->services[16] = array('type'=>'i', 'id'=>'79f19b91-60f3-7c4c-f28c-51c95982d21f', 'name'=>$this->FB_SZODA_SERVICE);
        $this->services[17] = array('type'=>'i', 'id'=>'6a94cc32-8b10-1d61-13b5-521f4e3e096f', 'name'=>$this->FB_SZODARENDELT_SERVICE);
        $this->services[18] = array('type'=>'i', 'id'=>'7fc2e01e-a190-1bcd-7772-522ef3843ee8', 'name'=>$this->FB_SZOLOMAG_SERVICE);
        $this->services[19] = array('type'=>'i', 'id'=>'166e51e6-df96-52b0-07eb-526a370bf3e6', 'name'=>$this->MEZ_SERVICE);
        $this->services[20] = array('type'=>'i', 'id'=>'897afc19-c521-8aeb-41aa-528b3ee7e0e6', 'name'=>$this->SZUL_2013_SERVICE);
        $this->services[21] = array('type'=>'i', 'id'=>'a07503ba-5750-71a5-e590-529f626d58ce', 'name'=>$this->FB_KARACSONY13_SERVICE);
        $this->services[22] = array('type'=>'i', 'id'=>'3ede0b9c-ad9e-492b-27c5-52cdce108954', 'name'=>$this->FM2014_SERVICE);
        $this->services[23] = array('type'=>'i', 'id'=>'3f142a37-3277-bb76-5fda-52f0ed261ebc', 'name'=>$this->OKO_SERVICE);
        $this->services[24] = array('type'=>'i', 'id'=>'afefa7a4-ff72-a1d7-965c-54006793be80', 'name'=>$this->KOKUSZ_SERVICE);
        $this->services[25] = array('type'=>'i', 'id'=>'a7369bdc-2208-3946-52dd-5447aade2283', 'name'=>$this->BIOQUIZ_SERVICE);
        $this->services[26] = array('type'=>'i', 'id'=>'1182a72b-d1ed-a6b7-cb50-544a059293d7', 'name'=>$this->BIOQUIZTEST_SERVICE);
        $this->services[27] = array('type'=>'i', 'id'=>'16c92875-484d-e0a9-4960-546c62f37840', 'name'=>$this->KARACSONY2014_SERVICE);
        $this->services[28] = array('type'=>'i', 'id'=>'8a49f8bc-a351-6997-a0e9-550a9ab5eda3', 'name'=>$this->QUIZ_SERVICE);
        $this->services[29] = array('type'=>'i', 'id'=>'66974f15-6f03-ddc4-1abb-550a9e7b4ba8', 'name'=>$this->QUIZ_RESULT_SERVICE);
        $this->services[30] = array('type'=>'i', 'id'=>'1a6e896c-5444-1359-f7d0-5374ea40b824', 'name'=>$this->TEAFA_SERVICE); 
        $this->services[31] = array('type'=>'i', 'id'=>'646d3a32-2c6c-a122-727f-56508be8998b ', 'name'=>$this->KAR2015_SERVICE); 
        $this->services[32] = array('type'=>'i', 'id'=>'cfdcdd69-2d65-5685-b18f-55efeae52f9f', 'name'=>$this->CAMPAIGN_SERVICE); 
        
        $this->service_instances[0] = array('type'=>'i', 'id'=>'3c9ac090-dc8c-7187-bdc6-508e40673fcc', 'name'=> $this->ABC_INSTANCE, 'service_name' => $this->services[0]['name']);
        $this->service_instances[15] = array('type'=>'i', 'id'=>'74ad11f6-b069-3ebb-6838-508e40bb6de8', 'name'=> $this->ABC_INSTANCE_A, 'service_name' => $this->services[0]['name']);
        $this->service_instances[16] = array('type'=>'i', 'id'=>'89450249-c0ee-b97b-84f2-508e40da0b38', 'name'=> $this->ABC_INSTANCE_B, 'service_name' => $this->services[0]['name']);
        $this->service_instances[17] = array('type'=>'i', 'id'=>'11351a4f-4437-b9a5-8f27-508e409d4de9', 'name'=> $this->ABC_INSTANCE_C, 'service_name' => $this->services[0]['name']);
        $this->service_instances[1] = array('type'=>'i', 'id'=>'75ab7d32-1ee4-fd14-9f4d-50b9ed776859', 'name'=> $this->ORDER_INSTANCE_ORDER, 'service_name' => $this->services[1]['name']);        
        $this->service_instances[2] = array('type'=>'i', 'id'=>'ca700741-4b3b-352b-16b6-509a671b63c6', 'name'=> $this->COMMON_INSTANCE, 'service_name' => $this->services[2]['name']);
        $this->service_instances[3] = array('type'=>'i', 'id'=>'5d3f9211-43cd-68b9-e999-50a3a3df7a44', 'name'=> $this->KARACSONY2012_INSTANCE, 'service_name' => $this->services[3]['name']);
        $this->service_instances[4] = array('type'=>'i', 'id'=>'55a4bdff-110b-ea00-1663-50b76cf86812', 'name'=> $this->KARAKTER_INSTANCE_NAME, 'service_name' => $this->services[4]['name']);
        
        $this->service_instances[5] = array('type'=>'i', 'id'=>'a513b660-b6e1-827e-b321-50bc805a94e2', 'name'=> $this->ORDER_INSTANCE_1X, 'service_name' => $this->services[1]['name']);        
        $this->service_instances[6] = array('type'=>'i', 'id'=>'23b1e07b-165a-e5ff-af80-50bc81b07356', 'name'=> $this->ORDER_INSTANCE_2X, 'service_name' => $this->services[1]['name']);        
        $this->service_instances[7] = array('type'=>'i', 'id'=>'4021fa53-eb28-fea8-703a-50bc82785d4d', 'name'=> $this->ORDER_INSTANCE_3X, 'service_name' => $this->services[1]['name']);        
        $this->service_instances[8] = array('type'=>'i', 'id'=>'4f6c9473-58a2-298d-9b92-50bc82ded680', 'name'=> $this->ORDER_INSTANCE_4X, 'service_name' => $this->services[1]['name']);        
        $this->service_instances[9] = array('type'=>'i', 'id'=>'533943f8-ddc9-7277-6f72-50bc8278d6fd', 'name'=> $this->ORDER_INSTANCE_5X, 'service_name' => $this->services[1]['name']);        
        $this->service_instances[10] = array('type'=>'i', 'id'=>'d3e20e46-0476-45d5-4380-50bc82db3746', 'name'=> $this->ORDER_INSTANCE_6X, 'service_name' => $this->services[1]['name']);        
        
        $this->service_instances[11] = array('type'=>'i', 'id'=>'bbe7c6f0-f301-1b08-a9f3-50c1da75085c', 'name'=> $this->CLIENT_INSTANCE_NAME, 'service_name' => $this->services[5]['name']);
        $this->service_instances[12] = array('type'=>'i', 'id'=>'2416b185-d61c-ab72-2dc0-50a7af5a4527', 'name'=> $this->FB2012KAR_INSTANCE_NAME, 'service_name' => $this->services[6]['name']);
        
        $this->service_instances[13] = array('type'=>'i', 'id'=>'44523a8c-8223-f0f0-7f23-50ea96f37322', 'name'=> $this->FB_KAVEZACC_INSTANCE, 'service_name' => $this->services[8]['name']);
        $this->service_instances[14] = array('type'=>'i', 'id'=>'551df34f-554e-4b42-4339-50f3fba5cb55', 'name'=> $this->FB_KAVEZACC_INSTANCE_TOJASHEJ, 'service_name' => $this->services[8]['name']);
        
        $this->service_instances[18] = array('type'=>'i', 'id'=>'5996080b-91f2-e427-a789-50fd8607090d', 'name'=> $this->KATEGORIA_INSTANCE_SHEA, 'service_name' => $this->services[9]['name']);
        $this->service_instances[19] = array('type'=>'i', 'id'=>'32801956-41ab-89a2-4810-50ebca925b3f', 'name'=> $this->KATEGORIA_INSTANCE_OKO, 'service_name' => $this->services[9]['name']);
        $this->service_instances[20] = array('type'=>'i', 'id'=>'690f2e7a-7e8b-52ba-6711-50fe988cd00f', 'name'=> $this->KATEGORIA_INSTANCE_BOR, 'service_name' => $this->services[9]['name']);
        $this->service_instances[21] = array('type'=>'i', 'id'=>'93babad6-176f-b550-d572-50fe98c45ecf', 'name'=> $this->KATEGORIA_INSTANCE_ELETMOD, 'service_name' => $this->services[9]['name']);
     //   $this->service_instances[22] = array('type'=>'i', 'id'=>'648718aa-1181-909e-b91f-50fe9850a7af', 'name'=> $this->KATEGORIA_INSTANCE_FERFIAK, 'service_name' => $this->services[9]['name']);
        
        $this->service_instances[23] = array('type'=>'i', 'id'=>'c16a7991-13fd-1234-d015-50ffa616cb54', 'name'=> $this->BLOG_INSTANCE_20130122, 'service_name' => $this->services[10]['name']);
        

        $this->service_instances[24] = array('type'=>'i', 'id'=>'d25a9883-2e2c-b879-aeb2-5103eecd9252', 'name'=> $this->FB_CEKLA_INSTANCE, 'service_name' => $this->services[11]['name']);
        $this->service_instances[25] = array('type'=>'i', 'id'=>'436ea503-367e-9147-7eea-5103f10d4f38', 'name'=> $this->FB_FUTO_INSTANCE, 'service_name' => $this->services[12]['name']);
        
        $this->service_instances[26] = array('type'=>'i', 'id'=>'96736623-5ba8-d7e4-d377-5116c3eed8ed', 'name'=> $this->FB_INSTANCE_1, 'service_name' => $this->services[13]['name']);
        $this->service_instances[27] = array('type'=>'i', 'id'=>'59960929-4a6b-9e9a-d019-512dbf37de2f', 'name'=> $this->BLOG_INSTANCE_20130222, 'service_name' => $this->services[10]['name']);
        $this->service_instances[28] = array('type'=>'i', 'id'=>'5f77c657-344a-3a9e-aee7-5137ada05a9e', 'name'=> $this->FB_INSTANCE_2, 'service_name' => $this->services[13]['name']);
        $this->service_instances[29] = array('type'=>'i', 'id'=>'c32acf30-aacd-e211-65e5-513702a0d729', 'name'=> $this->BLOG_INSTANCE_20130307, 'service_name' => $this->services[10]['name']);
        $this->service_instances[30] = array('type'=>'i', 'id'=>'67d0e95a-7499-9097-fd7c-5151611dc1bc', 'name'=> $this->BLOG_INSTANCE_20130327, 'service_name' => $this->services[10]['name']);
        $this->service_instances[31] = array('type'=>'i', 'id'=>'d4e81914-15b3-2c47-dd31-516c5e89c962', 'name'=> $this->BLOG_INSTANCE_20130416, 'service_name' => $this->services[10]['name']);            
        $this->service_instances[32] = array('type'=>'i', 'id'=>'6f5fc899-3f85-5851-8612-51a49318ada4', 'name'=> $this->BLOG_INSTANCE_20130522, 'service_name' => $this->services[10]['name']);    
        
        $this->service_instances[33] = array('type'=>'i', 'id'=>'d9482c5f-fa27-2fc6-9702-51b9aa5cd09f', 'name'=> $this->DOBOZ_INSTANCE, 'service_name' => $this->services[14]['name']);    
        
        $this->service_instances[34] = array('type'=>'i', 'id'=>'782c3e26-30a1-1fd5-3bdf-51acb8b21ac4', 'name'=> $this->WIDGET_TEAFA_INSTANCE, 'service_name' => $this->services[15]['name']);    
        $this->service_instances[35] = array('type'=>'i', 'id'=>'bd7732e2-1232-1ea2-3c94-51c95a53475e', 'name'=> $this->FB_SZODA_INSTANCE, 'service_name' => $this->services[16]['name']);
        
        $this->service_instances[36] = array('type'=>'i', 'id'=>'c5c973df-d291-7194-959d-51f1115f3c5b', 'name'=> $this->ABC_INSTANCE_WP, 'service_name' => $this->services[0]['name']);
        
        $this->service_instances[37] = array('type'=>'i', 'id'=>'267bf67c-5687-f2c0-063e-521f4eab29ab', 'name'=> $this->FB_SZODARENDELT_INSTANCE, 'service_name' => $this->services[0]['name']);
        
        $this->service_instances[38] = array('type'=>'i', 'id'=>'e3ec96f7-4ecd-dc66-efe2-522ef3170b54', 'name'=> $this->FB_SZOLOMAG_INST_EBOOK, 'service_name' => $this->services[18]['name']);
        $this->service_instances[39] = array('type'=>'i', 'id'=>'ba2298fb-53af-5ef7-5f8c-522ef39f53cd', 'name'=> $this->FB_SZOLOMAG_INST_TEST, 'service_name' => $this->services[18]['name']);
        $this->service_instances[40] = array('type'=>'i', 'id'=>'c03612ad-0a75-d8d2-5736-522ef3fb0969', 'name'=> $this->FB_SZOLOMAG_INST_KOLESZT, 'service_name' => $this->services[18]['name']);
        $this->service_instances[41] = array('type'=>'i', 'id'=>'597c0183-5282-d5c8-f02b-522ef30bd5ce', 'name'=> $this->FB_SZOLOMAG_INST_MEREG, 'service_name' => $this->services[18]['name']);
        $this->service_instances[42] = array('type'=>'i', 'id'=>'75a90315-91ee-f996-5be0-522ef36b4eb4', 'name'=> $this->FB_SZOLOMAG_INST_RANC, 'service_name' => $this->services[18]['name']);        
        $this->service_instances[43] = array('type'=>'i', 'id'=>'97e8dec8-fba3-6b20-5ac2-526a3743de56', 'name'=> $this->MEZ_INSTANCE, 'service_name' => $this->services[19]['name']);        
        
        $this->service_instances[44] = array('type'=>'i', 'id'=>'691e7d1a-f945-b4e2-5894-528b3ebf8a01', 'name'=> $this->SZUL_2013_INSTANCE, 'service_name' => $this->services[20]['name']);        
        
        $this->service_instances[45] = array('type'=>'i', 'id'=>'38250124-683f-7965-f09b-529f62457223', 'name'=> $this->FB_KARACSONY13_INSTANCE, 'service_name' => $this->services[21]['name']);
        $this->service_instances[46] = array('type'=>'i', 'id'=>'79a06314-a6f5-1303-af5e-52cdcead0908', 'name'=> $this->FM2014_INSTANCE, 'service_name' => $this->services[22]['name']);
        $this->service_instances[47] = array('type'=>'i', 'id'=>'82ccbc77-4e64-4d59-cbfd-52f0ed27a812', 'name'=> $this->OKO_INSTANCE, 'service_name' => $this->services[23]['name']);
        $this->service_instances[48] = array('type'=>'i', 'id'=>'e8a92a8a-0ed8-62b8-4f34-52f9d295ac70', 'name'=> $this->FUTO_INSTANCE_OKO, 'service_name' => $this->services[12]['name']);
        $this->service_instances[49] = array('type'=>'i', 'id'=>'8148ead4-39c1-e9d1-d0d3-540067d099b4', 'name'=> $this->KOKUSZ_INSTANCE, 'service_name' => $this->services[24]['name']);
        
        $this->service_instances[50] = array('type'=>'i', 'id'=>'91b27df3-9c86-a427-eccd-5447aaa42429', 'name'=> $this->BIOQUIZ_INSTANCE, 'service_name' => $this->services[25]['name']);
        $this->service_instances[51] = array('type'=>'i', 'id'=>'aa5576f2-e295-24da-9b76-544a05698947', 'name'=> $this->BIOQUIZTEST_INSTANCE, 'service_name' => $this->services[26]['name']);
        $this->service_instances[52] = array('type'=>'i', 'id'=>'4b540fa8-9ce2-6d91-f734-546c63d52e34', 'name'=> $this->KARACSONY2014_INSTANCE, 'service_name' => $this->services[27]['name']);
        $this->service_instances[53] = array('type'=>'i', 'id'=>'e3b96a76-3580-c501-fdf0-546db5ab6b39', 'name'=> $this->ADVENTTEST_INSTANCE, 'service_name' => $this->services[26]['name']);        
        $this->service_instances[54] = array('type'=>'i', 'id'=>'1ae64e10-5059-762f-8319-550a9ada4528', 'name'=> $this->QUIZ_INSTANCE_SPICE, 'service_name' => $this->services[28]['name']);        
        $this->service_instances[55] = array('type'=>'i', 'id'=>'31e2db77-2a02-3dea-2b09-550a9e5606d4', 'name'=> $this->QUIZ_RESULT_INSTANCE_SPICE, 'service_name' => $this->services[29]['name']);        
        $this->service_instances[56] = array('type'=>'i', 'id'=>'16d6e82f-6aa9-c845-f2d6-55a4e512e30f', 'name'=> $this->QUIZ_RESULT_INSTANCE_OKO, 'service_name' => $this->services[29]['name']);        
        $this->service_instances[57] = array('type'=>'i', 'id'=>'93fda43c-545e-ca75-3b42-55a4e68c325e', 'name'=> $this->MINI_INSTANCE_OKO, 'service_name' => $this->services[30]['name']);        
        $this->service_instances[58] = array('type'=>'i', 'id'=>'9b352bdc-97c0-7a81-07e2-56508b85188e', 'name'=> $this->KAR2015_INSTANCE, 'service_name' => $this->services[31]['name']);   
        $this->service_instances[59] = array('type'=>'i', 'id'=>'5e385815-c517-fa19-bca8-565099c6ad77', 'name'=> $this->ADVENT15_INSTANCE, 'service_name' => $this->services[26]['name']);                    
        $this->service_instances[60] = array('type'=>'i', 'id'=>'9278a581-75d9-62cf-bee9-57304aeba8a4', 'name'=> $this->CAMPAIGN_INSTANCE_OKO, 'service_name' => $this->services[32]['name']); 
        $this->service_instances[61] = array('type'=>'i', 'id'=>'9bce786d-138d-d3c8-073a-573ef100fdb3', 'name'=> $this->QUIZ_RESULT_INSTANCE_OKO16, 'service_name' => $this->services[32]['name']); 
        $this->service_instances[62] = array('type'=>'i', 'id'=>'6ac1bd6d-006b-8878-f80a-5756bd90ed6f', 'name'=> $this->CAMPAIGN_INSTANCE_NAPTEJ, 'service_name' => $this->services[32]['name']); 
        
        
        $this->INSTANCE_ID_TABLE_NAME = $wpdb->prefix . 'innovo_order_instances';
        $this->login();
    }
    
    function login() {
        if ($this->host == '' || $this->user_name == '' || $this->pwd == '') {
            wpsyslog("empty host, username or password");
            return;
        }
        $options = array( 'location' => $this->host.'/soap.php', 'uri' => $this->host, 'trace' => $this->trace , 'connection_timeout' => $this->timeout); 
        $user_auth = array( "user_name" => $this->user_name, "password" => $this->pwd ); 
        $this->client = new SoapClient(null, $options); 
        $this->login = $this->client->login($user_auth, ''); 
        
        if ($this->login->id==-1) {
            wpsyslog("innovo_api login",$login->error);
        }
    }   
    
    function logout() {
        $this->client->logout($this->login->id);
    }
    
    function exists_subscriber($email) {
        $result = $this->client->contact_by_email($this->user_name, $this->pwd, $email);            
        return count($result) > 0;
    }
    
    public function subscribe ($email, $firstname, $lastname, $check = false) {
        $result = null;
        $subscribe = !$check;
        if ($check && !$this->exists_subscriber($email)) {
            $subscribe = true;
        }
        if ($subscribe) {
            $result = $this->client->new_person($firstname, $lastname, $email); 
        }
        if (!isset($result)) {
            wpsyslog("subscribe", "email " . $email . " error subscribing: "  .$result->error->description,0);
            return false;
        }
        return $result->id;
    }
    
    public function get_subscriber ($email) {
        $result = $this->client->contact_by_email($email, $this->user_name, $this->pwd);
        if (count($result) == 0) { 
            wpsyslog("innovo_get_subscriber", "no subscribe by email " . $email);
        }        
        return $result;
    }
    
    public function get_subscriber_id ($email) {
        $innovo_id = array();
        if ($this->actual_contact_id['email'] == $email) {
            $innovo_id['id'] = $this->actual_contact_id['id'];
            $innovo_id['type'] = $this->actual_contact_id['type'];
            return $innovo_id;
        }
        $result = $this->client->contact_by_email($this->user_name, $this->pwd, $email);
        if (count($result) == 0) {
            wpsyslog("innovo_get_subscriber", "no subscriber by email. " . $email);
        } else {
            $this->actual_contact_id['email'] = $email;
            $innovo_id['id'] = $result[0]->id;
            $this->actual_contact_id['id'] =  $result[0]->id;
            if ( strlen($result[0]->id) == 36) {
                $innovo_id['type'] =  'i';
                $this->actual_contact_id['type'] = 'i';
            } else {
                $innovo_id['type'] = 'x';
                $this->actual_contact_id['type'] = 'x';
                $innovo_id['application'] = ' ';
            }            
        }
        return $innovo_id;
    }    
    
     function get_service_id($service_name) {
        $innovo_id = array();
        foreach($this->services as $service) {
            if ($service['name'] == $service_name) {
                $innovo_id['type'] = $service['type'];
                $innovo_id['id'] = $service['id'];
            }
        }   
        return $innovo_id; 
    }
    
    private function get_service_instance_common_id($service_name) {
        $innovo_id = array();
        foreach($this->service_instances as $service_instance) {
            if ($service_instance['service_name'] == $service_name) {
                $innovo_id['type'] = 'i';
                $innovo_id['id'] = $service_instance['id'];
                break;
            }
        }
        return $innovo_id;
    }
    
    private function get_service_instance_by_name($instance_name) {
        $innovo_id = array();
        foreach($this->service_instances as $service_instance) {
            if ($service_instance['name'] == $instance_name) {
                $innovo_id['type'] = 'i';
                $innovo_id['id'] = $service_instance['id'];
            }
        }
        return $innovo_id;
    }    
    
    public function update_status($service_name, $new_status, $email) {
        //$this->login();
        $service_id = $this->get_service_id($service_name);
        if (count($service_id) == 0) {  return; }
        
        $service_instance_id = $this->get_service_instance_common_id($service_name);
        if (count($service_instance_id) == 0) {  return; }
        
        $contact_id = $this->get_subscriber_id($email);
        if (count($contact_id) == 0) {  return; }
        $result = $this->client->update_person_status($contact_id, // contact id 
                                                'S', // product/service type 
                                                $service_id, // product id 
                                                '', // status id 
                                                $new_status); // status name
        if ($result->return != 1) {
            wpsyslog(update_status, "email " . $email . " error update_person_status: " . $new_status . " " .$result->error->description,0);
            return;
        }
        $result = $this->client->update_person_status($contact_id, // contact id
                'SI', // product/service type
                $service_instance_id, // product id
                '', // status id
                $new_status); // status name        
        
        if ($result->return!=1) { 
            // error handling 
            wpsyslog("update_status innovo", "email " . $email . " error update_person_status: "  . $new_status . " ".  $result->error->description,0);
            return;
        }       
    }
    
    public function update_status_instance($service_name, $instance_name, $new_status, $email) {
        $service_id = $this->get_service_id($service_name);
        $service_instance_id = $this->get_service_instance_by_name($instance_name);
        $contact_id = $this->get_subscriber_id($email);
        
        $result = $this->client->update_person_status($contact_id, // contact id 
                                                'S', // product/service type 
                                                $service_id, // product id 
                                                '', // status id 
                                                $new_status); // status name
        if ($result->return != 1) {
            wpsyslog("update_status service", "email " . $email . " error '" . " id '" . $service_name . "'  update_person_status: " . $new_status . "' " .  $result->error->description,0);
            return;
        }
        $result = $this->client->update_person_status($contact_id, // contact id
                'SI', // product/service type
                $service_instance_id, // product id
                '', // status id
                $new_status); // status name        
        
        if ($result->return!=1) { 
            wpsyslog("update_status  instance innovo", "email " . $email . " error '" . " id '" . $service_instance_id->id . "'  update_person_status: " . $new_status . "' " .  $result->error->description,0);
        }      
    }   

    public function delete_status_instance($instance_name, $email) {
    	$service_instance_id = $this->get_service_instance_by_name($instance_name);
    	if (count($service_instance_id) == 0) {  return; }
    	$contact_id = $this->get_subscriber_id($email);
    	if (count($contact_id) == 0) {  return; }
    
    	$result = $this->client->delete_person_status($contact_id, // contact id
    			'SI', // product/service type
    			$service_instance_id // product id
    			); // status name
    	if ($result->return != 1) {
    		return;
    	}    	
    }    

    public function update_order_status($service_name, $new_status, $email, $purchase_count) {
        $service_id = $this->get_service_id($service_name);
        //$service_instance_id = $this->get_order_service_instance_id($service_name, $service_id);
        $instance_name = $this->ORDER_INSTANCE_1X;
        switch ($purchase_count) {
            case 0:
            case 1:
                $instance_name = $this->ORDER_INSTANCE_1X;
                break;
            case 2:
                $instance_name = $this->ORDER_INSTANCE_2X;
                break;
            case 3:
                $instance_name = $this->ORDER_INSTANCE_3X;
                break;
            case 4:
                $instance_name = $this->ORDER_INSTANCE_4X;
                break;
            case 5:
                $instance_name = $this->ORDER_INSTANCE_5X;
                break;   
            default:     
                $instance_name = $this->ORDER_INSTANCE_6X;
                break;                           
        }
        $service_instance_id = $this->get_service_instance_by_name($instance_name);
        $contact_id = $this->get_subscriber_id($email);
                
        $result = $this->client->update_person_status($contact_id, // contact id
                'SI', // product/service type
                $service_instance_id, // product id
                '', // status id
                $new_status//$this->ORDER_STATUS_ORDER 
                ); // status name
         
        if ($result->return!=1) {
            // error handling
            wpsyslog("update_order_status innovo", "update person status nt successful email: " . $email. " "  . $result->error->description);
        }                    
    }
    
    public function update_product_status($service_name, $email, $prod_id) {
    	global $wpdb;
    	$service_id = $this->get_service_id($service_name);
    	$prod_name = $wpdb->get_var("select name from ". WPSC_TABLE_CART_CONTENTS." where prodid=" . $prod_id . " LIMIT 1");
    	$service_instance_id = $this->get_product_service_instance_id($service_name, $service_id, $prod_id, $prod_name);
    	$purchase_count = number_of_purchase_of_product($prod['prodid'], $email, $purchase_id);
    	
    	$new_status = $this->PROD_STATUS_1X;
    	switch ($purchase_count) {
    		case 0:
    		case 1:
    			$new_status = $this->PROD_STATUS_1X;
    			break;
    		case 2:
    			$new_status = $this->PROD_STATUS_2X;
    			break;
    		case 3:
    			$new_status = $this->PROD_STATUS_3X;
    			break;
    		case 4:
    			$new_status = $this->PROD_STATUS_4X;
    			break;
    		default:
    			$new_status = $this->PROD_STATUS_5X;
    			break;    		
    	}
    	    	
    	$contact_id = $this->get_subscriber_id($email);

    	$result = $this->client->update_person_status($contact_id, // contact id
    			'SI', // product/service type
    			$service_instance_id, // product id
    			'', // status id
    			$new_status
    	); // status name
    	 
    	if ($result->return!=1) {
    		// error handling
    		wpsyslog("update_product_status innovo", "update product person status nt successful email: " . $email. " "  . $result->error->description);
    	}    	
    }
    
    public function update_product_status_by_order($service_name, $email, $purchase_id) {
    	global $wpdb;
    	$service_id = $this->get_service_id($service_name);
    	$contact_id = $this->get_subscriber_id($email);
    	
    	$prods = $wpdb->get_results("select prodid, name from ". WPSC_TABLE_CART_CONTENTS." where purchaseid=" . $purchase_id , ARRAY_A);
    	foreach ($prods as $prod) {
	    	$service_instance_id = $this->get_product_service_instance_id($service_name, $service_id, $prod['prodid'], $prod['name']);
	    	$purchase_count = number_of_purchase_of_product($prod['prodid'], $email, $purchase_id);	    	
	    	
	    	$new_status = $this->PROD_STATUS_1X;
	    	switch ($purchase_count) {
	    		case 0:
	    		case 1:
	    			$new_status = $this->PROD_STATUS_1X;
	    			break;
	    		case 2:
	    			$new_status = $this->PROD_STATUS_2X;
	    			break;
	    		case 3:
	    			$new_status = $this->PROD_STATUS_3X;
	    			break;
	    		case 4:
	    			$new_status = $this->PROD_STATUS_4X;
	    			break;
	    		default:
	    			$new_status = $this->PROD_STATUS_5X;
	    			break;    		
	    	}
	    	
	    	$result = $this->client->update_person_status($contact_id, // contact id
	    			'SI', // product/service type
	    			$service_instance_id, // product id
	    			'', // status id
	    			$new_status
	    	); // status name
	    	
	    	if ($result->return!=1) {
	    		// error handling
	    		wpsyslog("update_product_status innovo", "update product person status nt successful email: " . $email. " prod "  . $prod['prodid'] . $result->error->description);
	    	}
    	}	
    }
    
    private function get_order_service_instance_id($service_name, $service_id) {
        global $wpdb;
        $instance_id = array();
        $today=date('Ymd');
        $sql = "select id from " . $this->INSTANCE_ID_TABLE_NAME . " where service_name = '" . $service_name . "' and instance_name = '" . $today . "'";
        //wpsyslog("get_order_service_instance_id", "sql " . $sql);
        $instance_id = $wpdb->get_var($sql);
        
        //wpsyslog("get_order_service_instance_id", "id " . $instance_id);
        
        if ($instance_id == null) {
            $api_instance_id = $this->client->new_service_instance($service_id, // parent service 
                    '', //external_id, 
                    $today, //name 
                    'Megrendelések ezen a napon:' . date('Y.m.d'), //description 
                    '', //no alias 
                    '', //location 
                    '', //postal_code 
                    '', //city 
                    '', //address 
                    '', //country 
                    '', //start_date 
                    '', //end_date 
                    '', //start_time 
                    ''); //end_time
            //wpsyslog("get_order_service_instance_id", "new id " . $api_instance_id->id);
            $wpdb->insert($this->INSTANCE_ID_TABLE_NAME, array(
                    'id'            => $api_instance_id->id,
                    'instance_name' => $today,
                    'service_name' => $service_name));
            $instance_id = $api_instance_id->id;
        }
        if ($instance_id != null) {
            $innovo_id['type'] = 'i';
            $innovo_id['id'] = $instance_id;
        }
        return $innovo_id;
    }
    
    private function get_product_service_instance_id($service_name, $service_id, $prod_id, $prod_name) {
    	global $wpdb;
    	$instance_id = array();
    	$sql = "select id from " . $this->INSTANCE_ID_TABLE_NAME . " where service_name = '" . $service_name . "' and blog_id = " . $prod_id;
    	//wpsyslog("get_order_service_instance_id", "sql " . $sql);
    	$instance_id = $wpdb->get_var($sql);
    
    	//wpsyslog("get_order_service_instance_id", "id " . $instance_id);
    
    	if ($instance_id == null) {
    		$api_instance_id = $this->client->new_service_instance($service_id, // parent service
    				'', //external_id,
    				$prod_name, //name
    				'Termék:' . $prod_name, //description
    				'', //no alias
    				'', //location
    				'', //postal_code
    				'', //city
    				'', //address
    				'', //country
    				'', //start_date
    				'', //end_date
    				'', //start_time
    				''); //end_time
    		//wpsyslog("get_order_service_instance_id", "new id " . $api_instance_id->id);
    		$wpdb->insert($this->INSTANCE_ID_TABLE_NAME, array(
    				'id'            => $api_instance_id->id,
    				'instance_name' => $prod_name,
    				'service_name' => $service_name,
    				'blog_id' => $prod_id));
    		$instance_id = $api_instance_id->id;
    	}
    	if ($instance_id != null) {
    		$innovo_id['type'] = 'i';
    		$innovo_id['id'] = $instance_id;
    	}
    	return $innovo_id;
    }    
    
    public function get_status($email, $instance_name) {
    	$contact_id = $this->get_subscriber_id($email);
    	$service_instance_id = $this->get_service_instance_by_name($instance_name);
    	
    	// get this contact's status information for this product 
    	$result = $this->client->retrieve_person_status($contact_id, 'SI', $service_instance_id);
    	if ($result->return!=1) { 
    		return '';
    	}
    	return $result->status_info->status_name;
    }
    
     public function update_contact_data($email, $parameters) {
        // create a new contact record, assigned to this account, and grab the contact ID
        
        try {
             $result = $this->client->set_entry($this->login->id, 'Contacts', $parameters);
        } catch (Exception $e) {
            wpsyslog ('Caught Soap exception: ' ,   $e->getMessage() . " session " . $this->login->id . " id " . $person_id, 0);
            return;
        }
        return $result->id;
    }
    
    public function get_contact_data($email, $field_list) {

        $contact_id = $this->get_subscriber_id($email);
        $person_id = $contact_id['id'];              
        $result = $this->client->get_entry($this->login->id, 'Contacts', $person_id, $field_list);
        if ($result->error->description != 'No Error') {
            error_log("innovo get_contact_data email: " . $email . " "  . $result->error->description);
            return null;
        }
        //print_r($result->entry_list[0]->name_value_list);
        return $result->entry_list[0]->name_value_list;
    }
}

?>