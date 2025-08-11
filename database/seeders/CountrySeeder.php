<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
        $countries = json_decode('{"data":['.
            '{"id":"1","iso":"AF","name":"AFGHANISTAN","en_name":"Afghanistan","ar_name":"أفغانستان","iso3":"AFG","numcode":"4","phonecode":"93"},'.
            '{"id":"2","iso":"AL","name":"ALBANIA","en_name":"Albania","ar_name":"ألبانيا","iso3":"ALB","numcode":"8","phonecode":"355"},'.
            '{"id":"3","iso":"DZ","name":"ALGERIA","en_name":"Algeria","ar_name":"الجزائر","iso3":"DZA","numcode":"12","phonecode":"213"},'.
            '{"id":"4","iso":"AS","name":"AMERICAN SAMOA","en_name":"American Samoa","ar_name":"ساموا-الأمريكي","iso3":"ASM","numcode":"16","phonecode":"1684"},'.
            '{"id":"5","iso":"AD","name":"ANDORRA","en_name":"Andorra","ar_name":"أندورا","iso3":"AND","numcode":"20","phonecode":"376"},'.
            '{"id":"6","iso":"AO","name":"ANGOLA","en_name":"Angola","ar_name":"أنغولا","iso3":"AGO","numcode":"24","phonecode":"244"},'.
            '{"id":"7","iso":"AI","name":"ANGUILLA","en_name":"Anguilla","ar_name":"أنغويلا","iso3":"AIA","numcode":"660","phonecode":"1264"},'.
            '{"id":"8","iso":"AQ","name":"ANTARCTICA","en_name":"Antarctica","ar_name":"أنتاركتيكا","iso3":null,"numcode":null,"phonecode":"0"},'.
            '{"id":"9","iso":"AG","name":"ANTIGUA AND BARBUDA","en_name":"Antigua and Barbuda","ar_name":"أنتيغوا وبربودا","iso3":"ATG","numcode":"28","phonecode":"1268"},'.
            '{"id":"10","iso":"AR","name":"ARGENTINA","en_name":"Argentina","ar_name":"الأرجنتين","iso3":"ARG","numcode":"32","phonecode":"54"},'.
            '{"id":"11","iso":"AM","name":"ARMENIA","en_name":"Armenia","ar_name":"أرمينيا","iso3":"ARM","numcode":"51","phonecode":"374"},'.
            '{"id":"12","iso":"AW","name":"ARUBA","en_name":"Aruba","ar_name":"أروبه","iso3":"ABW","numcode":"533","phonecode":"297"},'.
            '{"id":"13","iso":"AU","name":"AUSTRALIA","en_name":"Australia","ar_name":"أستراليا","iso3":"AUS","numcode":"36","phonecode":"61"},'.
            '{"id":"14","iso":"AT","name":"AUSTRIA","en_name":"Austria","ar_name":"النمسا","iso3":"AUT","numcode":"40","phonecode":"43"},'.
            '{"id":"15","iso":"AZ","name":"AZERBAIJAN","en_name":"Azerbaijan","ar_name":"أذربيجان","iso3":"AZE","numcode":"31","phonecode":"994"},'.
            '{"id":"16","iso":"BS","name":"BAHAMAS","en_name":"Bahamas","ar_name":"الباهاماس","iso3":"BHS","numcode":"44","phonecode":"1242"},'.
            '{"id":"17","iso":"BH","name":"BAHRAIN","en_name":"Bahrain","ar_name":"البحرين","iso3":"BHR","numcode":"48","phonecode":"973"},'.
            '{"id":"18","iso":"BD","name":"BANGLADESH","en_name":"Bangladesh","ar_name":"بنغلاديش","iso3":"BGD","numcode":"50","phonecode":"880"},'.
            '{"id":"19","iso":"BB","name":"BARBADOS","en_name":"Barbados","ar_name":"بربادوس","iso3":"BRB","numcode":"52","phonecode":"1246"},'.
            '{"id":"20","iso":"BY","name":"BELARUS","en_name":"Belarus","ar_name":"روسيا البيضاء","iso3":"BLR","numcode":"112","phonecode":"375"},'.
            '{"id":"21","iso":"BE","name":"BELGIUM","en_name":"Belgium","ar_name":"بلجيكا","iso3":"BEL","numcode":"56","phonecode":"32"},'.
            '{"id":"22","iso":"BZ","name":"BELIZE","en_name":"Belize","ar_name":"بيليز","iso3":"BLZ","numcode":"84","phonecode":"501"},'.
            '{"id":"23","iso":"BJ","name":"BENIN","en_name":"Benin","ar_name":"بنين","iso3":"BEN","numcode":"204","phonecode":"229"},'.
            '{"id":"24","iso":"BM","name":"BERMUDA","en_name":"Bermuda","ar_name":"جزر برمودا","iso3":"BMU","numcode":"60","phonecode":"1441"},'.
            '{"id":"25","iso":"BT","name":"BHUTAN","en_name":"Bhutan","ar_name":"بوتان","iso3":"BTN","numcode":"64","phonecode":"975"},'.
            '{"id":"26","iso":"BO","name":"BOLIVIA","en_name":"Bolivia","ar_name":"بوليفيا","iso3":"BOL","numcode":"68","phonecode":"591"},'.
            '{"id":"27","iso":"BA","name":"BOSNIA AND HERZEGOVINA","en_name":"Bosnia and Herzegovina","ar_name":"البوسنة و الهرسك","iso3":"BIH","numcode":"70","phonecode":"387"},'.
            '{"id":"28","iso":"BW","name":"BOTSWANA","en_name":"Botswana","ar_name":"بوتسوانا","iso3":"BWA","numcode":"72","phonecode":"267"},'.
            '{"id":"29","iso":"BV","name":"BOUVET ISLAND","en_name":"Bouvet Island","ar_name":"جزيرة بوفيه","iso3":null,"numcode":null,"phonecode":"0"},'.
            '{"id":"30","iso":"BR","name":"BRAZIL","en_name":"Brazil","ar_name":"البرازيل","iso3":"BRA","numcode":"76","phonecode":"55"},'.
            '{"id":"31","iso":"IO","name":"BRITISH INDIAN OCEAN TERRITORY","en_name":"British Indian Ocean Territory","ar_name":"إقليم المحيط الهندي البريطاني","iso3":null,"numcode":null,"phonecode":"246"},'.
            '{"id":"32","iso":"BN","name":"BRUNEI DARUSSALAM","en_name":"Brunei Darussalam","ar_name":"بروني","iso3":"BRN","numcode":"96","phonecode":"673"},'.
            '{"id":"33","iso":"BG","name":"BULGARIA","en_name":"Bulgaria","ar_name":"بلغاريا","iso3":"BGR","numcode":"100","phonecode":"359"},'.
            '{"id":"34","iso":"BF","name":"BURKINA FASO","en_name":"Burkina Faso","ar_name":"بوركينا فاسو","iso3":"BFA","numcode":"854","phonecode":"226"},'.
            '{"id":"35","iso":"BI","name":"BURUNDI","en_name":"Burundi","ar_name":"بوروندي","iso3":"BDI","numcode":"108","phonecode":"257"},'.
            '{"id":"36","iso":"KH","name":"CAMBODIA","en_name":"Cambodia","ar_name":"كمبوديا","iso3":"KHM","numcode":"116","phonecode":"855"},'.
            '{"id":"37","iso":"CM","name":"CAMEROON","en_name":"Cameroon","ar_name":"كاميرون","iso3":"CMR","numcode":"120","phonecode":"237"},'.
            '{"id":"38","iso":"CA","name":"CANADA","en_name":"Canada","ar_name":"كندا","iso3":"CAN","numcode":"124","phonecode":"1"},'.
            '{"id":"39","iso":"CV","name":"CAPE VERDE","en_name":"Cape Verde","ar_name":"الرأس الأخضر","iso3":"CPV","numcode":"132","phonecode":"238"},'.
            '{"id":"40","iso":"KY","name":"CAYMAN ISLANDS","en_name":"Cayman Islands","ar_name":"جزر كايمان","iso3":"CYM","numcode":"136","phonecode":"1345"},'.
            '{"id":"41","iso":"CF","name":"CENTRAL AFRICAN REPUBLIC","en_name":"Central African Republic","ar_name":"جمهورية أفريقيا الوسطى","iso3":"CAF","numcode":"140","phonecode":"236"},'.
            '{"id":"42","iso":"TD","name":"CHAD","en_name":"Chad","ar_name":"تشاد","iso3":"TCD","numcode":"148","phonecode":"235"},'.
            '{"id":"43","iso":"CL","name":"CHILE","en_name":"Chile","ar_name":"شيلي","iso3":"CHL","numcode":"152","phonecode":"56"},'.
            '{"id":"44","iso":"CN","name":"CHINA","en_name":"China","ar_name":"الصين","iso3":"CHN","numcode":"156","phonecode":"86"},'.
            '{"id":"45","iso":"CX","name":"CHRISTMAS ISLAND","en_name":"Christmas Island","ar_name":"جزيرة عيد الميلاد","iso3":null,"numcode":null,"phonecode":"61"},'.
            '{"id":"46","iso":"CC","name":"COCOS (KEELING) ISLANDS","en_name":"Cocos (Keeling) Islands","ar_name":"جزر كوكوس","iso3":null,"numcode":null,"phonecode":"672"},'.
            '{"id":"47","iso":"CO","name":"COLOMBIA","en_name":"Colombia","ar_name":"كولومبيا","iso3":"COL","numcode":"170","phonecode":"57"},'.
            '{"id":"48","iso":"KM","name":"COMOROS","en_name":"Comoros","ar_name":"جزر القمر","iso3":"COM","numcode":"174","phonecode":"269"},'.
            '{"id":"49","iso":"CG","name":"CONGO","en_name":"Congo","ar_name":"الكونغو","iso3":"COG","numcode":"178","phonecode":"242"},'.
            '{"id":"50","iso":"CD","name":"CONGO, THE DEMOCRATIC REPUBLIC OF THE","en_name":"Congo, the Democratic Republic of the","ar_name":"جمهورية الكونغو الديمقراطية","iso3":"COD","numcode":"180","phonecode":"242"},'.
            '{"id":"51","iso":"CK","name":"COOK ISLANDS","en_name":"Cook Islands","ar_name":"جزر كوك","iso3":"COK","numcode":"184","phonecode":"682"},'.
            '{"id":"52","iso":"CR","name":"COSTA RICA","en_name":"Costa Rica","ar_name":"كوستاريكا","iso3":"CRI","numcode":"188","phonecode":"506"},'.
            '{"id":"53","iso":"CI","name":"COTE D\'IVOIRE","en_name":"Cote D\'Ivoire","ar_name":"ساحل العاج","iso3":"CIV","numcode":"384","phonecode":"225"},'.
            '{"id":"54","iso":"HR","name":"CROATIA","en_name":"Croatia","ar_name":"كرواتيا","iso3":"HRV","numcode":"191","phonecode":"385"},'.
            '{"id":"55","iso":"CU","name":"CUBA","en_name":"Cuba","ar_name":"كوبا","iso3":"CUB","numcode":"192","phonecode":"53"},'.
            '{"id":"56","iso":"CY","name":"CYPRUS","en_name":"Cyprus","ar_name":"قبرص","iso3":"CYP","numcode":"196","phonecode":"357"},'.
            '{"id":"57","iso":"CZ","name":"CZECH REPUBLIC","en_name":"Czech Republic","ar_name":"الجمهورية التشيكية","iso3":"CZE","numcode":"203","phonecode":"420"},'.
            '{"id":"58","iso":"DK","name":"DENMARK","en_name":"Denmark","ar_name":"الدانمارك","iso3":"DNK","numcode":"208","phonecode":"45"},'.
            '{"id":"59","iso":"DJ","name":"DJIBOUTI","en_name":"Djibouti","ar_name":"جيبوتي","iso3":"DJI","numcode":"262","phonecode":"253"},'.
            '{"id":"60","iso":"DM","name":"DOMINICA","en_name":"Dominica","ar_name":"دومينيكا","iso3":"DMA","numcode":"212","phonecode":"1767"},'.
            '{"id":"61","iso":"DO","name":"DOMINICAN REPUBLIC","en_name":"Dominican Republic","ar_name":"الجمهورية الدومينيكية","iso3":"DOM","numcode":"214","phonecode":"1809"},'.
            '{"id":"62","iso":"EC","name":"ECUADOR","en_name":"Ecuador","ar_name":"إكوادور","iso3":"ECU","numcode":"218","phonecode":"593"},'.
            '{"id":"63","iso":"EG","name":"EGYPT","en_name":"Egypt","ar_name":"مصر","iso3":"EGY","numcode":"818","phonecode":"20"},'.
            '{"id":"64","iso":"SV","name":"EL SALVADOR","en_name":"El Salvador","ar_name":"إلسلفادور","iso3":"SLV","numcode":"222","phonecode":"503"},'.
            '{"id":"65","iso":"GQ","name":"EQUATORIAL GUINEA","en_name":"Equatorial Guinea","ar_name":"غينيا الاستوائي","iso3":"GNQ","numcode":"226","phonecode":"240"},'.
            '{"id":"66","iso":"ER","name":"ERITREA","en_name":"Eritrea","ar_name":"إريتريا","iso3":"ERI","numcode":"232","phonecode":"291"},'.
            '{"id":"67","iso":"EE","name":"ESTONIA","en_name":"Estonia","ar_name":"استونيا","iso3":"EST","numcode":"233","phonecode":"372"},'.
            '{"id":"68","iso":"ET","name":"ETHIOPIA","en_name":"Ethiopia","ar_name":"أثيوبيا","iso3":"ETH","numcode":"231","phonecode":"251"},'.
            '{"id":"69","iso":"FK","name":"FALKLAND ISLANDS (MALVINAS)","en_name":"Falkland Islands (Malvinas)","ar_name":"جزر فوكلاند","iso3":"FLK","numcode":"238","phonecode":"500"},'.
            '{"id":"70","iso":"FO","name":"FAROE ISLANDS","en_name":"Faroe Islands","ar_name":"جزر فارو","iso3":"FRO","numcode":"234","phonecode":"298"},'.
            '{"id":"71","iso":"FJ","name":"FIJI","en_name":"Fiji","ar_name":"فيجي","iso3":"FJI","numcode":"242","phonecode":"679"},'.
            '{"id":"72","iso":"FI","name":"FINLAND","en_name":"Finland","ar_name":"فنلندا","iso3":"FIN","numcode":"246","phonecode":"358"},'.
            '{"id":"73","iso":"FR","name":"FRANCE","en_name":"France","ar_name":"فرنسا","iso3":"FRA","numcode":"250","phonecode":"33"},'.
            '{"id":"74","iso":"GF","name":"FRENCH GUIANA","en_name":"French Guiana","ar_name":"غويانا الفرنسية","iso3":"GUF","numcode":"254","phonecode":"594"},'.
            '{"id":"75","iso":"PF","name":"FRENCH POLYNESIA","en_name":"French Polynesia","ar_name":"بولينيزيا الفرنسية","iso3":"PYF","numcode":"258","phonecode":"689"},'.
            '{"id":"76","iso":"TF","name":"FRENCH SOUTHERN TERRITORIES","en_name":"French Southern Territories","ar_name":"أراض فرنسية جنوبية وأنتارتيكية","iso3":null,"numcode":null,"phonecode":"0"},'.
            '{"id":"77","iso":"GA","name":"GABON","en_name":"Gabon","ar_name":"الغابون","iso3":"GAB","numcode":"266","phonecode":"241"},'.
            '{"id":"78","iso":"GM","name":"GAMBIA","en_name":"Gambia","ar_name":"غامبيا","iso3":"GMB","numcode":"270","phonecode":"220"},'.
            '{"id":"79","iso":"GE","name":"GEORGIA","en_name":"Georgia","ar_name":"جيورجيا","iso3":"GEO","numcode":"268","phonecode":"995"},'.
            '{"id":"80","iso":"DE","name":"GERMANY","en_name":"Germany","ar_name":"ألمانيا","iso3":"DEU","numcode":"276","phonecode":"49"},'.
            '{"id":"81","iso":"GH","name":"GHANA","en_name":"Ghana","ar_name":"غانا","iso3":"GHA","numcode":"288","phonecode":"233"},'.
            '{"id":"82","iso":"GI","name":"GIBRALTAR","en_name":"Gibraltar","ar_name":"جبل طارق","iso3":"GIB","numcode":"292","phonecode":"350"},'.
            '{"id":"83","iso":"GR","name":"GREECE","en_name":"Greece","ar_name":"اليونان","iso3":"GRC","numcode":"300","phonecode":"30"},'.
            '{"id":"84","iso":"GL","name":"GREENLAND","en_name":"Greenland","ar_name":"جرينلاند","iso3":"GRL","numcode":"304","phonecode":"299"},'.
            '{"id":"85","iso":"GD","name":"GRENADA","en_name":"Grenada","ar_name":"غرينادا","iso3":"GRD","numcode":"308","phonecode":"1473"},'.
            '{"id":"86","iso":"GP","name":"GUADELOUPE","en_name":"Guadeloupe","ar_name":"جزر جوادلوب","iso3":"GLP","numcode":"312","phonecode":"590"},'.
            '{"id":"87","iso":"GU","name":"GUAM","en_name":"Guam","ar_name":"جوام","iso3":"GUM","numcode":"316","phonecode":"1671"},'.
            '{"id":"88","iso":"GT","name":"GUATEMALA","en_name":"Guatemala","ar_name":"غواتيمال","iso3":"GTM","numcode":"320","phonecode":"502"},'.
            '{"id":"89","iso":"GN","name":"GUINEA","en_name":"Guinea","ar_name":"غينيا","iso3":"GIN","numcode":"324","phonecode":"224"},'.
            '{"id":"90","iso":"GW","name":"GUINEA-BISSAU","en_name":"Guinea-Bissau","ar_name":"غينيا-بيساو","iso3":"GNB","numcode":"624","phonecode":"245"},'.
            '{"id":"91","iso":"GY","name":"GUYANA","en_name":"Guyana","ar_name":"غيانا","iso3":"GUY","numcode":"328","phonecode":"592"},'.
            '{"id":"92","iso":"HT","name":"HAITI","en_name":"Haiti","ar_name":"هايتي","iso3":"HTI","numcode":"332","phonecode":"509"},'.
            '{"id":"93","iso":"HM","name":"HEARD ISLAND AND MCDONALD ISLANDS","en_name":"Heard Island and Mcdonald Islands","ar_name":"جزيرة هيرد وجزر ماكدونالد","iso3":null,"numcode":null,"phonecode":"0"},'.
            '{"id":"94","iso":"VA","name":"HOLY SEE (VATICAN CITY STATE)","en_name":"Holy See (Vatican City State)","ar_name":"فنزويلا","iso3":"VAT","numcode":"336","phonecode":"39"},'.
            '{"id":"95","iso":"HN","name":"HONDURAS","en_name":"Honduras","ar_name":"هندوراس","iso3":"HND","numcode":"340","phonecode":"504"},'.
            '{"id":"96","iso":"HK","name":"HONG KONG","en_name":"Hong Kong","ar_name":"هونغ كونغ","iso3":"HKG","numcode":"344","phonecode":"852"},'.
            '{"id":"97","iso":"HU","name":"HUNGARY","en_name":"Hungary","ar_name":"المجر","iso3":"HUN","numcode":"348","phonecode":"36"},'.
            '{"id":"98","iso":"IS","name":"ICELAND","en_name":"Iceland","ar_name":"آيسلندا","iso3":"ISL","numcode":"352","phonecode":"354"},'.
            '{"id":"99","iso":"IN","name":"INDIA","en_name":"India","ar_name":"الهند","iso3":"IND","numcode":"356","phonecode":"91"},'.
            '{"id":"100","iso":"ID","name":"INDONESIA","en_name":"Indonesia","ar_name":"أندونيسيا","iso3":"IDN","numcode":"360","phonecode":"62"},'.
            '{"id":"101","iso":"IR","name":"IRAN, ISLAMIC REPUBLIC OF","en_name":"Iran, Islamic Republic of","ar_name":"إيران","iso3":"IRN","numcode":"364","phonecode":"98"},'.
            '{"id":"102","iso":"IQ","name":"IRAQ","en_name":"Iraq","ar_name":"العراق","iso3":"IRQ","numcode":"368","phonecode":"964"},'.
            '{"id":"103","iso":"IE","name":"IRELAND","en_name":"Ireland","ar_name":"إيرلندا","iso3":"IRL","numcode":"372","phonecode":"353"},'.
            '{"id":"104","iso":"IL","name":"ISRAEL","en_name":"Israel","ar_name":"إسرائيل","iso3":"ISR","numcode":"376","phonecode":"972"},'.
            '{"id":"105","iso":"IT","name":"ITALY","en_name":"Italy","ar_name":"إيطاليا","iso3":"ITA","numcode":"380","phonecode":"39"},'.
            '{"id":"106","iso":"JM","name":"JAMAICA","en_name":"Jamaica","ar_name":"جمايكا","iso3":"JAM","numcode":"388","phonecode":"1876"},'.
            '{"id":"107","iso":"JP","name":"JAPAN","en_name":"Japan","ar_name":"اليابان","iso3":"JPN","numcode":"392","phonecode":"81"},'.
            '{"id":"108","iso":"JO","name":"JORDAN","en_name":"Jordan","ar_name":"الأردن","iso3":"JOR","numcode":"400","phonecode":"962"},'.
            '{"id":"109","iso":"KZ","name":"KAZAKHSTAN","en_name":"Kazakhstan","ar_name":"كازاخستان","iso3":"KAZ","numcode":"398","phonecode":"7"},'.
            '{"id":"110","iso":"KE","name":"KENYA","en_name":"Kenya","ar_name":"كينيا","iso3":"KEN","numcode":"404","phonecode":"254"},'.
            '{"id":"111","iso":"KI","name":"KIRIBATI","en_name":"Kiribati","ar_name":"كيريباتي","iso3":"KIR","numcode":"296","phonecode":"686"},'.
            '{"id":"112","iso":"KP","name":"KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF","en_name":"Korea, Democratic People\'s Republic of","ar_name":"كوريا الشمالية","iso3":"PRK","numcode":"408","phonecode":"850"},'.
            '{"id":"113","iso":"KR","name":"KOREA, REPUBLIC OF","en_name":"Korea, Republic of","ar_name":"كوريا الجنوبية","iso3":"KOR","numcode":"410","phonecode":"82"},'.
            '{"id":"114","iso":"KW","name":"KUWAIT","en_name":"Kuwait","ar_name":"الكويت","iso3":"KWT","numcode":"414","phonecode":"965"},'.
            '{"id":"115","iso":"KG","name":"KYRGYZSTAN","en_name":"Kyrgyzstan","ar_name":"قيرغيزستان","iso3":"KGZ","numcode":"417","phonecode":"996"},'.
            '{"id":"116","iso":"LA","name":"LAO PEOPLE\'S DEMOCRATIC REPUBLIC","en_name":"Lao People\'s Democratic Republic","ar_name":"لاوس","iso3":"LAO","numcode":"418","phonecode":"856"},'.
            '{"id":"117","iso":"LV","name":"LATVIA","en_name":"Latvia","ar_name":"لاتفيا","iso3":"LVA","numcode":"428","phonecode":"371"},'.
            '{"id":"118","iso":"LB","name":"LEBANON","en_name":"Lebanon","ar_name":"لبنان","iso3":"LBN","numcode":"422","phonecode":"961"},'.
            '{"id":"119","iso":"LS","name":"LESOTHO","en_name":"Lesotho","ar_name":"ليسوتو","iso3":"LSO","numcode":"426","phonecode":"266"},'.
            '{"id":"120","iso":"LR","name":"LIBERIA","en_name":"Liberia","ar_name":"ليبيريا","iso3":"LBR","numcode":"430","phonecode":"231"},'.
            '{"id":"121","iso":"LY","name":"LIBYAN ARAB JAMAHIRIYA","en_name":"Libyan Arab Jamahiriya","ar_name":"ليبيا","iso3":"LBY","numcode":"434","phonecode":"218"},'.
            '{"id":"122","iso":"LI","name":"LIECHTENSTEIN","en_name":"Liechtenstein","ar_name":"ليختنشتين","iso3":"LIE","numcode":"438","phonecode":"423"},'.
            '{"id":"123","iso":"LT","name":"LITHUANIA","en_name":"Lithuania","ar_name":"لتوانيا","iso3":"LTU","numcode":"440","phonecode":"370"},'.
            '{"id":"124","iso":"LU","name":"LUXEMBOURG","en_name":"Luxembourg","ar_name":"لوكسمبورغ","iso3":"LUX","numcode":"442","phonecode":"352"},'.
            '{"id":"125","iso":"MO","name":"MACAO","en_name":"Macao","ar_name":"ماكاو","iso3":"MAC","numcode":"446","phonecode":"853"},'.
            '{"id":"126","iso":"MK","name":"MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF","en_name":"Macedonia, the Former Yugoslav Republic of","ar_name":"مقدونيا","iso3":"MKD","numcode":"807","phonecode":"389"},'.
            '{"id":"127","iso":"MG","name":"MADAGASCAR","en_name":"Madagascar","ar_name":"مدغشقر","iso3":"MDG","numcode":"450","phonecode":"261"},'.
            '{"id":"128","iso":"MW","name":"MALAWI","en_name":"Malawi","ar_name":"مالاوي","iso3":"MWI","numcode":"454","phonecode":"265"},'.
            '{"id":"129","iso":"MY","name":"MALAYSIA","en_name":"Malaysia","ar_name":"ماليزيا","iso3":"MYS","numcode":"458","phonecode":"60"},'.
            '{"id":"130","iso":"MV","name":"MALDIVES","en_name":"Maldives","ar_name":"المالديف","iso3":"MDV","numcode":"462","phonecode":"960"},'.
            '{"id":"131","iso":"ML","name":"MALI","en_name":"Mali","ar_name":"مالي","iso3":"MLI","numcode":"466","phonecode":"223"},'.
            '{"id":"132","iso":"MT","name":"MALTA","en_name":"Malta","ar_name":"مالطا","iso3":"MLT","numcode":"470","phonecode":"356"},'.
            '{"id":"133","iso":"MH","name":"MARSHALL ISLANDS","en_name":"Marshall Islands","ar_name":"جزر مارشال","iso3":"MHL","numcode":"584","phonecode":"692"},'.
            '{"id":"134","iso":"MQ","name":"MARTINIQUE","en_name":"Martinique","ar_name":"مارتينيك","iso3":"MTQ","numcode":"474","phonecode":"596"},'.
            '{"id":"135","iso":"MR","name":"MAURITANIA","en_name":"Mauritania","ar_name":"موريتانيا","iso3":"MRT","numcode":"478","phonecode":"222"},'.
            '{"id":"136","iso":"MU","name":"MAURITIUS","en_name":"Mauritius","ar_name":"موريشيوس","iso3":"MUS","numcode":"480","phonecode":"230"},'.
            '{"id":"137","iso":"YT","name":"MAYOTTE","en_name":"Mayotte","ar_name":"مايوت","iso3":null,"numcode":null,"phonecode":"269"},'.
            '{"id":"138","iso":"MX","name":"MEXICO","en_name":"Mexico","ar_name":"المكسيك","iso3":"MEX","numcode":"484","phonecode":"52"},'.
            '{"id":"139","iso":"FM","name":"MICRONESIA, FEDERATED STATES OF","en_name":"Micronesia, Federated States of","ar_name":"مايكرونيزيا","iso3":"FSM","numcode":"583","phonecode":"691"},'.
            '{"id":"140","iso":"MD","name":"MOLDOVA, REPUBLIC OF","en_name":"Moldova, Republic of","ar_name":"مولدافيا","iso3":"MDA","numcode":"498","phonecode":"373"},'.
            '{"id":"141","iso":"MC","name":"MONACO","en_name":"Monaco","ar_name":"موناكو","iso3":"MCO","numcode":"492","phonecode":"377"},'.
            '{"id":"142","iso":"MN","name":"MONGOLIA","en_name":"Mongolia","ar_name":"منغوليا","iso3":"MNG","numcode":"496","phonecode":"976"},'.
            '{"id":"143","iso":"MS","name":"MONTSERRAT","en_name":"Montserrat","ar_name":"مونتسيرات","iso3":"MSR","numcode":"500","phonecode":"1664"},'.
            '{"id":"144","iso":"MA","name":"MOROCCO","en_name":"Morocco","ar_name":"المغرب","iso3":"MAR","numcode":"504","phonecode":"212"},'.
            '{"id":"145","iso":"MZ","name":"MOZAMBIQUE","en_name":"Mozambique","ar_name":"موزمبيق","iso3":"MOZ","numcode":"508","phonecode":"258"},'.
            '{"id":"146","iso":"MM","name":"MYANMAR","en_name":"Myanmar","ar_name":"ميانمار","iso3":"MMR","numcode":"104","phonecode":"95"},'.
            '{"id":"147","iso":"NA","name":"NAMIBIA","en_name":"Namibia","ar_name":"ناميبيا","iso3":"NAM","numcode":"516","phonecode":"264"},'.
            '{"id":"148","iso":"NR","name":"NAURU","en_name":"Nauru","ar_name":"نورو","iso3":"NRU","numcode":"520","phonecode":"674"},'.
            '{"id":"149","iso":"NP","name":"NEPAL","en_name":"Nepal","ar_name":"نيبال","iso3":"NPL","numcode":"524","phonecode":"977"},'.
            '{"id":"150","iso":"NL","name":"NETHERLANDS","en_name":"Netherlands","ar_name":"هولندا","iso3":"NLD","numcode":"528","phonecode":"31"},'.
            '{"id":"151","iso":"AN","name":"NETHERLANDS ANTILLES","en_name":"Netherlands Antilles","ar_name":"جزر الأنتيل الهولندي","iso3":"ANT","numcode":"530","phonecode":"599"},'.
            '{"id":"152","iso":"NC","name":"NEW CALEDONIA","en_name":"New Caledonia","ar_name":"كاليدونيا الجديدة","iso3":"NCL","numcode":"540","phonecode":"687"},'.
            '{"id":"153","iso":"NZ","name":"NEW ZEALAND","en_name":"New Zealand","ar_name":"نيوزيلندا","iso3":"NZL","numcode":"554","phonecode":"64"},'.
            '{"id":"154","iso":"NI","name":"NICARAGUA","en_name":"Nicaragua","ar_name":"نيكاراجوا","iso3":"NIC","numcode":"558","phonecode":"505"},'.
            '{"id":"155","iso":"NE","name":"NIGER","en_name":"Niger","ar_name":"النيجر","iso3":"NER","numcode":"562","phonecode":"227"},'.
            '{"id":"156","iso":"NG","name":"NIGERIA","en_name":"Nigeria","ar_name":"نيجيريا","iso3":"NGA","numcode":"566","phonecode":"234"},'.
            '{"id":"157","iso":"NU","name":"NIUE","en_name":"Niue","ar_name":"ني","iso3":"NIU","numcode":"570","phonecode":"683"},'.
            '{"id":"158","iso":"NF","name":"NORFOLK ISLAND","en_name":"Norfolk Island","ar_name":"جزيرة نورفولك","iso3":"NFK","numcode":"574","phonecode":"672"},'.
            '{"id":"159","iso":"MP","name":"NORTHERN MARIANA ISLANDS","en_name":"Northern Mariana Islands","ar_name":"جزر ماريانا الشمالية","iso3":"MNP","numcode":"580","phonecode":"1670"},'.
            '{"id":"160","iso":"NO","name":"NORWAY","en_name":"Norway","ar_name":"النرويج","iso3":"NOR","numcode":"578","phonecode":"47"},'.
            '{"id":"161","iso":"OM","name":"OMAN","en_name":"Oman","ar_name":"عمان","iso3":"OMN","numcode":"512","phonecode":"968"},'.
            '{"id":"162","iso":"PK","name":"PAKISTAN","en_name":"Pakistan","ar_name":"باكستان","iso3":"PAK","numcode":"586","phonecode":"92"},'.
            '{"id":"163","iso":"PW","name":"PALAU","en_name":"Palau","ar_name":"بالاو","iso3":"PLW","numcode":"585","phonecode":"680"},'.
            '{"id":"164","iso":"PS","name":"PALESTINIAN TERRITORY, OCCUPIED","en_name":"Palestinian Territory, Occupied","ar_name":"فلسطين","iso3":null,"numcode":null,"phonecode":"970"},'.
            '{"id":"165","iso":"PA","name":"PANAMA","en_name":"Panama","ar_name":"بنما","iso3":"PAN","numcode":"591","phonecode":"507"},'.
            '{"id":"166","iso":"PG","name":"PAPUA NEW GUINEA","en_name":"Papua New Guinea","ar_name":"بابوا غينيا الجديدة","iso3":"PNG","numcode":"598","phonecode":"675"},'.
            '{"id":"167","iso":"PY","name":"PARAGUAY","en_name":"Paraguay","ar_name":"باراغواي","iso3":"PRY","numcode":"600","phonecode":"595"},'.
            '{"id":"168","iso":"PE","name":"PERU","en_name":"Peru","ar_name":"بيرو","iso3":"PER","numcode":"604","phonecode":"51"},'.
            '{"id":"169","iso":"PH","name":"PHILIPPINES","en_name":"Philippines","ar_name":"الفليبين","iso3":"PHL","numcode":"608","phonecode":"63"},'.
            '{"id":"170","iso":"PN","name":"PITCAIRN","en_name":"Pitcairn","ar_name":"بيتكيرن","iso3":"PCN","numcode":"612","phonecode":"0"},'.
            '{"id":"171","iso":"PL","name":"POLAND","en_name":"Poland","ar_name":"بولونيا","iso3":"POL","numcode":"616","phonecode":"48"},'.
            '{"id":"172","iso":"PT","name":"PORTUGAL","en_name":"Portugal","ar_name":"البرتغال","iso3":"PRT","numcode":"620","phonecode":"351"},'.
            '{"id":"173","iso":"PR","name":"PUERTO RICO","en_name":"Puerto Rico","ar_name":"بورتو ريكو","iso3":"PRI","numcode":"630","phonecode":"1787"},'.
            '{"id":"174","iso":"QA","name":"QATAR","en_name":"Qatar","ar_name":"قطر","iso3":"QAT","numcode":"634","phonecode":"974"},'.
            '{"id":"175","iso":"RE","name":"REUNION","en_name":"Reunion","ar_name":"ريونيون","iso3":"REU","numcode":"638","phonecode":"262"},'.
            '{"id":"176","iso":"RO","name":"ROMANIA","en_name":"Romania","ar_name":"رومانيا","iso3":"ROM","numcode":"642","phonecode":"40"},'.
            '{"id":"177","iso":"RU","name":"RUSSIAN FEDERATION","en_name":"Russian Federation","ar_name":"روسيا","iso3":"RUS","numcode":"643","phonecode":"70"},'.
            '{"id":"178","iso":"RW","name":"RWANDA","en_name":"Rwanda","ar_name":"رواندا","iso3":"RWA","numcode":"646","phonecode":"250"},'.
            '{"id":"179","iso":"SH","name":"SAINT HELENA","en_name":"Saint Helena","ar_name":"سانت هيلانة","iso3":"SHN","numcode":"654","phonecode":"290"},'.
            '{"id":"180","iso":"KN","name":"SAINT KITTS AND NEVIS","en_name":"Saint Kitts and Nevis","ar_name":"سانت كيتس ونيفس,","iso3":"KNA","numcode":"659","phonecode":"1869"},'.
            '{"id":"181","iso":"LC","name":"SAINT LUCIA","en_name":"Saint Lucia","ar_name":"سان بيير وميكلون","iso3":"LCA","numcode":"662","phonecode":"1758"},'.
            '{"id":"182","iso":"PM","name":"SAINT PIERRE AND MIQUELON","en_name":"Saint Pierre and Miquelon","ar_name":"سانت بيير وميكلون","iso3":"SPM","numcode":"666","phonecode":"508"},'.
            '{"id":"183","iso":"VC","name":"SAINT VINCENT AND THE GRENADINES","en_name":"Saint Vincent and the Grenadines","ar_name":"سانت فنسنت وجزر غرينادين","iso3":"VCT","numcode":"670","phonecode":"1784"},'.
            '{"id":"184","iso":"WS","name":"SAMOA","en_name":"Samoa","ar_name":"ساموا","iso3":"WSM","numcode":"882","phonecode":"684"},'.
            '{"id":"185","iso":"SM","name":"SAN MARINO","en_name":"San Marino","ar_name":"سان مارينو","iso3":"SMR","numcode":"674","phonecode":"378"},'.
            '{"id":"186","iso":"ST","name":"SAO TOME AND PRINCIPE","en_name":"Sao Tome and Principe","ar_name":"ساو تومي وبرينسيبي","iso3":"STP","numcode":"678","phonecode":"239"},'.
            '{"id":"187","iso":"SA","name":"SAUDI ARABIA","en_name":"Saudi Arabia","ar_name":"المملكة العربية السعودية","iso3":"SAU","numcode":"682","phonecode":"966"},'.
            '{"id":"188","iso":"SN","name":"SENEGAL","en_name":"Senegal","ar_name":"السنغال","iso3":"SEN","numcode":"686","phonecode":"221"},'.
            '{"id":"189","iso":"CS","name":"SERBIA AND MONTENEGRO","en_name":"Serbia and Montenegro","ar_name":"صربيا والجبل الأسود","iso3":null,"numcode":null,"phonecode":"381"},'.
            '{"id":"190","iso":"SC","name":"SEYCHELLES","en_name":"Seychelles","ar_name":"سيشيل","iso3":"SYC","numcode":"690","phonecode":"248"},'.
            '{"id":"191","iso":"SL","name":"SIERRA LEONE","en_name":"Sierra Leone","ar_name":"سيراليون","iso3":"SLE","numcode":"694","phonecode":"232"},'.
            '{"id":"192","iso":"SG","name":"SINGAPORE","en_name":"Singapore","ar_name":"سنغافورة","iso3":"SGP","numcode":"702","phonecode":"65"},'.
            '{"id":"193","iso":"SK","name":"SLOVAKIA","en_name":"Slovakia","ar_name":"سلوفاكيا","iso3":"SVK","numcode":"703","phonecode":"421"},'.
            '{"id":"194","iso":"SI","name":"SLOVENIA","en_name":"Slovenia","ar_name":"سلوفينيا","iso3":"SVN","numcode":"705","phonecode":"386"},'.
            '{"id":"195","iso":"SB","name":"SOLOMON ISLANDS","en_name":"Solomon Islands","ar_name":"جزر سليمان","iso3":"SLB","numcode":"90","phonecode":"677"},'.
            '{"id":"196","iso":"SO","name":"SOMALIA","en_name":"Somalia","ar_name":"الصومال","iso3":"SOM","numcode":"706","phonecode":"252"},'.
            '{"id":"197","iso":"ZA","name":"SOUTH AFRICA","en_name":"South Africa","ar_name":"جنوب أفريقيا","iso3":"ZAF","numcode":"710","phonecode":"27"},'.
            '{"id":"198","iso":"GS","name":"SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS","en_name":"South Georgia and the South Sandwich Islands","ar_name":"المنطقة القطبية الجنوبية","iso3":null,"numcode":null,"phonecode":"0"},'.
            '{"id":"199","iso":"ES","name":"SPAIN","en_name":"Spain","ar_name":"إسبانيا","iso3":"ESP","numcode":"724","phonecode":"34"},'.
            '{"id":"200","iso":"LK","name":"SRI LANKA","en_name":"Sri Lanka","ar_name":"سريلانكا","iso3":"LKA","numcode":"144","phonecode":"94"},'.
            '{"id":"201","iso":"SD","name":"SUDAN","en_name":"Sudan","ar_name":"السودان","iso3":"SDN","numcode":"736","phonecode":"249"},'.
            '{"id":"202","iso":"SR","name":"SURINAME","en_name":"Suriname","ar_name":"سورينام","iso3":"SUR","numcode":"740","phonecode":"597"},'.
            '{"id":"203","iso":"SJ","name":"SVALBARD AND JAN MAYEN","en_name":"Svalbard and Jan Mayen","ar_name":"سفالبارد ويان ماين","iso3":"SJM","numcode":"744","phonecode":"47"},'.
            '{"id":"204","iso":"SZ","name":"SWAZILAND","en_name":"Swaziland","ar_name":"سوازيلند","iso3":"SWZ","numcode":"748","phonecode":"268"},'.
            '{"id":"205","iso":"SE","name":"SWEDEN","en_name":"Sweden","ar_name":"السويد","iso3":"SWE","numcode":"752","phonecode":"46"},'.
            '{"id":"206","iso":"CH","name":"SWITZERLAND","en_name":"Switzerland","ar_name":"سويسرا","iso3":"CHE","numcode":"756","phonecode":"41"},'.
            '{"id":"207","iso":"SY","name":"SYRIAN ARAB REPUBLIC","en_name":"Syrian Arab Republic","ar_name":"سوريا","iso3":"SYR","numcode":"760","phonecode":"963"},'.
            '{"id":"208","iso":"TW","name":"TAIWAN, PROVINCE OF CHINA","en_name":"Taiwan, Province of China","ar_name":"تايوان","iso3":"TWN","numcode":"158","phonecode":"886"},'.
            '{"id":"209","iso":"TJ","name":"TAJIKISTAN","en_name":"Tajikistan","ar_name":"طاجيكستان","iso3":"TJK","numcode":"762","phonecode":"992"},'.
            '{"id":"210","iso":"TZ","name":"TANZANIA, UNITED REPUBLIC OF","en_name":"Tanzania, United Republic of","ar_name":"تنزانيا","iso3":"TZA","numcode":"834","phonecode":"255"},'.
            '{"id":"211","iso":"TH","name":"THAILAND","en_name":"Thailand","ar_name":"تايلندا","iso3":"THA","numcode":"764","phonecode":"66"},'.
            '{"id":"212","iso":"TL","name":"TIMOR-LESTE","en_name":"Timor-Leste","ar_name":"تيمور الشرقية","iso3":null,"numcode":null,"phonecode":"670"},'.
            '{"id":"213","iso":"TG","name":"TOGO","en_name":"Togo","ar_name":"توغو","iso3":"TGO","numcode":"768","phonecode":"228"},'.
            '{"id":"214","iso":"TK","name":"TOKELAU","en_name":"Tokelau","ar_name":"توكيلاو","iso3":"TKL","numcode":"772","phonecode":"690"},'.
            '{"id":"215","iso":"TO","name":"TONGA","en_name":"Tonga","ar_name":"تونغا","iso3":"TON","numcode":"776","phonecode":"676"},'.
            '{"id":"216","iso":"TT","name":"TRINIDAD AND TOBAGO","en_name":"Trinidad and Tobago","ar_name":"ترينيداد وتوباغو","iso3":"TTO","numcode":"780","phonecode":"1868"},'.
            '{"id":"217","iso":"TN","name":"TUNISIA","en_name":"Tunisia","ar_name":"تونس","iso3":"TUN","numcode":"788","phonecode":"216"},'.
            '{"id":"218","iso":"TR","name":"TURKEY","en_name":"Turkey","ar_name":"تركيا","iso3":"TUR","numcode":"792","phonecode":"90"},'.
            '{"id":"219","iso":"TM","name":"TURKMENISTAN","en_name":"Turkmenistan","ar_name":"تركمانستان","iso3":"TKM","numcode":"795","phonecode":"7370"},'.
            '{"id":"220","iso":"TC","name":"TURKS AND CAICOS ISLANDS","en_name":"Turks and Caicos Islands","ar_name":"جزر توركس وكايكوس","iso3":"TCA","numcode":"796","phonecode":"1649"},'.
            '{"id":"221","iso":"TV","name":"TUVALU","en_name":"Tuvalu","ar_name":"توفالو","iso3":"TUV","numcode":"798","phonecode":"688"},'.
            '{"id":"222","iso":"UG","name":"UGANDA","en_name":"Uganda","ar_name":"أوغندا","iso3":"UGA","numcode":"800","phonecode":"256"},'.
            '{"id":"223","iso":"UA","name":"UKRAINE","en_name":"Ukraine","ar_name":"أوكرانيا","iso3":"UKR","numcode":"804","phonecode":"380"},'.
            '{"id":"224","iso":"AE","name":"UNITED ARAB EMIRATES","en_name":"United Arab Emirates","ar_name":"الإمارات العربية المتحدة","iso3":"ARE","numcode":"784","phonecode":"971"},'.
            '{"id":"225","iso":"GB","name":"UNITED KINGDOM","en_name":"United Kingdom","ar_name":"المملكة المتحدة","iso3":"GBR","numcode":"826","phonecode":"44"},'.
            '{"id":"226","iso":"US","name":"UNITED STATES","en_name":"United States","ar_name":"الولايات المتحدة","iso3":"USA","numcode":"840","phonecode":"1"},'.
            '{"id":"227","iso":"UM","name":"UNITED STATES MINOR OUTLYING ISLANDS","en_name":"United States Minor Outlying Islands","ar_name":"قائمة الولايات والمناطق الأمريكية","iso3":null,"numcode":null,"phonecode":"1"},'.
            '{"id":"228","iso":"UY","name":"URUGUAY","en_name":"Uruguay","ar_name":"أورغواي","iso3":"URY","numcode":"858","phonecode":"598"},'.
            '{"id":"229","iso":"UZ","name":"UZBEKISTAN","en_name":"Uzbekistan","ar_name":"أوزباكستان","iso3":"UZB","numcode":"860","phonecode":"998"},'.
            '{"id":"230","iso":"VU","name":"VANUATU","en_name":"Vanuatu","ar_name":"فانواتو","iso3":"VUT","numcode":"548","phonecode":"678"},'.
            '{"id":"231","iso":"VE","name":"VENEZUELA","en_name":"Venezuela","ar_name":"فنزويلا","iso3":"VEN","numcode":"862","phonecode":"58"},'.
            '{"id":"232","iso":"VN","name":"VIET NAM","en_name":"Viet Nam","ar_name":"فيتنام","iso3":"VNM","numcode":"704","phonecode":"84"},'.
            '{"id":"233","iso":"VG","name":"VIRGIN ISLANDS, BRITISH","en_name":"Virgin Islands, British","ar_name":"جزر العذراء البريطانية","iso3":"VGB","numcode":"92","phonecode":"1284"},'.
            '{"id":"234","iso":"VI","name":"VIRGIN ISLANDS, U.S.","en_name":"Virgin Islands, U.s.","ar_name":"الجزر العذراء الأمريكي","iso3":"VIR","numcode":"850","phonecode":"1340"},'.
            '{"id":"235","iso":"WF","name":"WALLIS AND FUTUNA","en_name":"Wallis and Futuna","ar_name":"والس وفوتونا","iso3":"WLF","numcode":"876","phonecode":"681"},'.
            '{"id":"236","iso":"EH","name":"WESTERN SAHARA","en_name":"Western Sahara","ar_name":"الصحراء الغربية","iso3":"ESH","numcode":"732","phonecode":"212"},'.
            '{"id":"237","iso":"YE","name":"YEMEN","en_name":"Yemen","ar_name":"اليمن","iso3":"YEM","numcode":"887","phonecode":"967"},'.
            '{"id":"238","iso":"ZM","name":"ZAMBIA","en_name":"Zambia","ar_name":"زامبيا","iso3":"ZMB","numcode":"894","phonecode":"260"},'.
            '{"id":"239","iso":"ZW","name":"ZIMBABWE","en_name":"Zimbabwe","ar_name":"زمبابوي","iso3":"ZWE","numcode":"716","phonecode":"263"}'.
        ']}');

        foreach($countries->data as $country)
            Country::query()->updateOrCreate(['id' => $country->id], [
                'name' => strtolower($country->name), 
                'iso' => $country->iso, 
                'iso3' => $country->iso3, 
                'en_name' => $country->en_name, 
                'ar_name' => $country->ar_name, 
                'numcode' => $country->numcode, 
                'phonecode' => $country->phonecode
            ]);
        
    }
}
