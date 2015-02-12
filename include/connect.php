<?php
/******************************************
* @Created on Dec 06, 2012
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in

* NOTE:
* ------------------------------------------
* SHARING SECTION
* ------------------------------------------
* IF YOU UPDATE THE USERNAME,PASSWORD OF THE DATABASE,THEN PLEASE CHANGE THE USERNAME,PASSWORD ON BELOW 
* FILES ALSO:
* -------------------------------------------
* ../admin/shareForm.php
* ../admin/selectCategotyAction.php
********************************************/

define('DATABASE_HOST', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', 'j0eN@t!on');
define('DATABASE_DB', 'rand_admin');

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'].'/';

$protocolArray = explode('/', $_SERVER['SERVER_PROTOCOL']);

if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
    $URL_SITE = 'https://'.$_SERVER['HTTP_HOST'];
} else {
	$URL_SITE = strtolower($protocolArray[0]).'://'.$_SERVER['HTTP_HOST'];
}
//facebook apps ids
$app_id='440160526038146';
$secret_id='7a3ab37a13656562f9b58d0cdd521438';
define('URL_SITE', $URL_SITE);
define('DOC_ROOT', $DOC_ROOT);
define('SALT', 'rand'); 

$groupsArray = array(0 => 'Site Settings', '1' => 'Email Settings', '2' => 'Contact Settings', '3' => 'Facebook Settings', '4' => 'Twitter Settings', '5' => 'Google Settings', '6' => 'Linked In Settings','7' => 'Group Plan Setting','8' => 'IPs and Mail Setting','9' => 'PayPal Setting', '10' => 'Discount Settings');

$addbcc = array();
$addbcc['jhao@rand.org'] = 'Jacqueline Sawan';
$addbcc['samuel@rand.org'] = 'Samuel Schilling';
$addbcc['espino@rand.org'] = 'Cielo Espino';
$addbcc['pialogo@rand.org'] = 'Norma Pialogo';
$addbcc['nation@randstatestats.org'] = 'Joe Nation';

// all messages to be shown
$session_message = array(	
	'0' => 'Your Account Validity is over. Subscribe to a membership plan to continue search.',
	'1' => 'You are logged in successfully',
	'2' => 'Username/Password is wrong',
	'3' => 'You have been verified.Please login now.',
	'4' => 'Your account had been verified already.',
	'5' => 'Search input cannot be empty.',
	'6' => 'Your are successfully registered on our site.Please check your email to verify.',
	'7' => 'Your Session has Expired.Please Login again to visit the site.',
	'8' => 'User Type has been added successfully.',
	'9' => 'User Type has been updated successfully.',
	'10' => 'User Type has been deleted successfully.',
	'11' => 'User Subtype has been added successfully.',
	'12' => 'User Subtype has been updated successfully.',
	'13' => 'User Subtype has been deleted successfully.',
	'14' => 'Your Password has been updated successfully.',
	'15' => 'Please enter correct captcha Value.',
	'16' => 'Sorry Internal Error Occur.',
	'17' => 'You have successfully completed your Transaction.',
	'18' => 'Profile has updated successfully.',
	'19' => 'Profile Picture has been updated.',
	'20' => 'Please Select a subscription Plan to continue your registration.',
	'21' => 'You have successfully completed your Registration and Transaction.Please check your email to verify your Account.',
	'22'=> 'Your message has been successfully sent to admin.',
	'23'=> 'No such form exists in this database. Please choose the correct database or follow the navigation links to proceed',
	'24'=> 'User has been added successfully',
	'25'=> 'You have not purchased selected database.',
	'26'=> 'Please enter correct captcha result'
);


$stateToCode = array(
"AK" => "02",
"AL" => "01",
"AR" => "05",
"AS" => "60",
"AZ" => "04",
"CA" => "06",
"CO" => "08",
"CT" => "09",
"DC" => "11",
"DE" => "10",
"FL" => "12",
"FM" => "64",
"GA" => "13",
"GU" => "66",
"HI" => "15",
"IA" => "19",
"ID" => "16",
"IL" => "17",
"IN" => "18",
"KS" => "20",
"KY" => "21",
"LA" => "22",
"MA" => "25",
"MD" => "24",
"ME" => "23",
"MH" => "68",
"MI" => "26",
"MN" => "27",
"MO" => "29",
"MP" => "69",
"MS" => "28",
"MT" => "30",
"NC" => "37",
"ND" => "38",
"NE" => "31",
"NH" => "33",
"NJ" => "34",
"NM" => "35",
"NV" => "32",
"NY" => "36",
"OH" => "39",
"OK" => "40",
"OR" => "41",
"PA" => "42",
"PR" => "72",
"PW" => "70",
"RI" => "44",
"SC" => "45",
"SD" => "46",
"TN" => "47",
"TX" => "48",
"UT" => "49",
"VA" => "51",
"VI" => "78",
"VT" => "50",
"WA" => "53",
"WI" => "55",
"WV" => "54",
"WY" => "56",
"US" => "00"
);


$stateToName = array(
"AL" =>	"Alabama",
"AK" =>	"Alaska",
"AZ" =>	"Arizona",
"AR" => "Arkansas",
"CA" => "California",
"CO" => "Colorado",
"CT" => "Connecticut",
"DE" => "Delaware",
"DC" => "Dist. of Columbia",
"FL" => "Florida",
"GA" => "Georgia",
"HI" => "Hawaii",
"ID" => "Idaho",
"IL" => "Illinois",
"IN" => "Indiana",
"IA" => "Iowa",
"KS" => "Kansas",
"KY" => "Kentucky",
"LA" => "Louisiana",
"ME" => "Maine",
"MD" => "Maryland",
"MA" => "Massachusetts",
"MI" => "Michigan",
"MN" => "Minnesota",
"MS" => "Mississippi",
"MO" => "Missouri",
"MT" => "Montana",
"NE" => "Nebraska",
"NV" => "Nevada",
"NH" => "New Hampshire",
"NJ" => "New Jersey",
"NM" => "New Mexico",
"NY" => "New York",
"NC" => "North Carolina",
"ND" => "North Dakota",
"OH" => "Ohio",
"OK" => "Oklahoma",
"OR" => "Oregon",
"PA" => "Pennsylvania",
"RI" => "Rhode Island",
"SC" => "South Carolina",
"SD" => "South Dakota",
"TN" => "Tennessee",
"TX" => "Texas",
"UT" => "Utah",
"VT" => "Vermont",
"VA" => "Virginia",
"WA" => "Washington",
"WV" => "West Virginia",
"WI" => "Wisconsin",
"WY" => "Wyoming",
"PR" => "Puerto Rico",
"VI" => "Virgin Islands",
"US" => "United States"
);


