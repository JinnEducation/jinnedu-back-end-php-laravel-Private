<?php

namespace App\Http\Controllers\Constants;

use App\Models\Country;

class CountryController extends ConstantController
{
    function __construct() {
        $auditInfo='Country';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='countries';
    }
    
    public function setCountriesLabels(){
        return;
        $data = [
                ['2','afghanistan','countries','Afghanistan','أفغانستان','Afghanistan','Afghanistan'],
                ['3','argentina','countries','Argentina','الأرجنتين','Argentinien','Argentine'],
                ['4','albania','countries','Albania','ألبانيا','Albanien','Albanie'],
                ['5','algeria','countries','Algeria','الجزائر','Algerien','Algérie'],
                ['6','andorra','countries','Andorra','أندورا','Andorra','Andorre'],
                ['7','angola','countries','Angola','أنغولا','Angola','Angola'],
                ['8','australia','countries','Australia','أستراليا','Australien','Australie'],
                ['9','austria','countries','Austria','النمسا','Österreich','Autriche'],
                ['10','azerbaijan','countries','Azerbaijan','أذربيجان','Aserbaidschan','Azerbaïdjan'],
                ['11','bahrain','countries','Bahrain','البحرين','Bahrain','bahreïn'],
                ['12','bangladesh','countries','Bangladesh','بنغلاديش','Bangladesch','Bangladesh'],
                ['13','belgium','countries','Belgium','بلجيكا','Belgien','Belgique'],
                ['14','bosnia-and-herzegovina','countries','Bosnia and Herzegovina','البوسنة و الهرسك','und Bosnien und Herzegowinas','Bosnie Herzégovine'],
                ['15','brazil','countries','Brazil','البرازيل','Brasilien','Brésil'],
                ['16','bulgaria','countries','Bulgaria','بلغاريا','Bulgarien','Bulgarie'],
                ['17','cameroon','countries','Cameroon','كاميرون','Cameron','Cameroun'],
                ['18','canada','countries','Canada','كندا','Kanada','Canada'],
                ['19','chad','countries','Chad','تشاد',' Tschad','Tchad'],
                ['20','chile','countries','Chile','شيلي','Chile','chilien'],
                ['21','china','countries','China','الصين','China','Chine'],
                ['22','colombia','countries','Colombia','كولومبيا','Kolumbien','Colombie'],
                ['23','congo','countries','Congo','الكونغو','Kongo','Kongo'],
                ['24','costa-rica','countries','Costa Rica','كوستاريكا','Costa Rica','Costa Rica'],
                ['25','croatia','countries','Croatia','كرواتيا','Kroatien','Croatie'],
                ['26','cuba','countries','Cuba','كوبا','Kuba','Cuba'],
                ['27','cyprus','countries','Cyprus','قبرص','Zypern','Chypre'],
                ['28','denmark','countries','Denmark','الدانمارك','Dänemark','Danemark'],
                ['29','djibouti','countries','Djibouti','جيبوتي','Dschibuti','Djibouti'],
                ['30','egypt','countries','Egypt','مصر','Ägypten','l\'Égypte'],
                ['31','ethiopia','countries','Ethiopia','أثيوبيا','Äthiopien','Éthiopie'],
                ['32','finland','countries','Finland','فنلندا','Finnland','Finlande'],
                ['33','france','countries','France','فرنسا','Frankreich','France'],
                ['34','germany','countries','Germany','ألمانيا','Deutschland','Allemagne'],
                ['35','greece','countries','Greece','اليونان','Griechenland','Grèce'],
                ['36','guinea','countries','Guinea','غينيا','Guinea','guinée'],
                ['37','iceland','countries','Iceland','آيسلندا','Island','Islande'],
                ['38','india','countries','India','الهند','Indien','Inde'],
                ['39','indonesia','countries','Indonesia','أندونيسيا','Indonesien','Indonésie'],
                ['40','iraq','countries','Iraq','العراق','Irak','Irak'],
                ['41','ireland','countries','Ireland','إيرلندا','Irland','Irlande'],
                ['42','israel','countries','Israel','إسرائيل','Israels','Israël'],
                ['43','italy','countries','Italy','إيطاليا','Italien','Italie'],
                ['44','japan','countries','Japan','اليابان','Japan','Japon'],
                ['45','jordan','countries','Jordan','الأردن','Jordanien','Jordanie'],
                ['46','kenya','countries','Kenya','كينيا','Kenia','Kenya'],
                ['47','kuwait','countries','Kuwait','الكويت','Kuwait','Koweït'],
                ['48','lebanon','countries','Lebanon','لبنان','Libanon','Liban'],
                ['51','madagascar','countries','Madagascar','مدغشقر','Madagaskar','Madagascar'],
                ['52','malaysia','countries','Malaysia','ماليزيا','Malaysia','Malaisie'],
                ['54','malta','countries','Malta','مالطا','Malta','Malte'],
                ['55','mauritania','countries','Mauritania','موريتانيا','Mauretanien','Mauritanie'],
                ['56','mexico','countries','Mexico','المكسيك','Mexiko','Mexique'],
                ['57','morocco','countries','Morocco','المغرب','Marokko','Maroc'],
                ['59','netherlands','countries','Netherlands','هولندا','Niederlande','Pays-Bas'],
                ['60','niger','countries','Niger','النيجر','Niger','Niger'],
                ['61','nigeria','countries','Nigeria','نيجيريا','Nigeria','Nigéria'],
                ['62','norway','countries','Norway','النرويج','Norwegen','Norvège'],
                ['63','oman','countries','Oman','عمان','Amman','Oman'],
                ['64','pakistan','countries','Pakistan','باكستان','Pakistan','Pakistan'],
                ['65','philippines','countries','Philippines','الفليبين','Philippinen','aux Philippines']
            ];
            
            foreach($data as $item){
                $row = Country::find($item[0]);
                if($row){
                    $row->name = $item[1];
                    $row->save();
                    $req = json_decode('{"name":"'.$item[1].'", "file":"'.$item[2].'", "title":"'.$item[3].'"}');
                    //$trans = json_decode('[{"langid":"1", "title":"'.$item[3].'"},{"langid":"2", "title":"'.$item[4].'"}]',true);
                    $trans = json_decode('[{"langid":"1", "title":"'.$item[3].'"},{"langid":"2", "title":"'.$item[4].'"},{"langid":"20", "title":"'.$item[5].'"},{"langid":"15", "title":"'.$item[6].'"}]',true);
                    //var_dump($trans);exit;
                    $req->trans = $trans;
                    //var_dump($req);exit;
                    $this->addLabelAndTranslations($req);
                }
                
            }
    }
    
}