$CountyCities = array(
	"48001" => array(
	 "8001001" => "Cayuga",
	  "Elkhart\t48001006",
	  "Frankston\t48001011",
	  "Montalba\t48001016",
	  "Neches\t48001021",
	  "Palestine\t48001026",
	  "Slocum\t48001031",
	  "Tennessee Colony\t48001036"
	),
	"48003" => array(
	 "8003001" => "Andrews"
	),
	"48005" => array(
	  "Diboll\t48005001",
	  "Huntington\t48005006",
	  "Lufkin\t48005011",
	  "Pollok\t48005016",
	  "Zavalla\t48005021"
	),
	"48007" => array(
	  "Fulton\t48007001",
	  "Rockport\t48007006"
	),
	"48009" => array(
	  "Archer City\t48009001",
	  "Holliday\t48009006",
	  "Megargel\t48009011",
	  "Scotland\t48009016",
	  "Windthorst\t48009021"
	),
	"48011" => array(
	  "Claude\t48011001",
	  "Wayside\t48011006"
	),
	"48013" => array(
	  "Campbellton\t48013001",
	  "Charlotte\t48013006",
	  "Christine\t48013011",
	  "Jourdanton\t48013016",
	  "Leming\t48013021",
	  "Lytle\t48013026",
	  "Mc Coy\t48013031",
	  "Peggy\t48013036",
	  "Pleasanton\t48013041",
	  "Poteet\t48013046"
	),
	"48015" => array(
	  "Bellville\t48015001",
	  "Bleiblerville\t48015006",
	  "Cat Spring\t48015011",
	  "Industry\t48015016",
	  "Kenney\t48015021",
	  "New Ulm\t48015026",
	  "San Felipe\t48015031",
	  "Sealy\t48015036",
	  "Wallis\t48015041"
	),
	"48017" => array(
	  "Bula\t48017001",
	  "Enochs\t48017006",
	  "Maple\t48017011",
	  "Muleshoe\t48017016"
	),
	"48019" => array(
	  "Bandera\t48019001",
	  "Lakehills\t48019006",
	  "Medina\t48019011",
	  "Pipe Creek\t48019016",
	  "Tarpley\t48019021",
	  "Vanderpool\t48019026"
	),
	"48021" => array(
	  "Bastrop\t48021001",
	  "Cedar Creek\t48021006",
	  "Elgin\t48021011",
	  "Mc Dade\t48021016",
	  "Paige\t48021021",
	  "Red Rock\t48021026",
	  "Rosanky\t48021031",
	  "Smithville\t48021036"
	),
	"48023" => array(
	  "Red Springs\t48023001",
	  "Seymour\t48023006",
	  "Vera\t48023011"
	),
	"48025" => array(
	  "Beeville\t48025001",
	  "Mineral\t48025011",
	  "Normanna\t48025016",
	  "Pawnee\t48025021",
	  "Pettus\t48025026",
	  "Skidmore\t48025031",
	  "Tuleta\t48025036",
	  "Tynan\t48025041"
	),
	"48027" => array(
	  "Bartlett\t48027001",
	  "Belton\t48027006",
	  "Fort Hood\t48027011",
	  "Harker Heights\t48027016",
	  "Heidenheimer\t48027021",
	  "Holland\t48027026",
	  "Killeen\t48027031",
	  "Little River\t48027036",
	  "Nolanville\t48027041",
	  "Pendleton\t48027046",
	  "Rogers\t48027051",
	  "Salado\t48027056",
	  "Temple\t48027061",
	  "Troy\t48027066"
	),
	"48029" => array(
	  "Adkins\t48029001",
	  "Alamo Heights\t48029006",
	  "Atascosa\t48029011",
	  "Balcones Heights\t48029016",
	  "Brooks AFB\t48029026",
	  "Castle Hills\t48029031",
	  "Converse\t48029036",
	  "Elmendorf\t48029041",
	  "Fair Oaks\t48029046",
	  "Fort Sam Houston\t48029056",
	  "Grey Forest\t48029061",
	  "Helotes\t48029066",
	  "Hill Country Village\t48029071",
	  "Hollywood Park\t48029076",
	  "Kelly AFB\t48029086",
	  "Kirby\t48029091",
	  "Lackland AFB\t48029177",
	  "Leon Valley\t48029101",
	  "Live Oak\t48029106",
	  "Macdona\t48029111",
	  "Olmos Park\t48029116",
	  "Randolph A F B\t48029121",
	  "Randolph AFB\t48029126",
	  "Saint Hedwig\t48029131",
	  "Shavano Park\t48029141",
	  "Somerset\t48029146",
	  "Terrell Hills\t48029151",
	  "Universal City\t48029156",
	  "Von Ormy\t48029161",
	  "Wetmore\t48029166",
	  "Windcrest\t48029176"
	),
	"48031" => array(
	  "Blanco\t48031001",
	  "Hye\t48031006",
	  "Johnson City\t48031011",
	  "Round Mountain\t48031016",
	  "Sandy\t48031021"
	),
	"48033" => array(
	  "Gail\t48033001"
	),
	"48035" => array(
	  "Clifton\t48035001",
	  "Cranfills Gap\t48035006",
	  "Iredell\t48035011",
	  "Kopperl\t48035016",
	  "Laguna Park\t48035021",
	  "Meridian\t48035026",
	  "Morgan\t48035031",
	  "Valley Mills\t48035036",
	  "Walnut Springs\t48035041"
	),
	"48037" => array(
	  "Boston\t48037001",
	  "De Kalb\t48037006",
	  "Hooks\t48037011",
	  "Leary\t48037016",
	  "Maud\t48037021",
	  "Nash\t48037026",
	  "New Boston\t48037031",
	  "Redwater\t48037036",
	  "Simms\t48037041",
	  "South Texarkana\t48037046",
	  "Texarkana\t48037051",
	  "Wake Village\t48037056"
	),
	"48039" => array(
	  "Algoa\t48039001",
	  "Alvin\t48039006",
	  "Angleton\t48039011",
	  "Arcola\t48039016",
	  "Brazoria\t48039021",
	  "Brookside Village\t48039026",
	  "Clute\t48039031",
	  "Damon\t48039036",
	  "Danbury\t48039041",
	  "Danciger\t48039046",
	  "Freeport\t48039051",
	  "Lake Jackson\t48039056",
	  "Liverpool\t48039061",
	  "Manvel\t48039066",
	  "Old Ocean\t48039071",
	  "Pearland\t48039076",
	  "Quintana\t48039081",
	  "Richwood\t48039086",
	  "Rosharon\t48039091",
	  "Surfside Beach\t48039096",
	  "Sweeny\t48039101",
	  "West Columbia\t48039106"
	),
	"48041" => array(
	  "Bryan\t48041001",
	  "College Station\t48041006",
	  "Kurten\t48041011",
	  "Millican\t48041016",
	  "Wellborn\t48041026"
	),
	"48043" => array(
	  "Alpine\t48043001",
	  "Big Bend National Park\t48043006",
	  "Marathon\t48043011",
	  "Terlingua\t48043016"
	),
	"48045" => array(
	  "Quitaque\t48045001",
	  "Silverton\t48045006"
	),
	"48047" => array(
	  "Encino\t48047001",
	  "Falfurrias\t48047006"
	),
	"48049" => array(
	  "Bangs\t48049001",
	  "Blanket\t48049006",
	  "Brookesmith\t48049011",
	  "Brownwood\t48049016",
	  "Early\t48049021",
	  "May\t48049026",
	  "Zephyr\t48049031"
	),
	"48051" => array(
	  "Caldwell\t48051001",
	  "Chriesman\t48051006",
	  "Clay\t48051011",
	  "Deanville\t48051016",
	  "Lyons\t48051021",
	  "Snook\t48051026",
	  "Somerville\t48051031"
	),
	"48053" => array(
	  "Bertram\t48053001",
	  "Briggs\t48053006",
	  "Burnet\t48053011",
	  "Granite Shoals\t48053016",
	  "Highland Haven\t48053021",
	  "Horseshoe Bay\t48053026",
	  "Marble Falls\t48053031",
	  "Meadowlakes\t48053036"
	),
	"48055" => array(
	  "Dale\t48055001",
	  "Fentress\t48055006",
	  "Lockhart\t48055011",
	  "Luling\t48055016",
	  "Martindale\t48055021",
	  "Maxwell\t48055026",
	  "Prairie Lea\t48055031"
	),
	"48057" => array(
	  "Long Mott\t48057001",
	  "Point Comfort\t48057006",
	  "Port Lavaca\t48057011",
	  "Port O Connor\t48057016",
	  "Seadrift\t48057021"
	),
	"48059" => array(
	  "Baird\t48059001",
	  "Clyde\t48059006",
	  "Cross Plains\t48059011",
	  "Putnam\t48059016"
	),
	"48061" => array(
	  "Bayview\t48061001",
	  "Brownsville\t48061006",
	  "Combes\t48061011",
	  "Harlingen\t48061016",
	  "La Feria\t48061021",
	  "Los Fresnos\t48061026",
	  "Los Indios\t48061031",
	  "Lozano\t48061036",
	  "Olmito\t48061041",
	  "Port Isabel\t48061046",
	  "Rancho Viejo\t48061051",
	  "Rio Hondo\t48061056",
	  "San Benito\t48061061",
	  "Santa Maria\t48061066",
	  "Santa Rosa\t48061071",
	  "South Padre Island\t48061076"
	),
	"48063" => array(
	  "Leesburg\t48063001",
	  "Pittsburg\t48063006"
	),
	"48065" => array(
	  "Groom\t48065001",
	  "Panhandle\t48065006",
	  "Skellytown\t48065011",
	  "White Deer\t48065016"
	),
	"48067" => array(
	  "Atlanta\t48067001",
	  "Avinger\t48067006",
	  "Bivins\t48067011",
	  "Bloomburg\t48067016",
	  "Douglassville\t48067021",
	  "Hughes Springs\t48067026",
	  "Kildare\t48067031",
	  "Linden\t48067036",
	  "Marietta\t48067041",
	  "Mc Leod\t48067046",
	  "Queen City\t48067051"
	),
	"48069" => array(
	  "Dimmitt\t48069001",
	  "Hart\t48069006",
	  "Nazareth\t48069011",
	  "Summerfield\t48069016"
	),
	"48071" => array(
	  "Anahuac\t48071001",
	  "Hankamer\t48071006",
	  "Monroe City\t48071011",
	  "Mont Belvieu\t48071016",
	  "Stowell\t48071021",
	  "Wallisville\t48071026",
	  "Winnie\t48071031"
	),
	"48073" => array(
	  "Alto\t48073001",
	  "Cuney\t48073006",
	  "Dialville\t48073011",
	  "Forest\t48073016",
	  "Gallatin\t48073021",
	  "Jacksonville\t48073026",
	  "Maydelle\t48073031",
	  "New Summerfield\t48073036",
	  "Reklaw\t48073041",
	  "Rusk\t48073046",
	  "Wells\t48073051"
	),
	"48075" => array(
	  "Carey\t48075001",
	  "Childress\t48075006",
	  "Kirkland\t48075011",
	  "Northfield\t48075016",
	  "Tell\t48075021"
	),
	"48077" => array(
	  "Bellevue\t48077001",
	  "Bluegrove\t48077006",
	  "Byers\t48077011",
	  "Henrietta\t48077016",
	  "Petrolia\t48077021"
	),
	"48079" => array(
	  "Bledsoe\t48079001",
	  "Morton\t48079006",
	  "Whiteface\t48079011"
	),
	"48081" => array(
	  "Bronte\t48081001",
	  "Robert Lee\t48081006",
	  "Silver\t48081011",
	  "Tennyson\t48081016"
	),
	"48083" => array(
	  "Burkett\t48083001",
	  "Coleman\t48083006",
	  "Goldsboro\t48083011",
	  "Gouldbusk\t48083016",
	  "Leaday\t48083021",
	  "Novice\t48083026",
	  "Rockwood\t48083031",
	  "Santa Anna\t48083036",
	  "Talpa\t48083041",
	  "Valera\t48083046",
	  "Voss\t48083051",
	  "Whon\t48083056"
	),
	"48085" => array(
	  "Allen\t48085001",
	  "Anna\t48085006",
	  "Blue Ridge\t48085011",
	  "Celina\t48085016",
	  "Copeville\t48085021",
	  "Farmersville\t48085026",
	  "Frisco\t48085031",
	  "Josephine\t48085036",
	  "Lavon\t48085041",
	  "Lucas\t48085087",
	  "McKinney\t48085046",
	  "Melissa\t48085051",
	  "Murphy\t48085056",
	  "Nevada\t48085061",
	  "Plano\t48085066",
	  "Princeton\t48085071",
	  "Prosper\t48085076",
	  "Westminster\t48085081",
	  "Weston\t48085086"
	),
	"48087" => array(
	  "Dodson\t48087001",
	  "Quail\t48087006",
	  "Samnorwood\t48087011",
	  "Wellington\t48087016"
	),
	"48089" => array(
	  "Alleyton\t48089001",
	  "Altair\t48089006",
	  "Columbus\t48089011",
	  "Eagle Lake\t48089016",
	  "Garwood\t48089021",
	  "Glidden\t48089026",
	  "Nada\t48089031",
	  "Oakland\t48089036",
	  "Rock Island\t48089041",
	  "Sheridan\t48089046",
	  "Weimar\t48089051"
	),
	"48091" => array(
	  "Bulverde\t48091001",
	  "Canyon Lake\t48091006",
	  "Fischer\t48091011",
	  "Garden Ridge\t48091016",
	  "New Braunfels\t48091021",
	  "San Antonio\t48091026",
	  "Spring Branch\t48091031"
	),
	"48093" => array(
	  "Comanche\t48093001",
	  "De Leon\t48093006",
	  "Energy\t48093011",
	  "Gustine\t48093016",
	  "Hasse\t48093021",
	  "Proctor\t48093026",
	  "Sidney\t48093031"
	),
	"48095" => array(
	  "Eden\t48095001",
	  "Eola\t48095006",
	  "Lowake\t48095011",
	  "Millersview\t48095016",
	  "Paint Rock\t48095021"
	),
	"48097" => array(
	  "Era\t48097001",
	  "Gainesville\t48097006",
	  "Lake Kiowa\t48097011",
	  "Lindsay\t48097016",
	  "Muenster\t48097021",
	  "Myra\t48097026",
	  "Rosston\t48097031",
	  "Valley View\t48097036"
	),
	"48099" => array(
	  "Bee House\t48099001",
	  "Copperas Cove\t48099006",
	  "Evant\t48099011",
	  "Flat\t48099016",
	  "Gatesville\t48099021",
	  "Izoro\t48099026",
	  "Jonesboro\t48099031",
	  "Leon Junction\t48099036",
	  "Mound\t48099041",
	  "Oglesby\t48099046",
	  "Purmela\t48099051"
	),
	"48101" => array(
	  "Cee Vee\t48101001",
	  "Chalk\t48101006",
	  "Nassau Bay\t48101012",
	  "Paducah\t48101011"
	),
	"48103" => array(
	  "Crane\t48103001"
	),
	"48105" => array(
	  "Ozona\t48105001"
	),
	"48107" => array(
	  "Cone\t48107001",
	  "Crosbyton\t48107006",
	  "Lorenzo\t48107011",
	  "Ralls\t48107016"
	),
	"48109" => array(
	  "Kent\t48109001",
	  "Van Horn\t48109006"
	),
	"48111" => array(
	  "Dalhart\t48111001",
	  "Kerrick\t48111006",
	  "Texline\t48111011"
	),
	"48113" => array(
	  "Addison\t48113001",
	  "Balch Springs\t48113006",
	  "Cedar Hill\t48113016",
	  "Combine\t48113021",
	  "Coppell\t48113026",
	  "Dallas\t48113031",
	  "De Soto\t48113036",
	  "Duncanville\t48113041",
	  "Farmers Branch\t48113046",
	  "Garland\t48113051",
	  "Grand Prairie\t48113056",
	  "Hutchins\t48113061",
	  "Irving\t48113066",
	  "Lancaster\t48113071",
	  "Mesquite\t48113076",
	  "Richardson\t48113081",
	  "Rowlett\t48113086",
	  "Sachse\t48113091",
	  "Seagoville\t48113096",
	  "St Paul\t48113101",
	  "Sunnyvale\t48113106",
	  "Wilmer\t48113111",
	  "Wylie\t48113116"
	),
	"48115" => array(
	  "Ackerly\t48115001",
	  "Lamesa\t48115006",
	  "Welch\t48115011"
	),
	"48117" => array(
	  "Dawn\t48117001",
	  "Hereford\t48117006"
	),
	"48119" => array(
	  "Ben Franklin\t48119001",
	  "Cooper\t48119006",
	  "Enloe\t48119011",
	  "Klondike\t48119016",
	  "Lake Creek\t48119021",
	  "Pecan Gap\t48119026"
	),
	"48121" => array(
	  "Argyle\t48121001",
	  "Aubrey\t48121006",
	  "Bartonville\t48121011",
	  "Carrollton\t48121016",
	  "Corinth\t48121107",
	  "Denton\t48121021",
	  "Double Oak\t48121026",
	  "Flower Mound\t48121031",
	  "Highland Village\t48121036",
	  "Justin\t48121041",
	  "Krum\t48121046",
	  "Lake Dallas\t48121051",
	  "Lakewood Village\t48121056",
	  "Lewisville\t48121061",
	  "Little Elm\t48121066",
	  "Oak Point\t48121071",
	  "Pilot Point\t48121076",
	  "Ponder\t48121081",
	  "Roanoke\t48121086",
	  "Sanger\t48121091",
	  "The Colony\t48121096",
	  "Trophy Club\t48121101",
	  "Westlake\t48121106"
	),
	"48123" => array(
	  "Cuero\t48123001",
	  "Hochheim\t48123006",
	  "Meyersville\t48123011",
	  "Nordheim\t48123016",
	  "Thomaston\t48123021",
	  "Westhoff\t48123026",
	  "Yorktown\t48123031"
	),
	"48125" => array(
	  "Afton\t48125001",
	  "Dickens\t48125006",
	  "McAdoo\t48125011",
	  "Spur\t48125016"
	),
	"48127" => array(
	  "Asherton\t48127001",
	  "Big Wells\t48127006",
	  "Carrizo Springs\t48127011",
	  "Catarina\t48127016"
	),
	"48129" => array(
	  "Clarendon\t48129001",
	  "Hedley\t48129006",
	  "Lelia Lake\t48129011"
	),
	"48131" => array(
	  "Benavides\t48131001",
	  "Concepcion\t48131006",
	  "Freer\t48131011",
	  "Realitos\t48131016",
	  "San Diego\t48131021"
	),
	"48133" => array(
	  "Carbon\t48133001",
	  "Cisco\t48133006",
	  "Desdemona\t48133011",
	  "Eastland\t48133016",
	  "Gorman\t48133021",
	  "Olden\t48133026",
	  "Ranger\t48133031",
	  "Rising Star\t48133036"
	),
	"48135" => array(
	  "Gardendale\t48135001",
	  "Goldsmith\t48135006",
	  "Notrees\t48135011",
	  "Odessa\t48135016",
	  "Penwell\t48135021"
	),
	"48137" => array(
	  "Barksdale\t48137001",
	  "Rocksprings\t48137006",
	  "Telegraph\t48137011"
	),
	"48139" => array(
	  "Avalon\t48139001",
	  "Bardwell\t48139006",
	  "Ennis\t48139011",
	  "Ferris\t48139016",
	  "Forreston\t48139021",
	  "Italy\t48139026",
	  "Maypearl\t48139031",
	  "Midlothian\t48139036",
	  "Milford\t48139041",
	  "Oak Leaf\t48139046",
	  "Ovilla\t48139051",
	  "Palmer\t48139056",
	  "Red Oak\t48139061",
	  "Rice\t48139066",
	  "Waxahachie\t48139071"
	),
	"48141" => array(
	  "Anthony\t48141001",
	  "Biggs Field\t48141006",
	  "Canutillo\t48141011",
	  "Clint\t48141016",
	  "El Paso\t48141021",
	  "Fabens\t48141026",
	  "Fort Bliss\t48141031",
	  "Horizon City\t48141036",
	  "San Elizario\t48141041",
	  "Socorro\t48141046",
	  "Tornillo\t48141051",
	  "Vinton\t48141056"
	),
	"48143" => array(
	  "Bluff Dale\t48143001",
	  "Dublin\t48143006",
	  "Lingleville\t48143011",
	  "Morgan Mill\t48143016",
	  "Stephenville\t48143021"
	),
	"48145" => array(
	  "Chilton\t48145001",
	  "Lott\t48145006",
	  "Marlin\t48145011",
	  "Otto\t48145016",
	  "Perry\t48145021",
	  "Reagan\t48145026",
	  "Rosebud\t48145031",
	  "Satin\t48145036"
	),
	"48147" => array(
	  "Bailey\t48147001",
	  "Bonham\t48147006",
	  "Dodd City\t48147011",
	  "Ector\t48147016",
	  "Gober\t48147021",
	  "Honey Grove\t48147026",
	  "Ivanhoe\t48147031",
	  "Ladonia\t48147036",
	  "Leonard\t48147041",
	  "Randolph\t48147046",
	  "Ravenna\t48147051",
	  "Savoy\t48147056",
	  "Telephone\t48147061",
	  "Trenton\t48147066",
	  "Windom\t48147071"
	),
	"48149" => array(
	  "Carmine\t48149001",
	  "Ellinger\t48149006",
	  "Fayetteville\t48149011",
	  "Flatonia\t48149016",
	  "La Grange\t48149021",
	  "Ledbetter\t48149026",
	  "Muldoon\t48149031",
	  "Plum\t48149036",
	  "Round Top\t48149041",
	  "Schulenburg\t48149046",
	  "Warda\t48149051",
	  "Warrenton\t48149056",
	  "West Point\t48149061",
	  "Winchester\t48149066"
	),
	"48151" => array(
	  "Mc Caulley\t48151001",
	  "Roby\t48151006",
	  "Rotan\t48151011",
	  "Sylvester\t48151016"
	),
	"48153" => array(
	  "Aiken\t48153001",
	  "Dougherty\t48153006",
	  "Floydada\t48153011",
	  "Lockney\t48153016",
	  "South Plains\t48153021"
	),
	"48155" => array(
	  "Crowell\t48155001",
	  "Truscott\t48155006"
	),
	"48157" => array(
	  "Beasley\t48157001",
	  "Booth\t48157006",
	  "Clodine\t48157011",
	  "Fresno\t48157016",
	  "Fulshear\t48157021",
	  "Guy\t48157026",
	  "Kendleton\t48157031",
	  "Meadows Place\t48157036",
	  "Missouri City\t48157041",
	  "Needville\t48157046",
	  "Orchard\t48157051",
	  "Richmond\t48157056",
	  "Rosenberg\t48157061",
	  "Simonton\t48157066",
	  "Stafford\t48157071",
	  "Sugar Land\t48157076",
	  "Sugar Land\t48157076",
	  "Thompsons\t48157081"
	),
	"48159" => array(
	  "Mount Vernon\t48159001",
	  "Scroggins\t48159006",
	  "Talco\t48159011"
	),
	"48161" => array(
	  "Donie\t48161001",
	  "Fairfield\t48161006",
	  "Kirvin\t48161011",
	  "Streetman\t48161016",
	  "Teague\t48161021",
	  "Wortham\t48161026"
	),
	"48163" => array(
	  "Bigfoot\t48163001",
	  "Dilley\t48163006",
	  "Moore\t48163011",
	  "Pearsall\t48163016"
	),
	"48165" => array(
	  "Loop\t48165001",
	  "Seagraves\t48165006",
	  "Seminole\t48165011"
	),
	"48167" => array(
	  "Alta Loma\t48167001",
	  "Arcadia\t48167006",
	  "Bacliff\t48167011",
	  "Clear Lake Shores\t48167016",
	  "Crystal Beach\t48167021",
	  "Dickinson\t48167026",
	  "Friendswood\t48167031",
	  "Galveston\t48167036",
	  "Gilchrist\t48167041",
	  "High Island\t48167046",
	  "Hitchcock\t48167051",
	  "Kemah\t48167056",
	  "La Marque\t48167061",
	  "League City\t48167066",
	  "Port Bolivar\t48167071",
	  "San Leon\t48167076",
	  "Santa FE\t48167081",
	  "Texas City\t48167086"
	),
	"48169" => array(
	  "Justiceburg\t48169001",
	  "Post\t48169006"
	),
	"48171" => array(
	  "Albert\t48171001",
	  "Doss\t48171006",
	  "Fredericksburg\t48171011",
	  "Harper\t48171016",
	  "Stonewall\t48171021",
	  "Willow City\t48171026"
	),
	"48173" => array(
	  "Garden City\t48173001",
	),
	"48175" => array(
	  "Berclair\t48175001",
	  "Fannin\t48175006",
	  "Goliad\t48175011",
	  "Weesatche\t48175016"
	),
	"48177" => array(
	  "Bebe\t48177001",
	  "Belmont\t48177006",
	  "Cost\t48177011",
	  "Gonzales\t48177016",
	  "Harwood\t48177021",
	  "Leesville\t48177026",
	  "Nixon\t48177031",
	  "Ottine\t48177036",
	  "Smiley\t48177041",
	  "Waelder\t48177046",
	  "Wrightsboro\t48177051"
	),
	"48179" => array(
	  "Alanreed\t48179001",
	  "Lefors\t48179006",
	  "McLean\t48179011",
	  "Pampa\t48179016"
	),
	"48181" => array(
	  "Bells\t48181001",
	  "Collinsville\t48181006",
	  "Denison\t48181011",
	  "Dorchester\t48181016",
	  "Gordonville\t48181021",
	  "Gunter\t48181026",
	  "Howe\t48181031",
	  "Pottsboro\t48181036",
	  "Sadler\t48181041",
	  "Sherman\t48181046",
	  "Southmayd\t48181051",
	  "Tioga\t48181056",
	  "Tom Bean\t48181061",
	  "Van Alstyne\t48181066",
	  "Whitesboro\t48181071",
	  "Whitewright\t48181076"
	),
	"48183" => array(
	  "Clarksville City\t48183001",
	  "Easton\t48183006",
	  "Gladewater\t48183011",
	  "Judson\t48183016",
	  "Kilgore\t48183021",
	  "Longview\t48183026",
	  "White Oak\t48183031"
	),
	"48185" => array(
	  "Anderson\t48185001",
	  "Bedias\t48185006",
	  "Iola\t48185011",
	  "Navasota\t48185016",
	  "Plantersville\t48185021",
	  "Richards\t48185026",
	  "Roans Prairie\t48185031",
	  "Shiro\t48185036",
	  "Singleton\t48185041"
	),
	"48187" => array(
	  "Cibolo\t48187001",
	  "Geronimo\t48187006",
	  "Kingsbury\t48187011",
	  "Marion\t48187016",
	  "McQueeney\t48187026",
	  "Schertz\t48187031",
	  "Seguin\t48187036",
	  "Selma\t48187041",
	  "Staples\t48187046"
	),
	"48189" => array(
	  "Abernathy\t48189001",
	  "Cotton Center\t48189006",
	  "Edmonson\t48189011",
	  "Hale Center\t48189016",
	  "Petersburg\t48189021",
	  "Plainview\t48189026"
	),
	"48191" => array(
	  "Estelline\t48191001",
	  "Lakeview\t48191006",
	  "Memphis\t48191011",
	  "Turkey\t48191016"
	),
	"48193" => array(
	  "Carlton\t48193001",
	  "Hamilton\t48193006",
	  "Hico\t48193011",
	  "Pottsville\t48193016"
	),
	"48195" => array(
	  "Gruver\t48195001",
	  "Morse\t48195006",
	  "Spearman\t48195011"
	),
	"48197" => array(
	  "Chillicothe\t48197001",
	  "Quanah\t48197006"
	),
	"48199" => array(
	  "Batson\t48199001",
	  "Kountze\t48199011",
	  "Lumberton\t48199016",
	  "Saratoga\t48199021",
	  "Silsbee\t48199026",
	  "Sour Lake\t48199031",
	  "Thicket\t48199036",
	  "Village Mills\t48199041",
	  "Votaw\t48199046"
	),
	"48201" => array(
	  "Alief\t48201001",
	  "Atascocita\t48201006",
	  "Barker\t48201011",
	  "Barrett\t48201016",
	  "Baytown\t48201021",
	  "Bellaire\t48201026",
	  "Channelview\t48201031",
	  "Clear Lake City\t48201182",
	  "Clutch City\t48201036",
	  "Crosby\t48201041",
	  "Cypress\t48201046",
	  "Deer Park\t48201051",
	  "El Lago\t48201056",
	  "Galena Park\t48201061",
	  "Highlands\t48201066",
	  "Hockley\t48201071",
	  "Houston\t48201076",
	  "Huffman\t48201081",
	  "Hufsmith\t48201086",
	  "Humble\t48201091",
	  "Jacinto City\t48201096",
	  "Jersey Village\t48201101",
	  "Katy\t48201106",
	  "Kingwood\t48201111",
	  "Klein\t48201116",
	  "La Porte\t48201121",
	  "North Houston\t48201126",
	  "Park Row\t48201131",
	  "Pasadena\t48201136",
	  "Seabrook\t48201141",
	  "Shoreacres\t48201146",
	  "South Houston\t48201151",
	  "Taylor Lake Village\t48201161",
	  "Tomball\t48201171",
	  "V A Hospital\t48201176",
	  "Webster\t48201181",
	  "West University Place\t48201183",
	  "The Woodlands\t48201166"
	),
	"48203" => array(
	  "Elysian Fields\t48203001",
	  "Hallsville\t48203006",
	  "Harleton\t48203011",
	  "Jonesville\t48203016",
	  "Karnack\t48203021",
	  "Marshall\t48203026",
	  "Scottsville\t48203031",
	  "Waskom\t48203036",
	  "Woodlawn\t48203041"
	),
	"48205" => array(
	  "Channing\t48205001",
	  "Hartley\t48205006"
	),
	"48207" => array(
	  "Haskell\t48207001",
	  "O Brien\t48207006",
	  "Rochester\t48207011",
	  "Rule\t48207016",
	  "Sagerton\t48207021",
	  "Weinert\t48207026"
	),
	"48209" => array(
	  "Buda\t48209001",
	  "Driftwood\t48209006",
	  "Dripping Springs\t48209011",
	  "Kyle\t48209016",
	  "Niederwald\t48209021",
	  "San Marcos\t48209026",
	  "Uhland\t48209031",
	  "Wimberley\t48209036"
	),
	"48211" => array(
	  "Canadian\t48211001",
	  "Glazier\t48211006"
	),
	"48213" => array(
	  "Athens\t48213001",
	  "Brownsboro\t48213006",
	  "Chandler\t48213011",
	  "Edom\t48213016",
	  "Eustace\t48213021",
	  "Kemp\t48213026",
	  "Larue\t48213031",
	  "Log Cabin\t48213036",
	  "Malakoff\t48213041",
	  "Murchison\t48213046",
	  "Poynor\t48213051",
	  "Seven Points\t48213056",
	  "Trinidad\t48213061"
	),
	"48215" => array(
	  "Alamo\t48215001",
	  "Alton\t48215006",
	  "Donna\t48215011",
	  "Edcouch\t48215016",
	  "Edinburg\t48215021",
	  "Elsa\t48215026",
	  "Hargill\t48215031",
	  "Hidalgo\t48215036",
	  "La Blanca\t48215041",
	  "La Joya\t48215046",
	  "La Villa\t48215051",
	  "Linn\t48215056",
	  "Los Ebanos\t48215061",
	  "McAllen\t48215066",
	  "Mercedes\t48215071",
	  "Mission\t48215076",
	  "Monte Alto\t48215081",
	  "Penitas\t48215086",
	  "Pharr\t48215091",
	  "Progreso\t48215096",
	  "Progreso Lakes\t48215101",
	  "San Juan\t48215106",
	  "Sullivan City\t48215111",
	  "Weslaco\t48215116"
	),
	"48217" => array(
	  "Abbott\t48217001",
	  "Aquilla\t48217006",
	  "Birome\t48217011",
	  "Blum\t48217016",
	  "Bonanza\t48217021",
	  "Brandon\t48217026",
	  "Bynum\t48217031",
	  "Covington\t48217036",
	  "Hillsboro\t48217041",
	  "Hubbard\t48217046",
	  "Irene\t48217051",
	  "Itasca\t48217056",
	  "Malone\t48217061",
	  "Mertens\t48217066",
	  "Mount Calm\t48217071",
	  "Penelope\t48217076",
	  "Whitney\t48217081"
	),
	"48219" => array(
	  "Anton\t48219001",
	  "Levelland\t48219006",
	  "Pep\t48219011",
	  "Ropesville\t48219016",
	  "Smyer\t48219021",
	  "Sundown\t48219026",
	  "Whitharral\t48219031"
	),
	"48221" => array(
	  "Cresson\t48221001",
	  "Granbury\t48221006",
	  "Lipan\t48221011",
	  "Paluxy\t48221016",
	  "Tolar\t48221021"
	),
	"48223" => array(
	  "Brashear\t48223001",
	  "Como\t48223006",
	  "Cumby\t48223011",
	  "Dike\t48223016",
	  "Pickton\t48223021",
	  "Saltillo\t48223026",
	  "Sulphur Bluff\t48223031",
	  "Sulphur Springs\t48223036"
	),
	"48225" => array(
	  "Austonio\t48225001",
	  "Crockett\t48225006",
	  "Grapeland\t48225011",
	  "Kennard\t48225016",
	  "Latexo\t48225021",
	  "Lovelady\t48225026",
	  "Ratcliff\t48225031"
	),
	"48227" => array(
	  "Big Spring\t48227001",
	  "Coahoma\t48227006",
	  "Forsan\t48227011",
	  "Knott\t48227016",
	  "Vealmoor\t48227021"
	),
	"48229" => array(
	  "Dell City\t48229001",
	  "Fort Hancock\t48229006",
	  "Salt Flat\t48229011",
	  "Sierra Blanca\t48229016"
	),
	"48231" => array(
	  "Caddo Mills\t48231001",
	  "Campbell\t48231006",
	  "Celeste\t48231011",
	  "Commerce\t48231016",
	  "Greenville\t48231021",
	  "Lone Oak\t48231026",
	  "Merit\t48231031",
	  "Quinlan\t48231036",
	  "West Tawakoni\t48231041",
	  "Wolfe City\t48231046"
	),
	"48233" => array(
	  "Borger\t48233001",
	  "Fritch\t48233006",
	  "Phillips\t48233011",
	  "Sanford\t48233016",
	  "Stinnett\t48233021"
	),
	"48235" => array(
	  "Barnhart\t48235001",
	  "Mertzon\t48235006"
	),
	"48237" => array(
	  "Bryson\t48237001",
	  "Jacksboro\t48237006",
	  "Jermyn\t48237011",
	  "Perrin\t48237016"
	),
	"48239" => array(
	  "Edna\t48239001",
	  "Francitas\t48239006",
	  "Ganado\t48239011",
	  "La Salle\t48239016",
	  "La Ward\t48239021",
	  "Lolita\t48239026",
	  "Vanderbilt\t48239031"
	),
	"48241" => array(
	  "Bon Ami\t48241001",
	  "Buna\t48241006",
	  "Evadale\t48241011",
	  "Jasper\t48241016",
	  "Kirbyville\t48241021",
	  "Magnolia Springs\t48241026",
	  "Roganville\t48241031",
	  "Sam Rayburn\t48241036"
	),
	"48243" => array(
	  "Fort Davis\t48243001",
	  "Valentine\t48243006"
	),
	"48245" => array(
	  "Beaumont\t48245001",
	  "China\t48245006",
	  "Groves\t48245011",
	  "Hamshire\t48245016",
	  "Nederland\t48245021",
	  "Nome\t48245026",
	  "Port Acres\t48245031",
	  "Port Arthur\t48245036",
	  "Port Neches\t48245041",
	  "Sabine Pass\t48245046",
	  "Voth\t48245051"
	),
	"48247" => array(
	  "Guerra\t48247001",
	  "Hebbronville\t48247006"
	),
	"48249" => array(
	  "Alice\t48249001",
	  "Ben Bolt\t48249006",
	  "Orange Grove\t48249011",
	  "Premont\t48249016",
	  "Sandia\t48249021"
	),
	"48251" => array(
	  "Alvarado\t48251001",
	  "Burleson\t48251006",
	  "Cleburne\t48251011",
	  "Godley\t48251016",
	  "Grandview\t48251021",
	  "Joshua\t48251026",
	  "Keene\t48251031",
	  "Lillian\t48251036",
	  "Rio Vista\t48251041",
	  "Venus\t48251046"
	),
	"48253" => array(
	  "Anson\t48253001",
	  "Avoca\t48253006",
	  "Hamlin\t48253011",
	  "Hawley\t48253016",
	  "Lueders\t48253021",
	  "Stamford\t48253026"
	),
	"48255" => array(
	  "Ecleto\t48255001",
	  "Falls City\t48255006",
	  "Gillett\t48255011",
	  "Hobson\t48255016",
	  "Karnes City\t48255021",
	  "Kenedy\t48255026",
	  "Panna Maria\t48255031",
	  "Runge\t48255036"
	),
	"48257" => array(
	  "Crandall\t48257001",
	  "Elmo\t48257006",
	  "Forney\t48257011",
	  "Gun Barrel City\t48257016",
	  "Kaufman\t48257021",
	  "Mabank\t48257026",
	  "Rosser\t48257031",
	  "Scurry\t48257036",
	  "Terrell\t48257041"
	),
	"48259" => array(
	  "Bergheim\t48259001",
	  "Boerne\t48259006",
	  "Comfort\t48259011",
	  "Fair Oaks Ranch\t48259016",
	  "Kendalia\t48259021",
	  "Sisterdale\t48259026",
	  "Waring\t48259031"
	),
	"48261" => array(
	  "Armstrong\t48261001",
	  "Sarita\t48261006"
	),
	"48263" => array(
	  "Girard\t48263001",
	  "Jayton\t48263006"
	),
	"48265" => array(
	  "Camp Verde\t48265001",
	  "Center Point\t48265006",
	  "Hunt\t48265011",
	  "Ingram\t48265016",
	  "Kerrville\t48265021",
	  "Mountain Home\t48265026"
	),
	"48267" => array(
	  "Junction\t48267001",
	  "London\t48267006",
	  "Roosevelt\t48267011"
	),
	"48269" => array(
	  "Dumont\t48269001",
	  "Guthrie\t48269006"
	),
	"48271" => array(
	  "Brackettville\t48271001"
	),
	"48273" => array(
	  "Kingsville\t48273001",
	  "Kingsville Naval\t48273006",
	  "Riviera\t48273011"
	),
	"48275" => array(
	  "Benjamin\t48275001",
	  "Goree\t48275006",
	  "Knox City\t48275011",
	  "Munday\t48275016"
	),
	"48277" => array(
	  "Arthur City\t48277001",
	  "Blossom\t48277006",
	  "Brookston\t48277011",
	  "Chicota\t48277016",
	  "Cunningham\t48277021",
	  "Deport\t48277026",
	  "Paris\t48277031",
	  "Pattonville\t48277036",
	  "Petty\t48277041",
	  "Powderly\t48277046",
	  "Reno\t48277051",
	  "Roxton\t48277056",
	  "Sumner\t48277061"
	),
	"48279" => array(
	  "Amherst\t48279001",
	  "Earth\t48279006",
	  "Fieldton\t48279011",
	  "Littlefield\t48279016",
	  "Olton\t48279021",
	  "Spade\t48279026",
	  "Springlake\t48279031",
	  "Sudan\t48279036"
	),
	"48281" => array(
	  "Kempner\t48281001",
	  "Lampasas\t48281006",
	  "Lometa\t48281011"
	),
	"48283" => array(
	  "Artesia Wells\t48283001",
	  "Cotula\t48283017",
	  "Cotulla\t48283006",
	  "Encinal\t48283011",
	  "Fowlerton\t48283016"
	),
	"48285" => array(
	  "Hallettsville\t48285001",
	  "Moulton\t48285006",
	  "Shiner\t48285011",
	  "Speaks\t48285016",
	  "Sublime\t48285021",
	  "Sweet Home\t48285026",
	  "Yoakum\t48285031"
	),
	"48287" => array(
	  "Dime Box\t48287001",
	  "Giddings\t48287006",
	  "Lexington\t48287011",
	  "Lincoln\t48287016"
	),
	"48289" => array(
	  "Buffalo\t48289001",
	  "Centerville\t48289006",
	  "Concord\t48289011",
	  "Flynn\t48289016",
	  "Hilltop Lakes\t48289021",
	  "Jewett\t48289026",
	  "Keechi\t48289031",
	  "Leona\t48289036",
	  "Marquez\t48289041",
	  "Normangee\t48289046",
	  "Oakwood\t48289051"
	),
	"48291" => array(
	  "Ames\t48291001",
	  "Cleveland\t48291006",
	  "Daisetta\t48291011",
	  "Dayton\t48291016",
	  "Devers\t48291021",
	  "Hardin\t48291026",
	  "Hull\t48291031",
	  "Liberty\t48291036",
	  "Raywood\t48291041",
	  "Romayor\t48291046",
	  "Rye\t48291051"
	),
	"48293" => array(
	  "Coolidge\t48293001",
	  "Groesbeck\t48293006",
	  "Kosse\t48293011",
	  "Mexia\t48293016",
	  "Prairie Hill\t48293021",
	  "Tehuacana\t48293026",
	  "Thornton\t48293031"
	),
	"48295" => array(
	  "Booker\t48295001",
	  "Darrouzett\t48295006",
	  "Follett\t48295011",
	  "Higgins\t48295016",
	  "Lipscomb\t48295021"
	),
	"48297" => array(
	  "Dinero\t48297006",
	  "George West\t48297011",
	  "Oakville\t48297016",
	  "Three Rivers\t48297021",
	  "Whitsett\t48297026"
	),
	"48299" => array(
	  "Bluffton\t48299001",
	  "Buchanan Dam\t48299006",
	  "Castell\t48299011",
	  "Kingsland\t48299016",
	  "Llano\t48299021",
	  "Sunrise Beach\t48299026",
	  "Tow\t48299031",
	  "Valley Spring\t48299036"
	),
	"48301" => array(
	  "Mentone\t48301001"
	),
	"48303" => array(
	  "Idalou\t48303001",
	  "Lubbock\t48303006",
	  "New Deal\t48303011",
	  "Ransom Canyon\t48303016",
	  "Reese Air Force Base\t48303021",
	  "Shallowater\t48303026",
	  "Slaton\t48303031",
	  "Southland\t48303036",
	  "Wolfforth\t48303041"
	),
	"48305" => array(
	  "New Home\t48305001",
	  "Odonnell\t48305006",
	  "Tahoka\t48305011",
	  "Wilson\t48305016"
	),
	"48307" => array(
	  "Brady\t48307001",
	  "Doole\t48307006",
	  "Fife\t48307011",
	  "Lohn\t48307016",
	  "Melvin\t48307021",
	  "Pear Valley\t48307026",
	  "Rochelle\t48307031",
	  "Voca\t48307036"
	),
	"48309" => array(
	  "Axtell\t48309001",
	  "Bellmead\t48309006",
	  "Beverly Hills\t48309011",
	  "Bruceville\t48309016",
	  "China Spring\t48309021",
	  "Crawford\t48309026",
	  "Eddy\t48309031",
	  "Elm Mott\t48309036",
	  "Hewitt\t48309041",
	  "Lacy Lakeview\t48309046",
	  "Leroy\t48309051",
	  "Lorena\t48309056",
	  "Mart\t48309061",
	  "McGregor\t48309066",
	  "McGregor\t48309066",
	  "Moody\t48309071",
	  "Riesel\t48309076",
	  "Ross\t48309081",
	  "Waco\t48309086",
	  "West\t48309091",
	  "Woodway\t48309096"
	),
	"48311" => array(
	  "Calliham\t48311001",
	  "Tilden\t48311006"
	),
	"48313" => array(
	  "Madisonville\t48313001",
	  "Midway\t48313006",
	  "North Zulch\t48313011"
	),
	"48315" => array(
	  "Jefferson\t48315001",
	  "Lodi\t48315006",
	  "Smithland\t48315011"
	),
	"48317" => array(
	  "Lenorah\t48317001",
	  "Stanton\t48317006",
	  "Tarzan\t48317011"
	),
	"48319" => array(
	  "Art\t48319001",
	  "Fredonia\t48319006",
	  "Mason\t48319011",
	  "Pontotoc\t48319016"
	),
	"48321" => array(
	  "Bay City\t48321001",
	  "Blessing\t48321006",
	  "Cedar Lane\t48321011",
	  "Clemville\t48321016",
	  "Collegeport\t48321021",
	  "Elmaton\t48321026",
	  "Markham\t48321031",
	  "Matagorda\t48321036",
	  "Midfield\t48321041",
	  "Palacios\t48321046",
	  "Pledger\t48321051",
	  "Sargent\t48321056",
	  "Van Vleck\t48321061",
	  "Wadsworth\t48321066"
	),
	"48323" => array(
	  "Eagle Pass\t48323001",
	  "El Indio\t48323006",
	  "Quemado\t48323011",
	  "Spofford\t48323016"
	),
	"48325" => array(
	  "Castroville\t48325001",
	  "D Hanis\t48325006",
	  "Devine\t48325011",
	  "Dunlay\t48325016",
	  "Hondo\t48325021",
	  "La Coste\t48325026",
	  "Mico\t48325031",
	  "Natalia\t48325036",
	  "Rio Medina\t48325041",
	  "Yancey\t48325046"
	),
	"48327" => array(
	  "Fort Mc Kavett\t48327001",
	  "Hext\t48327006",
	  "Menard\t48327011"
	),
	"48329" => array(
	  "Midland\t48329001"
	),
	"48331" => array(
	  "Ben Arnold\t48331001",
	  "Buckholts\t48331006",
	  "Burlington\t48331011",
	  "Cameron\t48331016",
	  "Davilla\t48331021",
	  "Gause\t48331026",
	  "Maysfield\t48331031",
	  "Milano\t48331036",
	  "Rockdale\t48331041",
	  "Thorndale\t48331046"
	),
	"48333" => array(
	  "Goldthwaite\t48333001",
	  "Mullin\t48333006",
	  "Priddy\t48333011",
	  "Star\t48333016"
	),
	"48335" => array(
	  "Colorado City\t48335001",
	  "Loraine\t48335006",
	  "Westbrook\t48335011"
	),
	"48337" => array(
	  "Bowie\t48337001",
	  "Forestburg\t48337006",
	  "Montague\t48337011",
	  "Nocona\t48337016",
	  "Ringgold\t48337021",
	  "Saint Jo\t48337026",
	  "Sunset\t48337031"
	),
	"48339" => array(
	  "Conroe\t48339001",
	  "Cut and Shoot\t48339006",
	  "Dobbin\t48339011",
	  "Grangerland\t48339016",
	  "Magnolia\t48339021",
	  "Montgomery\t48339026",
	  "New Caney\t48339031",
	  "Panorama Village\t48339036",
	  "Patton\t48339041",
	  "Pinehurst\t48339046",
	  "Porter\t48339051",
	  "Splendora\t48339056",
	  "Spring\t48339061",
	  "The Woodlands\t48339066",
	  "Willis\t48339071"
	),
	"48341" => array(
	  "Cactus\t48341001",
	  "Dumas\t48341006",
	  "Masterson\t48341011",
	  "Sunray\t48341016"
	),
	"48343" => array(
	  "Cason\t48343001",
	  "Daingerfield\t48343006",
	  "Lone Star\t48343011",
	  "Naples\t48343016",
	  "Omaha\t48343021"
	),
	"48345" => array(
	  "Flomot\t48345001",
	  "Matador\t48345006",
	  "Roaring Springs\t48345011"
	),
	"48347" => array(
	  "Chireno\t48347001",
	  "Cushing\t48347006",
	  "Douglass\t48347011",
	  "Etoile\t48347016",
	  "Garrison\t48347021",
	  "Martinsville\t48347026",
	  "Nacogdoches\t48347031",
	  "Sacul\t48347036",
	  "Woden\t48347041"
	),
	"48349" => array(
	  "Barry\t48349001",
	  "Blooming Grove\t48349006",
	  "Chatfield\t48349011",
	  "Corsicana\t48349016",
	  "Dawson\t48349021",
	  "Frost\t48349026",
	  "Kerens\t48349031",
	  "Powell\t48349036",
	  "Purdon\t48349041",
	  "Richland\t48349046"
	),
	"48351" => array(
	  "Bon Wier\t48351001",
	  "Burkeville\t48351006",
	  "Call\t48351011",
	  "Deweyville\t48351016",
	  "Newton\t48351021",
	  "Wiergate\t48351026"
	),
	"48353" => array(
	  "Blackwell\t48353001",
	  "Maryneal\t48353006",
	  "Nolan\t48353011",
	  "Roscoe\t48353016",
	  "Sweetwater\t48353021"
	),
	"48355" => array(
	  "Agua Dulce\t48355001",
	  "Banquete\t48355006",
	  "Bishop\t48355011",
	  "Chapman Ranch\t48355016",
	  "Corpus Christi\t48355021",
	  "Driscoll\t48355026",
	  "Port Aransas\t48355031",
	  "Robstown\t48355036"
	),
	"48357" => array(
	  "Farnsworth\t48357001",
	  "Perryton\t48357006",
	  "Waka\t48357011"
	),
	"48359" => array(
	  "Adrian\t48359001",
	  "Boys Ranch\t48359006",
	  "Valle de Oro\t48359011",
	  "Vega\t48359016",
	  "Wildorado\t48359021"
	),
	"48361" => array(
	  "Bridge City\t48361001",
	  "Mauriceville\t48361006",
	  "Orange\t48361011",
	  "Orangefield\t48361016",
	  "Vidor\t48361021",
	  "West Orange\t48361026"
	),
	"48363" => array(
	  "Gordon\t48363001",
	  "Graford\t48363006",
	  "Mineral Wells\t48363011",
	  "Mingus\t48363016",
	  "Palo Pinto\t48363021",
	  "Santo\t48363026",
	  "Strawn\t48363031"
	),
	"48365" => array(
	  "Beckville\t48365001",
	  "Carthage\t48365006",
	  "Clayton\t48365011",
	  "De Berry\t48365016",
	  "Gary\t48365021",
	  "Long Branch\t48365026",
	  "Panola\t48365031"
	),
	"48367" => array(
	  "Aledo\t48367001",
	  "Dennis\t48367011",
	  "Millsap\t48367016",
	  "Peaster\t48367021",
	  "Poolville\t48367026",
	  "Springtown\t48367031",
	  "Weatherford\t48367036",
	  "Whitt\t48367041"
	),
	"48369" => array(
	  "Black\t48369001",
	  "Bovina\t48369006",
	  "Farwell\t48369011",
	  "Friona\t48369016",
	  "Lazbuddie\t48369021"
	),
	"48371" => array(
	  "Coyanosa\t48371001",
	  "Fort Stockton\t48371006",
	  "Girvin\t48371011",
	  "Imperial\t48371016",
	  "Iraan\t48371021",
	  "Sheffield\t48371026"
	),
	"48373" => array(
	  "Ace\t48373001",
	  "Barnum\t48373006",
	  "Camden\t48373011",
	  "Corrigan\t48373016",
	  "Dallardsville\t48373021",
	  "Goodrich\t48373026",
	  "Leggett\t48373031",
	  "Livingston\t48373036",
	  "Moscow\t48373041",
	  "Onalaska\t48373046",
	  "Segno\t48373051"
	),
	"48375" => array(
	  "Bushland\t48375006",
	),
	"48377" => array(
	  "Marfa\t48377001",
	  "Presidio\t48377006",
	  "Redford\t48377011",
	  "Shafter\t48377016"
	),
	"48379" => array(
	  "Alba\t48379001",
	  "Emory\t48379006",
	  "Point\t48379011"
	),
	"48381" => array(
	  "Amarillo\t48381001",
	  "Canyon\t48381006",
	  "Umbarger\t48381011"
	),
	"48383" => array(
	  "Best\t48383001",
	  "Big Lake\t48383006",
	  "Texon\t48383011"
	),
	"48385" => array(
	  "Camp Wood\t48385001",
	  "Leakey\t48385006",
	  "Rio Frio\t48385011"
	),
	"48387" => array(
	  "Annona\t48387001",
	  "Avery\t48387006",
	  "Bagwell\t48387011",
	  "Bogata\t48387016",
	  "Clarksville\t48387021",
	  "Detroit\t48387026"
	),
	"48389" => array(
	  "Balmorhea\t48389001",
	  "Orla\t48389006",
	  "Pecos\t48389011",
	  "Saragosa\t48389016",
	  "Toyah\t48389021",
	  "Toyahvale\t48389026",
	  "Verhalen\t48389031"
	),
	"48391" => array(
	  "Austwell\t48391001",
	  "Bayside\t48391006",
	  "Refugio\t48391011",
	  "Tivoli\t48391016",
	  "Woodsboro\t48391021"
	),
	"48393" => array(
	  "Miami\t48393001"
	),
	"48395" => array(
	  "Bremond\t48395001",
	  "Calvert\t48395006",
	  "Franklin\t48395011",
	  "Hearne\t48395016",
	  "Mumford\t48395021",
	  "New Baden\t48395026",
	  "Ridge\t48395031",
	  "Wheelock\t48395036"
	),
	"48397" => array(
	  "Fate\t48397001",
	  "Heath\t48397006",
	  "Rockwall\t48397011",
	  "Royse City\t48397016"
	),
	"48399" => array(
	  "Ballinger\t48399001",
	  "Miles\t48399006",
	  "Norton\t48399011",
	  "Rowena\t48399016",
	  "Wingate\t48399021",
	  "Winters\t48399026"
	),
	"48401" => array(
	  "Henderson\t48401001",
	  "Joinerville\t48401006",
	  "Laird Hill\t48401011",
	  "Laneville\t48401016",
	  "Minden\t48401021",
	  "Mount Enterprise\t48401026",
	  "New London\t48401031",
	  "Overton\t48401036",
	  "Price\t48401041",
	  "Selman City\t48401046",
	  "Tatum\t48401051",
	  "Turnertown\t48401056"
	),
	"48403" => array(
	  "Bronson\t48403001",
	  "Brookeland\t48403006",
	  "Geneva\t48403011",
	  "Hemphill\t48403016",
	  "Milam\t48403021",
	  "Pineland\t48403026"
	),
	"48405" => array(
	  "Broaddus\t48405001",
	  "San Augustine\t48405006"
	),
	"48407" => array(
	  "Coldspring\t48407001",
	  "Oakhurst\t48407006",
	  "Pointblank\t48407011",
	  "Shepherd\t48407016"
	),
	"48409" => array(
	  "Aransas Pass\t48409001",
	  "Edroy\t48409006",
	  "Gregory\t48409011",
	  "Ingleside\t48409016",
	  "Mathis\t48409021",
	  "Odem\t48409026",
	  "Portland\t48409031",
	  "Sinton\t48409036",
	  "Taft\t48409041"
	),
	"48411" => array(
	  "Bend\t48411001",
	  "Cherokee\t48411006",
	  "Richland Springs\t48411011",
	  "San Saba\t48411016"
	),
	"48413" => array(
	  "Eldorado\t48413001",
	  "Eldorado AFS\t48413006"
	),
	"48415" => array(
	  "Dermott\t48415001",
	  "Dunn\t48415006",
	  "Fluvanna\t48415011",
	  "Hermleigh\t48415016",
	  "Ira\t48415021",
	  "Snyder\t48415026"
	),
	"48417" => array(
	  "Albany\t48417001",
	  "Moran\t48417006"
	),
	"48419" => array(
	  "Center\t48419001",
	  "Joaquin\t48419006",
	  "Shelbyville\t48419011",
	  "Tenaha\t48419016",
	  "Timpson\t48419021"
	),
	"48421" => array(
	  "Stratford\t48421001"
	),
	"48423" => array(
	  "Arp\t48423001",
	  "Bullard\t48423006",
	  "Flint\t48423011",
	  "Garden Valley\t48423016",
	  "Lindale\t48423021",
	  "Mount Selman\t48423026",
	  "Mt Sylvan\t48423031",
	  "Troup\t48423036",
	  "Tyler\t48423041",
	  "Whitehouse\t48423046",
	  "Winona\t48423051"
	),
	"48425" => array(
	  "Glen Rose\t48425001",
	  "Nemo\t48425006",
	  "Rainbow\t48425011"
	),
	"48427" => array(
	  "Delmita\t48427001",
	  "Falcon Heights\t48427006",
	  "Garciasville\t48427011",
	  "Grulla\t48427016",
	  "Rio Grande City\t48427021",
	  "Roma\t48427026",
	  "Salineno\t48427031",
	  "San Isidro\t48427036",
	  "Santa Elena\t48427041"
	),
	"48429" => array(
	  "Breckenridge\t48429001",
	  "Caddo\t48429006"
	),
	"48431" => array(
	  "Sterling City\t48431001"
	),
	"48433" => array(
	  "Aspermont\t48433001",
	  "Old Glory\t48433006"
	),
	"48435" => array(
	  "Sonora\t48435001"
	),
	"48437" => array(
	  "Happy\t48437001",
	  "Kress\t48437006",
	  "Tulia\t48437011",
	  "Vigo Park\t48437016"
	),
	"48439" => array(
	  "Arlington\t48439001",
	  "Azle\t48439006",
	  "Bedford\t48439011",
	  "Benbrook\t48439016",
	  "Blue Mound\t48439021",
	  "Colleyville\t48439026",
	  "Crowley\t48439031",
	  "Euless\t48439036",
	  "Everman\t48439041",
	  "Fort Worth\t48439046",
	  "Grapevine\t48439051",
	  "Haltom City\t48439056",
	  "Haslet\t48439061",
	  "Hurst\t48439066",
	  "Keller\t48439071",
	  "Kennedale\t48439076",
	  "Lake Worth\t48439081",
	  "Mansfield\t48439086",
	  "Naval Air Station/ jrb\t48439091",
	  "North Richland Hills\t48439096",
	  "Pantego\t48439101",
	  "Richland Hills\t48439106",
	  "River Oaks\t48439111",
	  "Saginaw\t48439116",
	  "Southlake\t48439121",
	  "Watauga\t48439126",
	  "White Settlement\t48439131"
	),
	"48441" => array(
	  "Abilene\t48441001",
	  "Buffalo Gap\t48441006",
	  "Dyess AFB\t48441011",
	  "Lawn\t48441016",
	  "Merkel\t48441021",
	  "Ovalo\t48441026",
	  "Trent\t48441031",
	  "Tuscola\t48441036",
	  "Tye\t48441041"
	),
	"48443" => array(
	  "Dryden\t48443006",
	  "Sanderson\t48443011"
	),
	"48445" => array(
	  "Brownfield\t48445001",
	  "Meadow\t48445006",
	  "Wellman\t48445011"
	),
	"48447" => array(
	  "Throckmorton\t48447001",
	  "Woodson\t48447006"
	),
	"48449" => array(
	  "Cookville\t48449001",
	  "Mount Pleasant\t48449006",
	  "Winfield\t48449011"
	),
	"48451" => array(
	  "Carlsbad\t48451001",
	  "Christoval\t48451006",
	  "Goodfellow AFB\t48451011",
	  "Knickerbocker\t48451016",
	  "Mereta\t48451021",
	  "San Angelo\t48451026",
	  "Vancourt\t48451031",
	  "Veribest\t48451036",
	  "Wall\t48451041",
	  "Water Valley\t48451046"
	),
	"48453" => array(
	  "Briarcliff\t48453006",
	  "Creedmoor\t48453011",
	  "Del Valle\t48453016",
	  "Jonestown\t48453021",
	  "Lago Vista\t48453026",
	  "Lakeway\t48453031",
	  "Manchaca\t48453041",
	  "Manor\t48453046",
	  "Mc Neil\t48453051",
	  "McNeil\t48453056",
	  "Pflugerville\t48453061",
	  "Rollingwood\t48453066",
	  "Spicewood\t48453071",
	  "West Lake Hills\t48453076"
	),
	"48455" => array(
	  "Apple Springs\t48455001",
	  "Centralia\t48455006",
	  "Groveton\t48455011",
	  "Pennington\t48455016",
	  "Trinity\t48455021",
	  "Woodlake\t48455026"
	),
	"48457" => array(
	  "Chester\t48457001",
	  "Colmesneil\t48457006",
	  "Dogwood\t48457011",
	  "Doucette\t48457016",
	  "Fred\t48457021",
	  "Hillister\t48457026",
	  "Rockland\t48457031",
	  "Spurger\t48457036",
	  "Warren\t48457041",
	  "Woodville\t48457046"
	),
	"48459" => array(
	  "Big Sandy\t48459001",
	  "Diana\t48459006",
	  "Gilmer\t48459011",
	  "New Diana\t48459016",
	  "Ore City\t48459021"
	),
	"48461" => array(
	  "Mc Camey\t48461001",
	  "McCamey\t48461006",
	  "Midkiff\t48461011",
	  "Rankin\t48461016"
	),
	"48463" => array(
	  "Concan\t48463001",
	  "Knippa\t48463006",
	  "Sabinal\t48463011",
	  "Utopia\t48463016",
	  "Uvalde\t48463021"
	),
	"48465" => array(
	  "Comstock\t48465001",
	  "Del Rio\t48465006",
	  "Langtry\t48465011",
	  "Laughlin AFB\t48465021"
	),
	"48467" => array(
	  "Ben Wheeler\t48467001",
	  "Canton\t48467006",
	  "Edgewood\t48467011",
	  "Fruitvale\t48467016",
	  "Grand Saline\t48467021",
	  "Van\t48467026",
	  "Wills Point\t48467031"
	),
	"48469" => array(
	  "Bloomington\t48469001",
	  "Inez\t48469006",
	  "McFaddin\t48469011",
	  "Nursery\t48469016",
	  "Placedo\t48469021",
	  "Raisin\t48469026",
	  "Telferner\t48469031",
	  "Victoria\t48469036"
	),
	"48471" => array(
	  "Dodge\t48471001",
	  "Huntsville\t48471006",
	  "New Waverly\t48471011",
	  "Riverside\t48471016"
	),
	"48473" => array(
	  "Brookshire\t48473001",
	  "Hempstead\t48473006",
	  "Pattison\t48473011",
	  "Prairie View\t48473016",
	  "Waller\t48473021"
	),
	"48475" => array(
	  "Barstow\t48475001",
	  "Grandfalls\t48475006",
	  "Monahans\t48475011",
	  "Pyote\t48475016",
	  "Royalty\t48475021",
	  "Wickett\t48475026"
	),
	"48477" => array(
	  "Brenham\t48477001",
	  "Burton\t48477006",
	  "Chappell Hill\t48477011",
	  "Washington\t48477016"
	),
	"48479" => array(
	  "Bruni\t48479001",
	  "Laredo\t48479006",
	  "Mirando City\t48479011",
	  "Oilton\t48479016",
	  "Rio Bravo\t48479021"
	),
	"48481" => array(
	  "Boling\t48481001",
	  "Danevang\t48481006",
	  "East Bernard\t48481011",
	  "Egypt\t48481016",
	  "El Campo\t48481021",
	  "Glen Flora\t48481026",
	  "Hungerford\t48481031",
	  "Lane City\t48481036",
	  "Lissie\t48481041",
	  "Louise\t48481046",
	  "Newgulf\t48481051",
	  "Pierce\t48481056",
	  "Wharton\t48481061"
	),
	"48483" => array(
	  "Allison\t48483001",
	  "Briscoe\t48483006",
	  "Mobeetie\t48483011",
	  "Shamrock\t48483016",
	  "Twitty\t48483021",
	  "Wheeler\t48483026"
	),
	"48485" => array(
	  "Burkburnett\t48485001",
	  "Electra\t48485006",
	  "Iowa Park\t48485011",
	  "Kamay\t48485016",
	  "Sheppard AFB\t48485021",
	  "Wichita Falls\t48485026"
	),
	"48487" => array(
	  "Harrold\t48487001",
	  "Odell\t48487006",
	  "Oklaunion\t48487011",
	  "Vernon\t48487016"
	),
	"48489" => array(
	  "Lasara\t48489001",
	  "Lyford\t48489006",
	  "Port Mansfield\t48489011",
	  "Raymondville\t48489016",
	  "San Perlita\t48489021",
	  "Sebastian\t48489026"
	),
	"48491" => array(
	  "Andice\t48491001",
	  "Austin\t48491006",
	  "Cedar Park\t48491011",
	  "Coupland\t48491016",
	  "Florence\t48491021",
	  "Georgetown\t48491026",
	  "Granger\t48491031",
	  "Hutto\t48491036",
	  "Jarrell\t48491041",
	  "Leander\t48491046",
	  "Liberty Hill\t48491051",
	  "Round Rock\t48491056",
	  "Schwertner\t48491061",
	  "Sun City\t48491066",
	  "Taylor\t48491071",
	  "Thrall\t48491076",
	  "Walburg\t48491081",
	  "Weir\t48491086"
	),
	"48493" => array(
	  "Floresville\t48493001",
	  "La Vernia\t48493006",
	#  "Lavernia\t48493011",
	  "Pandora\t48493016",
	  "Poth\t48493021",
	  "Recycle\t48493026",
	  "Stockdale\t48493031",
	  "Sutherland Springs\t48493036"
	),
	"48495" => array(
	  "Kermit\t48495001",
	  "Wink\t48495006"
	),
	"48497" => array(
	  "Alvord\t48497001",
	  "Boyd\t48497006",
	  "Bridgeport\t48497011",
	  "Chico\t48497016",
	  "Decatur\t48497021",
	  "Greenwood\t48497026",
	  "Newark\t48497031",
	  "Paradise\t48497036",
	  "Rhome\t48497041",
	  "Slidell\t48497046"
	),
	"48499" => array(
	  "Golden\t48499001",
	  "Hawkins\t48499006",
	  "Mineola\t48499011",
	  "Quitman\t48499016",
	  "Winnsboro\t48499021",
	  "Yantis\t48499026"
	),
	"48501" => array(
	  "Denver City\t48501001",
	  "Plains\t48501006",
	  "Tokio\t48501011"
	),
	"48503" => array(
	  "Elbert\t48503001",
	  "Eliasville\t48503006",
	  "Graham\t48503011",
	  "Loving\t48503016",
	  "Newcastle\t48503021",
	  "Olney\t48503026",
	  "South Bend\t48503031"
	),
	"48505" => array(
	  "Lopeno\t48505001",
	  "San Ygnacio\t48505006",
	  "Zapata\t48505011"
	),
	"48507" => array(
	  "Batesville\t48507001",
	  "Crystal City\t48507006",
	  "La Pryor\t48507011"
	)
);


define('INFO_EMAIL', 'info@randstatestats.org'); 

$countries = array('AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AD' => 'Andorra', 'AO' => 'Angola', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'The Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BR' => 'Brazil', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo, Republic of the', 'CD' => 'Congo, Democratic Republic of the', 'CR' => 'Costa Rica', 'CI' => 'Cote d\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'Timor-Leste', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GR' => 'Greece', 'GD' => 'Grenada', 'GT' => 'Guatemala', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HN' => 'Honduras', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea, North', 'KR' => 'Korea, South', 'ZZ' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar (Burma)', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PL' => 'Poland', 'PT' => 'Portugal', 'QA' => 'Qatar', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States of America', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican City (Holy See)', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');
?>