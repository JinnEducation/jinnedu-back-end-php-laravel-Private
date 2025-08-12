<?php

namespace App\Http\Controllers\Localizations;

use Illuminate\Http\Request;
use App\Http\Requests\Label\LabelRequest;

use App\Models\User;
use App\Models\Language;
use App\Models\Translation;
use App\Models\Label;

use Bouncer;
use Mail;

/*
SELECT 
languages.id as id, 
languages.name as name, 
'languages' as file, 
(select title from translations where langid=1 and labelid=labels.id) english , 
(select title from translations where langid=2 and labelid=labels.id) arabic 
FROM 
`languages` left join labels on (labels.name=languages.name and labels.file='languages') 
WHERE 1
*/

class LabelController extends LocalizationController
{
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Label::query('labels.*');
         
        if(!empty($request->q)){
            $items->whereRaw(filterTextDB('labels.name').' like ?',['%'.filterText($request->q).'%']);
            $items->distinct();
        }
         
        //  $items = $items->paginate($limit);
        $items = paginate($items, $limit);
        
        foreach($items as $item){
            $item->trans = $item->translations()->get();
        }

         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
    public function getOptions(Request $request)
    {
         $query=$request->q;
         //dd($query);
         $items = Label::whereRaw('name like ?',['%'.$query.'%'])->limit(10)->get();
         
         return $items;
    }
    
    public function setTranslations($item,$request){
        //var_dump($request->trans[0]);//exit;
        if(isset($request->trans)){
            foreach($request->trans as $trans){
                //var_dump($trans);exit;
                $translation = Translation::where('labelid',$item->id)->where('langid',$trans['langid'])->first();
                if(!$translation) $translation = new Translation;
                
                $translation->labelid=$item->id;
                $translation->langid=$trans['langid'];
                $translation->title=$trans['title'];
                $translation->save();
            }
        }
    }
    
    public function store(LabelRequest $request)
    {
        return $this->storeUpdateRequest($request);
    }
    
    public function update(LabelRequest $request, $id)
    {
        return $this->storeUpdateRequest($request, $id);
    }

    public function storeUpdateRequest($request, $id=0)
    {
        $data = ['name'=>$request->name,'file'=>$request->file,'title'=>$request->title];
        
        $data['name'] = str_replace(' ','-',strtolower($data['name']));
        $data['file'] = str_replace(' ','-',strtolower($data['file']));
        
        $itemDuplicated = Label::where('name',$data['name'])->where('file',$data['file'])->where('id','<>',$id)->first();
        if($itemDuplicated) return response([
                'success' => false,
                'message' => 'item-duplicated',
                'msg-code' => '111'
        ] , 200);
        
        $item=null;
        
        if($id>0){
            $item = Label::find($id);
            if(!$item) return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ] , 200);
            $item->update($data);
        }else {
            $item = Label::create($data);
        }
        
        $this->setTranslations($item,$request);
        $item->trans=$item->translations()->get();
        
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function show($id)
    {
        $item = Label::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->trans=$item->translations()->get();
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $item = Label::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->delete();
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
    
    public function setLabels(){
        return;
        $data = [
                ['1','users','global','Users','Users','المستخدمين','Utilisateurs','Benutzer'],
                ['2','localization-management','global','Localization Managment','Localization Managment','إدارة اللغات','Gestion de la localisation','Verwaltung der Lokalisierung'],
                ['3','languages-management','languages ','Languages Management','Languages Management','إدارة اللغات','Gestion des langues','Verwaltung der Sprachen'],
                ['4','labels-management','global','Labels Management','Labels Management','إدارة التسميات','Gestion des étiquettes','Verwaltung von Labels'],
                ['5','translations-management','global','Translations Management','Translations Management','إدارة الترجمات','Gestion des traductions','Verwaltung von Übersetzungen'],
                ['6','constants-system','global','System Constants','System Constants','ثوابت النظام','Constantes du système','System-Konstanten'],
                ['7','add-button','global','Add','Add','إضافة','Ajouter','Hinzufügen'],
                ['8','submit-button','global','Submit','Submit','أرسل','Soumettre','Einreichen'],
                ['9','create-button','global','Create','Create','إنشاء','Créer','Erstellen'],
                ['10','discard-button','global','Discard','Discard','إلغاء','Rejeter','Verwerfen'],
                ['11','label','global','Label','Label','تسمية','Étiquette','Kennzeichnen'],
                ['12','update-button','global','Update','Update','تحديث','Mise à jour','Aktualisieren'],
                ['13','actions-button','global','Actions','Actions','إجراءات','Actions','Aktionen'],
                ['14','delete-button','global','Delete','Delete','حذف','Supprimer','Löschen'],
                ['15','advanced','global','advanced','Advanced','متقدم','Avancé','Erweitert'],
                ['16','roles-management','global','Roles Management','Roles Management','ادارة الصلاحيات','Gestion des rôles','Verwaltung von Rollen'],
                ['17','group-classes-management','global','Group Classes Management','Group Classes Management','إدارة الدروس الجماعية','Gestion des classes de groupe','Verwaltung von Gruppenklassen'],
                ['18','sections','global','System Sections','System Sections','أقسام الموقع','Sections du système','Systemabschnitte'],
                ['19','contents','global','System Contents','System Contents','محتوى الموقع','Contenu du système','System-Inhalte'],
                ['20','experiences-management','global','Experiences Management','Experiences Management','ادارة الخبرات','Gestion des expériences','Management von Erlebnissen'],
                ['21','user','global','User','User','المستخدم','Utilisateur','Benutzer'],
                ['22','countries-management','countries','Country Management','Country Management','ادارة الدول','Gestion des pays','Verwaltung der Länder'],
                ['23','sign-up-member','global','Not a member yet?','Not a member yet?','لست عضواُ بعد؟','Vous n\'êtes pas encore membre ?','Noch kein Mitglied?'],
                ['24','submit','global','Submit','Submit','أرسل','Soumettre','einreichen'],
                ['25','language','global','Language','Language','لغة','Langue','Sprache'],
                ['26','student','global','student','Student','طالب','Étudiant','Schüler'],
                ['27','users-management','global','Users Management','Users Management','إدارة المستخدمين','Gestion des utilisateurs','Verwaltung der Benutzer'],
                ['28','private-courses-management','global','Private Courses Management','Private Courses Management','إدارة الدروس الخاصة','Gestion des cours privés','Verwaltung von Privatkursen'],
                ['29','school','global','School','School','المدرسة','École','Schule'],
                ['30','busy','global','Busy','Busy','مشغول','Occupé','Beschäftigt'],
                ['31','none','global','none','None','لا يوجد','Personne','Keine'],
                ['32','conferences-management','global','Conferences Management','Conferences Management','إدارة اللقاءات','Gestion des conférences','Verwaltung von Konferenzen'],
                ['33','php','global','php','PHP','PHP','PHP','PHP'],
                ['34','cancel','global','Cancel','Cancel','إلغاء','Annuler','Abbrechen'],
                ['35','already-member','global','Already a mamber?','Already a mamber?','هل أنت عضو بالفعل؟','Vous êtes déjà membre ?','Sind Sie bereits Mitglied?'],
                ['36','password-required','global','The password is required','The password is required','كلمة السر مطلوبة','Le mot de passe est requis','Das Passwort ist erforderlich'],
                ['37','email-in-valid','global','The entered email is not a valid email address','The entered email is not a valid email address','البريد الذي قمت بإدخاله غير صالح','L\'adresse électronique saisie n\'est pas valide','Die eingegebene E-Mail ist keine gültige E-Mail Adresse'],
                ['38','new-password','global','Setup New Password','Setup New Password','قم بإنشاء كلمة سر جديدة','Configurer un nouveau mot de passe','Neues Passwort einrichten'],
                ['39','new-password-description','global','Have you already reset your password?','Have you already reset your password?','هل قمت بإعادة تعيين كلمة السر الخاصة بك بالفعل؟','Avez-vous déjà réinitialisé votre mot de passe ?','Haben Sie Ihr Passwort bereits zurückgesetzt?'],
                ['40','password-confirm','global','Confirm Password','Confirm Password','تأكيد كلمة السر','Confirmer le mot de passe','Bestätigen Sie Ihr Passwort'],
                ['41','swal-password-reset','global','You have successfully reset your password!','You have successfully reset your password!','لقد قمت بإعادة تعيين كلمة المرور بنجاح!','Vous avez réinitialisé votre mot de passe avec succès !','Sie haben Ihr Passwort erfolgreich zurückgesetzt!'],
                ['42','swal-thank','global','Okay. Thank you!','Okay. Thank you!','حسناً، شكراً لك','C\'est bon. Merci !','Okay! Vielen Dank für die Bestätigung!'],
                ['43','remember-me','global','Remember me?','Remember me?','تذكرني؟','Vous vous souvenez de moi ?','Erinnern Sie sich an mich'],
                ['44','settings-management','global','settings-management','Settings Management','إعدادات الموقع','Gestion des paramètres','Verwaltung der Einstellungen'],
                ['45','reviews-management','global','Reviews Management','Reviews Management','ادارة التقييمات','Gestion des avis','Verwaltung von Bewertungen'],
                ['46','complaints-management','global','Complaints Management','Complaints Management','إدارة الشكاوى','Gestion des plaintes','Verwaltung von Reklamationen'],
                ['47','settings-list','global','Settings List','Settings List','قائمة الإعدادات','Liste des paramètres','Liste der Einstellungen'],
                ['48','supports-management','global','Supports Management','Support','الدعم','Support','Unterstützung'],
                ['49','favorite-management','global','Favorites Management','Favorite Management','إدارة المفضلة','Gestion des favoris','Verwaltung der Favoriten'],
                ['50','interests-management','global','Interests Management','Interests Management','إدارة الاهتمامات','Gestion des intérêts','Verwaltung der Interessen'],
                ['51','our-courses-management','global','Our Courses Management','Our Courses Management','إدارة دوراتنا','Gestion de nos cours','Verwaltung unserer Kurse'],
                ['52','orders-management','global','Orders Management','Orders Management','إدارة الطلبات','Gestion des commandes','Verwaltung der Bestellungen'],
                ['53','chats-management','global','Chats Management','Chats Management','إدارة الدردشات','Gestion des chats','Verwaltung der Chats'],
                ['54','invitations','global','Invitations','Invitations','الدعوات','Invitations','Einladungen'],
                ['55','levels-management','global','Levels Management','Levels Management','إدارة المستويات','Gestion des niveaux','Verwaltung der Levels'],
                ['56','situations-management','global','Situations Management','Situations Management','إدارة الحالات','Gestion des situations','Verwaltung von Situationen'],
                ['57','add-settings','global','add-settings','Add Settings','إضافة إعدادات','Ajouter des paramètres','Einstellungen hinzufügen'],
                ['58','subjects-management','subjects','Subjects Management','Subjects Management','إدارة المواد','Gestion des sujets','Verwaltung von Themen'],
                ['59','view-settings','global','view-settings','View Settings','عرض الإعدادات','Visualiser les paramètres','Einstellungen anzeigen'],
                ['60','degree-types-management','global','Degree Types Management','Degree Types Management','إدارة المحصل التعليمي','Gestion des types de diplômes','Verwaltung der Abschlussarten'],
                ['61','delete-settings','global','delete-settings','Delete Settings','حذف الإعدادات','Supprimer des paramètres','Einstellungen löschen'],
                ['62','week-days-management','global','Week Days Management','Week Days Management','إدارة أيام الأسبوع','Gestion des jours de la semaine','Verwaltung der Wochentage'],
                ['63','users-list','global','users-list','Users List','قائمة المستخدمين','Liste des utilisateurs','Benutzerliste'],
                ['64','world-timezones-management','global','World Timezones Management','World Timezones Management','ادارة فرق التوقيت','Gestion des fuseaux horaires','Verwaltung der Weltzeitzonen'],
                ['65','add-users','global','add-users','Add Users','إضافة مستخدمين','Ajouter des utilisateurs','Benutzer hinzufügen'],
                ['66','view-users','global','view-users','View Users','عرض المستخدمين','Visualiser les utilisateurs','Benutzer anzeigen'],
                ['67','update-users','global','update-users','Update Users','تحديث المستخدمين','Mettre à jour les utilisateurs','Benutzer aktualisieren'],
                ['68','delete-users','global','delete-users','Delete Users','حذف مستخدمين','Supprimer des utilisateurs','Benutzer löschen'],
                ['69','reviews-list','global','reviews-list','Reviews List','قائمة التقييمات','Liste des avis','Liste der Bewertungen'],
                ['70','prices-management','global','Prices Management','Prices Management','إدارة الأسعار','Gestion des prix','Preise Management'],
                ['71','sort-by-tutors','global','Sort By Tutors','Sort By Tutors','فرز حسب المدرسين','Trier par tuteurs','Nach Tutorn sortieren'],
                ['72','departments-management','global','Departments Management','Departments Management','إدارة الأقسام','Gestion des départements','Verwaltung Abteilungen'],
                ['73','categories-management','global','Categories Management','Categories Management','إدارة التصنيفات','Gestion des catégories','Verwaltung der Kategorien'],
                ['74','navigations-management','global','Navigations Management','Navigations Management','إدارة القوائم','Gestion des navigations','Verwaltung der Navigation'],
                ['75','posts-management','global','Posts Management','Posts Management','إدارة الوظائف','Gestion des postes','Verwaltung der Beiträge'],
                ['76','pages-management','global','Pages Management','Pages Management','إدارة الصفحات','Gestion des pages','Verwaltung der Seiten'],
                ['77','events-management','global','Events Management','Events Management','إدارة الفعاليات','Gestion des événements','Verwaltung von Ereignissen'],
                ['78','helps-management','global','Helps Management','Helps Management','يساعد الإدارة','Gestion de l\'aide','Verwaltung der Hilfe'],
                ['79','advertisements-management','global','Advertisements Management','Advertisements Management','إدارة الإعلانات','Gestion des annonces','Verwaltung von Werbeanzeigen'],
                ['80','videos-management','global','Videos Management','Videos Management','إدارة الفيديو','Gestion des vidéos','Verwaltung von Videos'],
                ['81','links-management','global','Links Management','Links Management','إدارة الروابط','Gestion des liens','Verwaltung von Links'],
                ['82','documents-management','global','Documents Management','Documents Management','إدارة الملفات','Gestion des documents','Verwaltung von Dokumenten'],
                ['83','images-management','global','Images Management','Images Management','إدارة الصور','Gestion des images','Verwaltung von Bildern'],
                ['84','complaints-list','global','Complaints List','Complaints List','قائمة الشكاوى','Liste des plaintes','Reklamationsliste'],
                ['85','view-complaints','global','View Complaints','View Complaints','عرض الشكاوى','Visualiser les plaintes','Reklamationen ansehen'],
                ['86','delete-complaints','global','Delete Complaints','Delete Complaints','حذف الشكاوى','Supprimer des plaintes','Reklamationen löschen'],
                ['87','reply','global','Reply','Reply','رد','Répondre','Antworten'],
                ['88','supports-list','global','Supports List','Supports List','يدعم قائمة','Liste des soutiens','Unterstützungen Liste'],
                ['89','view-supports','global','View Supports','View Supports','مشاهدة يدعم','Visualiser les soutiens','Unterstützungen anzeigen'],
                ['90','delete-supports','global','Delete Supports','Delete Supports','دعامات الحذف','Supprimer des soutiens','Unterstützungen löschen'],
                ['91','favorites-list','global','Favorite List','Favorite List','قائمة المفضلة','Liste des favoris','Favoritenliste'],
                ['92','add-favorites','global','Add Favorites','Add To Favorites','إضافة الى المفضلة','Ajouter aux favoris','Zu Favoriten hinzufügen'],
                ['93','view-favorites','global','View Favorites','View Favorites','عرض المفضلة','Visualiser les favoris','Favoriten anzeigen'],
                ['94','delete-favorites','global','Delete Favorites','Delete Favorites','حذف المفضلة','Supprimer les favoris','Favoriten löschen'],
                ['95','interests-list','global','Interests List','Interests List','قائمة الاهتمامات','Liste des intérêts','Interessenliste'],
                ['96','add-interests','global','Add Interests','Add Interests','إضافة الاهتمامات','Ajouter des intérêts','Interessen hinzufügen'],
                ['97','view-interests','global','View Interests','View Interests','عرض الاهتمامات','Visualiser les intérêts','Interessen anzeigen'],
                ['98','delete-interests','global','Delete Interests','Delete Interests','حذف الاهتمامات','Supprimer des intérêts','Interessen löschen'],
                ['99','roles-list','global','Roles List','Roles List','قائمة الصلاحيات','Liste des rôles','Rollen-Liste'],
                ['100','add-roles','global','Add Roles','Add Roles','إضافة صلاحيات','Ajouter des rôles','Rollen hinzufügen'],
                ['101','view-roles','global','View Roles','View Roles','عرض الصلاحيات','Visualiser les rôles','Rollen anzeigen'],
                ['102','update-roles','global','Update Roles','Update Roles','تحديث الصلاحيات','Mettre à jour les rôles','Rollen aktualisieren'],
                ['103','delete-roles','global','Delete Roles','Delete Roles','حذف الصلاحيات','Supprimer des rôles','Rollen löschen'],
                ['104','group-classes-list','global','Group Classes List','Group Classes List','قائمة الدروس الجماعية','Liste des classes de groupe','Liste der Gruppenklassen'],
                ['105','add-group-classes','global','Add Group Classes','Add Group Classes','إضافة دروس جماعية','Ajouter un groupe de classes','Gruppenklassen hinzufügen'],
                ['106','view-group-classes','global','View Group Classes','View Group Classes','عرض الصفوف الجماعية','Visualiser les classes de groupe','Gruppenklassen anzeigen'],
                ['107','update-group-classes','global','Update Group Classes','Update Group Classes','تحديث الصفوف الجماعية','Mettre à jour les classes de groupe','Gruppenklassen aktualisieren'],
                ['108','delete-group-classes','global','Delete Group Classes','Delete Group Classes','حذف الصفوف الجماعية','Supprimer des groupes de cours','Gruppenklassen löschen'],
                ['109','register-as-tutor','global','Register As Tutor','Register As Tutor','سجل كمدرس','S\'inscrire comme tuteur','Als Tutor registrieren'],
                ['110','un-register-as-tutor','global','Un-Register  As Tutor','Un-Register  As Tutor','الغاء التسجيل كمدرس','Se désinscrire comme tuteur','Als Tutor abmelden'],
                ['111','tutor-index','global','Tutor Index','Tutor Index','دليل المدرس','Index des tuteurs','Tutor-Index'],
                ['112','admin-conferences','global','Admin Conferences','Admin\'s Conferences','لقاءات المسؤول','Conférences de l\'administrateur','Konferenzen der Verwaltung'],
                ['113','okay.-got-it!','global','Yes. Done Successfully','Yes. Done Successfully','نعم. تم بنجاح','Oui. Fait avec succès','Ja. Erfolgreich erledigt'],
                ['114','tutor-change-conference-date','global','Change Tutor\'s Conference Date','Change Tutor\'s Conference Date','تغيير موعد لقاء المدرس','Modifier la date de la conférence du tuteur','Konferenzdatum des Tutors ändern'],
                ['115','tutor-conferences','global','Tutor Conferences','Tutor\'s Conferences','لقاءات المدرس','Conférences du tuteur','Konferenzen des Tutors'],
                ['116','student-conferences','global','Student\'s Conferences','Student\'s Conferences','لقاءات الطالب','Conférences de l\'étudiant','Schülerkonferenzen'],
                ['117','create-conference-tutor-link','global','Create Tutor\'s Conference Link','Create Tutor\'s Conference Link','إنشاء رابط لقاء مدرس','Créer un lien pour la conférence du tuteur','Link zur Tutorkonferenz erstellen'],
                ['118','create-conference-student-link','global','Create Student Conference Link','Create Student\'s Conference Link','إنشاء رابط مؤتمر الطالب','Créer le lien de la conférence de l\'élève','Link für Schülerkonferenz erstellen'],
                ['119','upload-file','global','Upload File','Upload File','رفع ملف','Télécharger un fichier','Datei hochladen'],
                ['120','add-note','global','Add Note','Add Note','إضافة ملاحظة','Ajouter une note','Notiz hinzufügen'],
                ['121','add-complaint','global','Add Complaint','Add Complaint','إضافة شكوى','Ajouter une plainte','Beanstandung hinzufügen'],
                ['122','admin-orders','global','Admin Orders','Admin\'s Orders','طلبات المسؤول','Commandes de l\'administrateur','Aufträge der Verwaltung'],
                ['123','my-orders','global','My Orders','My Orders','طلباتي','Mes commandes','Meine Anordnungen'],
                ['124','refund','global','Refund','Refund','استرداد','Remboursement','Rückerstattung'],
                ['125','tutor-change-conference-date','global','Tutor Change Conference Date','Change Tutor\'s Conference Date','موعد مؤتمر تغيير المعلم','Modifier la date de la conférence du tuteur','Termin der Tutorkonferenz ändern'],
                ['126','private-chat','global','Private Chat','Private Chat','دردشة خاصة','Chat privé','Privater Chat'],
                ['127','send-message','global','Send Message','Send Message','إرسال رسالة','Envoyer un message','Nachricht senden'],
                ['128','messages-list','global','Messages List','Messages List','قائمة الرسائل','Liste des messages','Nachrichten Liste'],
                ['129','show-message','global','Show Message','Show Message','إظهار الرسالة','Visualiser le message','Nachricht anzeigen'],
                ['130','chat-contacts','global','Chat Contacts','Chat Contacts','اتصالات الدردشة','Contacts de chat','Chat-Kontakte'],
                ['131','admin-invitations','global','Admin Invitations','Admin\'s Invitations','دعوات المسؤول','Invitations de l\'administrateur','Einladungen für Administratoren'],
                ['132','parent-invitations','global','Parent Invitations','Parent\'s Invitations','دعوات الوالدين','Invitations des parents','Einladungen der Eltern'],
                ['133','child-invitations','global','Child Invitations','Child Invitations','دعوات الأطفال','Invitations des enfants','Einladungen für Kinder'],
                ['134','send-invitation','global','Send Invitation','Send Invitation','إرسال دعوة','Envoyer une invitation','Einladung senden'],
                ['135','accept-invitation','global','Accept Invitation','Accept Invitation','قبول الدعوة','Accepter l\'invitation','Einladung annehmen'],
                ['136','reject-invitation','global','Reject Invitation','Reject Invitation','رفض الدعوة','Refuser l\'invitation','Einladung ablehnen'],
                ['137','remove-invitation','global','Remove Invitation','Remove Invitation','حذف الدعوة','Supprimer l\'invitation','Einladung entfernen'],
                ['138','languages-list','languages ','Languages List','Languages List','قائمة اللغات','Liste des langues','Liste der Sprachen'],
                ['139','add-languages','languages ','Add Languages','Add Languages','إضافة لغات','Ajouter des langues','Sprachen hinzufügen'],
                ['140','view-languages','languages ','View Languages','View Languages','عرض اللغات','Visualiser les langues','Sprachen anzeigen'],
                ['141','update-languages','languages ','Update Languages','Update Languages','تحديث اللغات','Mettre à jour les langues','Sprachen aktualisieren'],
                ['142','delete-languages','languages ','Delete Languages','Delete Languages','حذف لغات','Supprimer des langues','Sprachen löschen'],
                ['143','labels-list','global','Labels List','Labels List','قائمة التسميات','Liste des étiquettes','Etiketten Liste'],
                ['144','add-labels','global','Add Labels','Add Labels','أضف تسميات','Ajouter des étiquettes','Labels hinzufügen'],
                ['145','view-labels','global','View Labels','View Labels','عرض التسميات','Visualiser les étiquettes','Etiketten anzeigen'],
                ['146','update-labels','global','Update Labels','Update Labels','تحديث التسميات','Mettre à jour les étiquettes','Etiketten aktualisieren'],
                ['147','delete-labels','global','Delete Labels','Delete Labels','حذف التسميات','Supprimer des étiquettes','Etiketten löschen'],
                ['148','translations-list','global','Translations List','Translations List','قائمة الترجمة','Liste des traductions','Liste der Übersetzungen'],
                ['149','add-translations','global','Add Translations','Add Translation','إضافة ترجمة','Ajouter une traduction','Übersetzung hinzufügen'],
                ['150','view-translations','global','View Translations','View Translation','عرض الترجمة','Visualiser la traduction','Übersetzung anzeigen'],
                ['151','update-translations','global','Update Translations','Update Translation','تحديث الترجمة','Mettre à jour la traduction','Übersetzung aktualisieren'],
                ['152','delete-translations','global','Delete Translations','Delete Translation','حذف ترجمة','Supprimer une traduction','Übersetzung löschen'],
                ['153','countries-list','countries','Countries List','Countries List','قائمة الدول','Liste des pays','Länderliste'],
                ['154','add-countries','countries','Add Countries','Add Countries','إضافة دول','Ajouter un pays','Länder hinzufügen'],
                ['155','view-countries','countries','View Countries','View Countries','عرض الدول','Visualiser les pays','Länder anzeigen'],
                ['156','update-countries','countries','Update Countries','Update Countries','تحديث الدول','Mettre à jour les pays','Länder aktualisieren'],
                ['157','delete-countries','countries','Delete Countries','Delete Countries','حذف دول','Supprimer des pays','Länder löschen'],
                ['158','levels-list','global','Levels List','Levels List','قائمة المستويات','Liste des niveaux','Ebenen Liste'],
                ['159','add-levels','global','Add Levels','Add Levels','اضافة مستويات','Ajouter des niveaux','Ebenen hinzufügen'],
                ['160','view-levels','global','View Levels','View Levels','عرض المستويات','Visualiser les niveaux','Ebenen anzeigen'],
                ['161','update-levels','global','Update Levels','Update Levels','تحديث المستويات','Mettre à jour les niveaux','Ebenen aktualisieren'],
                ['162','delete-levels','global','Delete Levels','Delete Levels','حذف مستويات','Supprimer des niveaux','Levels löschen'],
                ['163','experiences-list','global','Experiences List','Experiences List','قائمة الخبرات','Liste des expériences','Erlebnisse Liste'],
                ['164','add-experiences','global','Add Experiences','Add Experiences','إضافة خبرات','Ajouter des expériences','Erlebnisse hinzufügen'],
                ['165','view-experiences','global','View Experiences','View Experiences','عرض الخبرات','Visualiser les expériences','Erlebnisse anzeigen'],
                ['166','update-experiences','global','Update Experiences','Update Experiences','تحديث الخبرات','Mettre à jour les expériences','Erlebnisse aktualisieren'],
                ['167','delete-experiences','global','Delete Experiences','Delete Experiences','حذف خبرات','Supprimer des expériences','Erlebnisse löschen'],
                ['168','situations-list','global','Situations List','Situations List','قائمة الحالات','Liste des situations','Situations Liste'],
                ['169','add-situations','global','Add Situations','Add Situations','إضافة حالات','Ajouter des situations','Situationen hinzufügen'],
                ['170','view-situations','global','View Situations','View Situations','عرض الحالات','Visualiser les situations','Situationen anzeigen'],
                ['171','update-situations','global','Update Situations','Update Situations','تحديث الحالات','Mettre à jour les situations','Situationen aktualisieren'],
                ['172','delete-situations','global','Delete Situations','Delete Situations','حذف حالات','Supprimer des situations','Situationen löschen'],
                ['173','subjects-list','subjects','Subjects List','Subjects List','قائمة المواد','Liste des sujets','Themen Liste'],
                ['174','add-subjects','subjects','Add Subjects','Add Subjects','إضافة المواد ','Ajouter des sujets','Themen hinzufügen'],
                ['175','view-subjects','subjects','View Subjects','View Subjects','عرض المواد','Visualiser les sujets','Themen anzeigen'],
                ['176','update-subjects','subjects','Update Subjects','Update Subjects','تحديث المواد','Mettre à jour les sujets','Themen aktualisieren'],
                ['177','delete-subjects','subjects','Delete Subjects','Delete Subjects','حذف المواد','Supprimer des sujets','Fächer löschen'],
                ['178','degree-types-list','global','Degree Types List','Degree Types List','قائمة أنواع المحصل التعليمي','Liste des types de diplômes','Liste der Abschlusstypen'],
                ['179','add-degree-types','global','Add Degree Types','Add Degree Types','إضافة أنواع المحصل التعليمي','Ajouter des types de diplômes','Abschlusstypen hinzufügen'],
                ['180','view-degree-types','global','View Degree Types','View Degree Types','عرض أنواع المحصل التعليمي','Visualiser les types de diplômes','Studiengangstypen anzeigen'],
                ['181','update-degree-types','global','Update Degree Types','Update Degree Types','تحديث أنواع المحصل التعليمي','Mettre à jour les types de diplômes','Studiengangstypen aktualisieren'],
                ['182','delete-degree-types','global','Delete Degree Types','Delete Degree Types','حذف أنواع المحصل التعليمي','Supprimer des types de diplômes','Abschlussarten löschen'],
                ['183','week-days-list','global','Week Days List','Week Days List','قائمة أيام الأسبوع','Liste des jours de la semaine','Liste der Wochentage'],
                ['184','add-week-days','global','Add Week Days','Add Week Days','إضافة أيام الأسبوع','Ajouter des jours de semaine','Wochentage hinzufügen'],
                ['185','view-week-days','global','View Week Days','View Week Days','عرض أيام الأسبوع','Visualiser les jours de la semaine','Wochentage anzeigen'],
                ['186','update-week-days','global','Update Week Days','Update Week Days','تحديث أيام الأسبوع','Mise à jour des jours de la semaine','Wochentage aktualisieren'],
                ['187','delete-week-days','global','Delete Week Days','Delete Week Days','حذف أيام الأسبوع','Supprimer les jours de la semaine','Wochentage löschen'],
                ['188','world-timezones-list','global','World Timezones List','World Timezones List','قائمة فرق التوقيت','Liste des fuseaux horaires','Liste der Weltzeitzonen'],
                ['189','add-world-timezones','global','Add World Timezones','Add World Timezones','إضافة فرق التوقيت','Ajouter des fuseaux horaires','Weltzeitzonen hinzufügen'],
                ['190','view-world-timezones','global','View World Timezones','View World Timezones','عرض فرق التوقيت','Visualiser les fuseaux horaires','Weltzeitzonen anzeigen'],
                ['191','update-world-timezones','global','Update World Timezones','Update World Timezones','تحديث فرق التوقيت','Mettre à jour les fuseaux horaires','Weltzeitzonen aktualisieren'],
                ['192','delete-world-timezones','global','Delete World Timezones','Delete World Timezones','حذف فرق التوقيت','Supprimer les fuseaux horaires','Weltzeitzonen löschen'],
                ['193','prices-list','global','Prices List','Prices List','قائمة الأسعار','Liste des prix','Preise Liste'],
                ['194','add-prices','global','Add Prices','Add Prices','أضف أسعار','Ajouter des prix','Preise hinzufügen'],
                ['195','view-prices','global','View Prices','View Prices','عرض الأسعار','Afficher les prix','Preise anzeigen'],
                ['196','update-prices','global','Update Prices','Update Prices','تحديث الأسعار','Mettre à jour les prix','Preise aktualisieren'],
                ['197','delete-prices','global','Delete Prices','Delete Prices','حذف أسعار','Supprimer les prix','Preise löschen'],
                ['198','sort-by-tutors-list','global','Sort By Tutors List','Sort By Tutors List','فرز حسب قائمة المدرسين','Trier par liste de tuteurs','Nach Tutorn sortieren Liste'],
                ['199','add-sort-by-tutors','global','Add Sort By Tutors','Add Sort By Tutors','أضف الفرز من قبل المعلمين','Ajouter un tuteur','Nach Tutorn sortieren hinzufügen'],
                ['200','view-sort-by-tutors','global','View Sort By Tutors','View Sort By Tutors','عرض الفرز من قِبل المدرسين','Afficher les tuteurs triés par ordre de priorité','Nach Tutorn sortieren anzeigen'],
                ['201','update-sort-by-tutors','global','Update Sort By Tutors','Update Sort By Tutors','تحديث الفرز بواسطة المعلمين','Mettre à jour le tri des tuteurs','Aktualisieren Nach Tutorn sortieren'],
                ['202','delete-sort-by-tutors','global','Delete Sort By Tutors','Delete Sort By Tutors','حذف الفرز بواسطة المعلمين','Supprimer le tri des tuteurs','Löschen Nach Tutorn sortieren'],
                ['203','departments-list','global','Departments List','Departments List','قائمة الأقسام','Liste des départements','Abteilungen Liste'],
                ['204','add-departments','global','Add Departments','Add Departments','إضافة أقسام','Ajouter des départements','Abteilungen hinzufügen'],
                ['205','view-departments','global','View Departments','View Departments','عرض الاقسام','Visualiser les départements','Abteilungen anzeigen'],
                ['206','update-departments','global','Update Departments','Update Departments','تحديث الاقسام','Mettre à jour les départements','Abteilungen aktualisieren'],
                ['207','delete-departments','global','Delete Departments','Delete Departments','أقسام','Supprimer des départements','Abteilungen löschen'],
                ['208','categories-list','global','Categories List','Categories List','قائمة التصنيفات','Liste des catégories','Kategorien-Liste'],
                ['209','add-categories','global','Add Categories','Add Categories','إضافة تصنيفات','Ajouter des catégories','Kategorien hinzufügen'],
                ['210','view-categories','global','View Categories','View Categories','عرض التصنيفات','Visualiser les catégories','Kategorien anzeigen'],
                ['211','update-categories','global','Update Categories','Update Categories','تحديث التصنيفات','Mettre à jour les catégories','Kategorien aktualisieren'],
                ['212','delete-categories','global','Delete Categories','Delete Categories','حذف تصنيفات','Supprimer des catégories','Kategorien löschen'],
                ['213','courses-list','global','Courses List','Courses List','قائمة الدروس','Liste des cours','Kurse Liste'],
                ['214','add-courses','global','Add Courses','Add Courses','إضافة دروس','Ajouter des cours','Kurse hinzufügen'],
                ['215','view-courses','global','View Courses','View Courses','عرض الدروس','Visualiser les cours','Kurse anzeigen'],
                ['216','update-courses','global','Update Courses','Update Courses','تحديث الدروس','Mettre à jour les cours','Kurse aktualisieren'],
                ['217','delete-courses','global','Delete Courses','Delete Courses','حذف دروس','Supprimer des cours','Kurse löschen'],
                ['218','navigations-list','global','Navigations List','Navigations List','قوائم الموقع','Liste des navigations','Navigationsliste'],
                ['219','add-navigations','global','Add Navigations','Add Navigations','إضافة قوائم','Ajouter une navigation','Navigationen hinzufügen'],
                ['220','view-navigations','global','View Navigations','View Navigations','عرض القوائم','Visualiser les navigations','Navigationen anzeigen'],
                ['221','update-navigations','global','Update Navigations','Update Navigations','تحديث القوائم','Mettre à jour les navigations','Navigationen aktualisieren'],
                ['222','delete-navigations','global','Delete Navigations','Delete Navigations','حذف القوائم','Supprimer des navigations','Navigationen löschen'],
                ['223','posts-list','global','Posts List','Posts List','قائمة المواضيع','Liste des messages','Beiträge Liste'],
                ['224','add-posts','global','Add Posts','Add Posts','اضافة المواضيع','Ajouter des messages','Beiträge hinzufügen'],
                ['225','view-posts','global','View Posts','View Posts','عرض المواضيع','Visualiser les messages','Beiträge anzeigen'],
                ['226','update-posts','global','Update Posts','Update Posts','تحديث المواضيع','Mettre à jour les messages','Beiträge aktualisieren'],
                ['227','delete-posts','global','Delete Posts','Delete Posts','حذف المواضيع','Supprimer des messages','Beiträge löschen'],
                ['228','pages-list','global','Pages List','Pages List','قائمة المواضيع','Liste des pages','Seiten Liste'],
                ['229','add-pages','global','Add Pages','Add Pages','اضافة صفحات','Ajouter des pages','Seiten hinzufügen'],
                ['230','view-pages','global','View Pages','View Pages','عرض الصفحات','Afficher les pages','Seiten anzeigen'],
                ['231','update-pages','global','Update Pages','Update Pages','تحديث الصفحات','Mettre à jour les pages','Seiten aktualisieren'],
                ['232','delete-pages','global','Delete Pages','Delete Pages','حذف الصفحات','Supprimer des pages','Seiten löschen'],
                ['233','events-list','global','Events List','Events List','قائمة الفعاليات','Liste des événements','Liste der Ereignisse'],
                ['234','add-events','global','Add Events','Add Events','اضافة الفعاليات','Ajouter des événements','Ereignisse hinzufügen'],
                ['235','view-events','global','View Events','View Events','عرض الفععاليات','Visualiser les événements','Ereignisse anzeigen'],
                ['236','update-events','global','Update Events','Update Events','تحديث الفعاليات','Mise à jour des événements','Ereignisse aktualisieren'],
                ['237','delete-events','global','Delete Events','Delete Events','حذف الفعاليات','Supprimer des événements','Ereignisse löschen'],
                ['238','helps-list','global','Helps List','Helps List','قائمة المساعدات','Liste des aides','Liste der Hilfen'],
                ['239','add-helps','global','Add Helps','Add Helps','اضافة مساعدات','Ajouter une aide','Hilfen hinzufügen'],
                ['240','view-helps','global','View Helps','View Helps','عرض المساعدة','Visualiser les aides','Hilfen anzeigen'],
                ['241','update-helps','global','Update Helps','Update Helps','تحديث المساعدات','Mise à jour des aides','Hilfen aktualisieren'],
                ['242','delete-helps','global','Delete Helps','Delete Helps','حذف المساعدات','Supprimer des aides','Hilfen löschen'],
                ['243','advertisements-list','global','Advertisements List','Advertisements List','قائمة الإعلانات','Liste des publicités','Inserate Liste'],
                ['244','add-advertisements','global','Add Advertisements','Add Advertisements','اضافة اعلانات','Ajouter des publicités','Inserate hinzufügen'],
                ['245','view-advertisements','global','View Advertisements','View Advertisements','عرض الإعلانات','Visualiser les publicités','Anzeigen anzeigen'],
                ['246','update-advertisements','global','Update Advertisements','Update Advertisements','تحديث الاعلانات','Mettre à jour les annonces','Inserate aktualisieren'],
                ['247','delete-advertisements','global','Delete Advertisements','Delete Advertisements','حذف الاعلانات','Supprimer des annonces','Inserate löschen'],
                ['248','videos-list','global','Videos List','Videos List','قائمة الفيديوهات','Liste des vidéos','Videos Liste'],
                ['249','add-videos','global','Add Videos','Add Videos','اضافة الفيديوهات','Ajouter des vidéos','Videos hinzufügen'],
                ['250','view-videos','global','View Videos','View Videos','عرض الفيديوهات','Visualiser les vidéos','Videos ansehen'],
                ['251','update-videos','global','Update Videos','Update Videos','تحديث الفيديوهات','Mettre à jour les vidéos','Videos aktualisieren'],
                ['252','delete-videos','global','Delete Videos','Delete Videos','حذف الفيديوهات','Supprimer les vidéos','Videos löschen'],
                ['253','links-list','global','Links List','Links List','قائمة الروابط','Liste des liens','Links-Liste'],
                ['254','add-links','global','Add Links','Add Links','اضافة الروابط','Ajouter des liens','Links hinzufügen'],
                ['255','view-links','global','View Links','View Links','عرض الروابط','Visualiser les liens','Links anzeigen'],
                ['256','update-links','global','Update Links','Update Links','تحديث الروابط','Mettre à jour les liens','Links aktualisieren'],
                ['257','delete-links','global','Delete Links','Delete Links','حذف الروابط','Supprimer des liens','Links löschen'],
                ['258','documents-list','global','Documents List','Documents List','قائمة الملفات','Liste des documents','Dokumente Liste'],
                ['259','add-documents','global','Add Documents','Add Documents','اضافة الملفات','Ajouter des documents','Dokumente hinzufügen'],
                ['260','view-documents','global','View Documents','View Documents','عرض الملفات','Visualiser les documents','Dokumente anzeigen'],
                ['261','update-documents','global','Update Documents','Update Documents','تحديث الملفات','Mettre à jour les documents','Dokumente aktualisieren'],
                ['262','delete-documents','global','Delete Documents','Delete Documents','حذف الملفات','Supprimer des documents','Dokumente löschen'],
                ['263','images-list','global','Images List','Images List','قائمة الصور','Liste des images','Bilder Liste'],
                ['264','add-images','global','Add Images','Add Images','اضافة الصور','Ajouter des images','Bilder hinzufügen'],
                ['265','view-images','global','View Images','View Images','عرض الصور','Visualiser les images','Bilder anzeigen'],
                ['266','update-images','global','Update Images','Update Images','تحديث الصور','Mise à jour des images','Bilder aktualisieren'],
                ['267','delete-images','global','Delete Images','Delete Images','حذف الصور','Supprimer des images','Bilder löschen'],
                ['268','balance','global','Balance','Balance','الرصيد','Équilibrer','Ausgleichen'],
                ['269','points','global','Points','Points','النقاط','Points','Punkte'],
                ['270','categories','global','Categories','Categories','التصنيفات','Catégories','Kategorien'],
                ['271','home','global','Home','Home','الرئيسية','Accueil','Startseite'],
                ['272','group-classes','global','Group Classes','Group Classes','الصفوف الجماعية','Cours collectifs','Gruppenunterricht'],
                ['273','our-courses','global','Courses','Courses','الدروس','Cours','Kurse'],
                ['274','about-us','global','About Us','About Us','معلومات عنا','A propos de nous','Über uns'],
                ['275','search','global','Search','Search','بحث','Rechercher','Suche'],
                ['276','explore-tutors','global','Explore Tutors','Explore Tutors','استكشف المدرسين','Explorer les tuteurs','NachhilfeTutor suchen'],
                ['277','find-tutors','global','Find Tutors','Find Tutors','اعثر على مدرسين','Trouver des tuteurs','Tutoren finden'],
                ['278','become-a-tutor','global','Become A Tutor','Become A Tutor','كن مدرسًا','Devenir tuteur','Tutor werden'],
                ['279','experience-tutors','global','Experience Tutors','Experience Tutors','مدرسين خبراء','Expérimenter les tuteurs','Tutoren erleben'],
                ['280','tutor-nationalities','global','Tutor Nationalities','Tutor Nationalities','جنسيات المعلم','Nationalités des tuteurs','Nationalitäten der Tutor'],
                ['281','5-stars-tutor-reviews','global','5 Stars Tutor Reviews','5 Stars Tutor Reviews','5 نجوم مراجعات المعلم','Commentaires de tuteurs 5 étoiles','5-Sterne-Tutorbewertungen'],
                ['282','subjects-taught','subjects','Subjects Taught','Subjects Taught','المواضيع التي تم تدريسها','Sujets enseignées','Unterrichtete Fächer'],
                ['283','search-by-tutor-name','global','Search By Tutor Name','Search By Tutor Name','البحث عن طريق اسم المعلم','Recherche par nom de tuteur','Suche nach Name des Tutors'],
                ['284','price-range','global','PRICE RANGE','PRICE RANGE','نطاق السعر','PRIX','PREISSPANNE'],
                ['285','country-of-birth','global','COUNTRY OF BIRTH','COUNTRY OF BIRTH','بلد الميلاد','PAYS DE NAISSANCE','GEBURTSLAND'],
                ['286','i-am-available-at','global','Availability Time','Availability Time','أنا متاح في','Heure de disponibilité','Verfügbarkeit Zeit'],
                ['287','subject','global','Subject','Subject','المادة','Sujet','Fach'],
                ['288','from','global','From','From','من','De','Von'],
                ['289','to','global','To','To','الى','A','Bis'],
                ['290','also-speaks','global','Also Speaks','Also Speaks','يتحدث أيضا','Parle aussi','Spricht auch'],
                ['291','country','global','Country','Country','دولة','Pays','Land'],
                ['292','specializations','specializations','Specializations','Specializations','تخصصات','  Spécialités ','Spezialisierung'],
                ['293','native-language','global','Native Language','Native Language','اللغة الأم','Langue maternelle','Muttersprache'],
                ['294','sort-by','global','Sort By','Sort By','فرز حسب','Trier par','Sortieren nach'],
                ['295','full-name','global','Full Name','Full Name','الاسم الكامل','Nom complet','Vollständiger Name'],
                ['296','popular-group-classes','global','Popular Group Classes','Popular Group Classes','فئات جماعية شعبية','Cours collectifs populaires','Beliebte Gruppenkurse'],
                ['297','popular-events','global','Popular Events','Popular Events','الأحداث الشعبية','Événements populaires','Beliebte Veranstaltungen'],
                ['298','navigation','global','NAVIGATION','NAVIGATION','القائمة الرئيسية','NAVIGATION','NAVIGATION'],
                ['299','events','global','Events','Events','الفعاليات','Evénements','Veranstaltungen'],
                ['300','contact-us','global','Contact Us','Contact Us','اتصل بنا','Contact','Kontaktieren Sie uns'],
                ['301','help-center','global','Help Center','Help Center','مركز المساعدة','Centre d\'aide','Hilfe-Center'],
                ['302','buy-points','global','Buy Points','Buy Points','شراء نقاط','Acheter des points','Punkte kaufen'],
                ['303','sign-out','global','Sign Out','Sign Out','تسجيل الخروج','Se déconnecter','Abmelden'],
                ['304','for-tutors','global','For Tutors','For Tutors','للمعلمين','Pour les tuteurs','Für Tutoren'],
                ['305','become-an-online-tutor','global','Become An Online Tutor','Become An Online Tutor','كن مدرسًا عبر الإنترنت','Devenir tuteur en ligne','Werden Sie ein Online-Tutor'],
                ['306','tutors','global','Tutors','Tutors','مدرسون','Tuteurs','NachhilfeTutor'],
                ['307','images','global','Images','Images','الصور','Images','Bilder'],
                ['308','videos','global','Videos','Videos','الفيديوهات','Vidéos','Videos'],
                ['309','documents','global','Documents','Documents','الملفات','Documents','Dokumente'],
                ['310','links','global','Links','Links','الروابط','Liens','Links'],
                ['311','pages','global','Pages','Pages','الصفحات','Pages','Seiten'],
                ['312','social-media','global','Social Media','Social Media','وسائل التواصل الاجتماعي','Médias sociaux','Soziale Medien'],
                ['313','teach-arabic-online','global','Teach Arabic Online','Teach Arabic Online','تعليم اللغة العربية اون لاين','Enseigner l\'arabe en ligne','Arabisch online unterrichten'],
                ['314','teach-english-online','global','Teach English Online','Teach English Online','علِّم اللغة الإنجليزية عبر الإنترنت','Enseigner l\'anglais en ligne','Englisch online unterrichten'],
                ['315','teach-french-online','global','Teach French Online','Teach French Online','علِّم اللغة الفرنسية عبر الإنترنت','Enseigner le français en ligne','Online Französisch unterrichten'],
                ['316','teach-german-online','global','Teach German Online','Teach German Online','علِّم اللغة الألمانية عبر الإنترنت','Enseigner l\'allemand en ligne','Deutsch Online unterrichten'],
                ['317','teach-spanish-online','global','Teach Spanish Online','Teach Spanish Online','علِّم اللغة الإسبانية عبر الإنترنت','Enseigner l\'espagnol en ligne','Online Spanisch unterrichten'],
                ['318','location','global','Location','Location','المكان','Lieu de travail','Standort'],
                ['319','address','global','Address','Address','العنوان','Adresse','Adresse'],
                ['320','facebook','global','Facebook','Facebook','فيسبوك','Facebook','Facebook'],
                ['321','instagram','global','Instagram','Instagram','انستغرام','Instagram','Instagram'],
                ['322','whats-app','global','Whats App','Whats App','واتس اب','Whats App','Whats App'],
                ['323','linked-in','global','Linkedin','Linkedin','لينكيد ان','Linkedin','Linkedin'],
                ['324','youtube','global','Youtube','Youtube','يوتيوب','Youtube','Youtube'],
                ['325','usd','global','usd','USD','دولار','USD','USD'],
                ['326','eur','global','eur','EUR','يورو','EUR','EUR'],
                ['327','submit','global','Submit','Submit','أرسل','Soumettre','Einreichen'],
                ['328','wait-please','global','Please wait ...','Please wait ...','من فضلك انتظر','Veuillez patienter ...','Bitte warten ...'],
                ['329','popular-packages','global','Popular Packages','Popular Packages','الحزم الشائعة','Forfaits populaires','Beliebte Pakete'],
                ['330','packages','global','Packages','Packages','حزم','Forfaits','Pakete'],
                ['331','view-packages','global','View Packages','View Packages','عرض الباقات','Visualiser les forfaits','Pakete ansehen'],
                ['332','classes','global','Classes','Classes','صفوف','Cours','Unterricht'],
                ['333','view-tutors','global','View Tutors','View Tutors','عرض المدرسين','Visualiser les tuteurs','Tutoren ansehen'],
                ['334','popular-tutors','global','Popular Tutors','Popular Tutors','المدرسون الشائعون','Tuteurs populaires','Beliebte Tutoren'],
                ['335','cancel','global','Cancel','Cancel','إلغاء','Annuler','Abbrechen'],
                ['336','password-forgot-title','global','Forgot your password?','Forgot your password?','هل نسيت كلمة المرور؟','Mot de passe oublié ?','Haben Sie Ihr Passwort vergessen?'],
                ['337','email','global','Email','Email','البريد الإلكتروني','Courriel','E-Mail an'],
                ['338','sign-in-header','global','Already have an account?','Already have an account?','هل تملك حساب بالفعل؟','Vous avez déjà un compte ?','Haben Sie bereits ein Konto?'],
                ['339','sign-in-title','global','Sign in','Sign in','تسجيل الدخول','S\'identifier','Anmelden'],
                ['340','sign-in-description','global','Get unlimited access & earn money','Get un-limited access and earn courses','احصل على وصول غير محدود واكسب الدورات التدريبية','Obtenez un accès illimité et gagnez des cours','Unbeschränkten Zugang erhalten und Kurse verdienen'],
                ['341','account-type-title','global','Account Type','Account Type','نوع الحساب','Type de compte','Konto Typ'],
                ['342','account-type-description','global','Set up your account type','Set up your account type','قم بإعداد نوع حسابك','Définissez votre type de compte','Legen Sie Ihren Kontotyp fest'],
                ['343','account-information-title','global','Account Information','Account Information','معلومات الحساب','Informations sur le compte','Konto-Informationen'],
                ['344','account-information-description','global','Set up your account basic information','Set up your account basic information','قم بإعداد معلومات حسابك الأساسية','Configurez les informations de base de votre compte','Richten Sie Ihre grundlegenden Kontoinformationen ein'],
                ['345','biography-information-title','global','global.biography-information-title','Biography Information','معلومات السيرة الذاتية','Informations sur la biographie','Biografie-Informationen'],
                ['346','biography-information-description','global','Set up your biography information information','Set up your biography information information','قم بإعداد معلومات السيرة الذاتية الخاصة بك','Configurer les informations de votre biographie','Richten Sie Ihre Biographie-Informationen ein '],
                ['347','daily-availability-time-title','global','Daily Availability Time','Daily Availability Time','زمن التواجد اليومي','Heure de disponibilité quotidienne','Tägliche Verfügbarkeitszeit'],
                ['348','daily-availability-time-description','global','Set up your daily availability time','Set up your daily availability time','قم بإعداد زمن التواجد اليومي الخاص بك','Définissez votre heure de disponibilité quotidienne','Legen Sie Ihre tägliche Verfügbarkeitszeit fest'],
                ['349','price-information-title','global','Price Information','Price Information','معلومات السعر','Informations sur les prix','Preis-Informationen'],
                ['350','price-information-description','global','Set up your price information','Set up your price information','قم بإعداد معلومات السعر الخاصة بك','Définissez vos informations de prix','Richten Sie Ihre Preisinformationen ein'],
                ['351','certification-information-title','global','Certification Information','Certification Information','معلومات الشهادة','Informations sur la certification','Informationen zur Zertifizierung'],
                ['352','certification-information-description','global','Set up your certification information','Set up your certification information','قم بإعداد معلومات الشهادة الخاصة بك','Configurez vos informations de certification','Richten Sie Ihre Zertifizierungsinformationen ein'],
                ['353','tutor-video-title','global','Tutor Video','Tutor Video','الفيديو الخاص بالمدرس الخاص','Vidéo du tuteur','Tutor-Video'],
                ['354','tutor-video-description','global','Upload a video about your self and experience','Upload a video about your self and experience','قم بتحميل فيديو عن نفسك وتجربتك','Téléchargez une vidéo sur vous et votre expérience','Laden Sie ein Video über sich und Ihre Erfahrung hoch'],
                ['355','continue','global','Continue','Continue','متابعة','Continuer','Weiter'],
                ['356','previous','global','Previous','Previous','السابق','Précédent','Vorherige'],
                ['357','submit','global','Submit','Submit','أرسل','Soumettre','Einreichen'],
                ['358','wait-please','global','Wait Please','Wait Please','انتظر من فضلك','Attendre S.V.P.','Bitte warten'],
                ['359','cancel','global','Cancel','Cancel','إلغاء','Annuler','Abbrechen'],
                ['360','password-forgot','global','Forgot Password','Forgot Password','نسيت كلمة المرور','Mot de passe oublié','Passwort vergessen'],
                ['361','sign-up','global','Sign-up','Sign-up','إنشاء حساب','S\'inscrire','Anmeldung'],
                ['362','password-forgot-title','global','Forgot Password ?','Forgot Password ?','نسيت كلمة المرور؟','Mot de passe oublié ?','Passwort vergessen?'],
                ['363','password-forgot-description','global','Enter your email to reset your password.','Enter your email to reset your password.','أدخل بريدك الإلكتروني لإعادة تعيين كلمة المرور الخاصة بك.','Entrez votre email pour réinitialiser votre mot de passe.','Geben Sie Ihre E-Mail-Adresse ein, um Ihr Passwort zurückzusetzen.'],
                ['364','password-new-title','global','Reset your password','Setup New Password','أعد ضبط كلمه السر','Créer un nouveau mot de passe','Neues Passwort einrichten'],
                ['365','password-new-description','global','Please, set up a new and strong password','Please, set up a new and strong password','من فضلك، قم بإنشاء كلمة مرور جديدة وقوية','Veuillez créer un nouveau mot de passe fort','Bitte geben Sie ein neues und sicheres Passwort ein'],
                ['366','global.avatar','global','Avatar','Avatar','صورة شخصية','Avatar','Avatar'],
                ['367','first-name','global','First Name','First Name','الاسم الأول','Prénom','Vorname'],
                ['368','last-name','global','Last Name','Last Name','الاسم الأخير','Nom de famille','Nachname'],
                ['369','email','global','E-mail','E-mail','البريد الإلكتروني','E-mail (en anglais)','E-Mail'],
                ['370','password','global','Password','Password','كلمة المرور','Mot de passe','Kennwort'],
                ['371','password-confirm','global','Password confirm','Password confirm','تأكيد كلمة المرور','Confirmation du mot de passe','Passwort bestätigen'],
                ['372','password-strength','global','Password strength','Password strength','قوة كلمة المرور','Force du mot de passe','Stärke des Passworts'],
                ['373','remember-me','global','Remember me','Remember me','تذكرني','Se souvenir de moi','Erinnern Sie mich'],
                ['374','first-name-required','global','First name required','First name required','الاسم الأول مطلوب','Prénom requis','Vorname erforderlich'],
                ['375','last-name-required','global','Last name required','Last name required','الاسم الأخير مطلوب','Nom de famille requis','Nachname erforderlich'],
                ['376','email-required','global','E-mail required','E-mail required','البريد الإلكتروني مطلوب','E-mail requis','E-Mail erforderlich'],
                ['377','email-in-valid','global','E-mail in valid','E-mail in valid','البريد الإلكتروني غير صالح','E-mail valide','E-Mail in gültig'],
                ['378','profile-title','global','Profile','Profile','الملف الشخصي','Profil','Profil'],
                ['379','profile-description','global','Edit your profile','Edit your profile','تعديل الملف الشخصي الخاص بك','Modifier votre profil','Bearbeiten Sie Ihr Profil'],
                ['380','personal-information-title','global','Personal information','Personal information','المعلومات الشخصية','Informations personnelles','Persönliche Informationen'],
                ['381','personal-information-description','global','Set up your personal information','Set up your personal information','قم بإعداد معلوماتك الشخصية','Configurez vos informations personnelles','Legen Sie Ihre persönlichen Daten fest'],
                ['382','home','global','Home','Home','الرئيسية','Accueil','Startseite'],
                ['383','dashboard','global','Dashboard','Dashboard','لوحة التحكم','Tableau de bord','Dashboard'],
                ['384','contact-us','global','Contact us','Contact us','اتصل بنا','Nous contacter','Kontakt'],
                ['385','avatar','global','Avatar','Avatar','الصورة الشخصية','Avatar','Avatar'],
                ['386','avatar-description','global','Allowed file types: png, jpg, jpeg','Allowed file types: png, jpg, jpeg','أنواع الملفات المسموح بها: png، jpg، jpeg','Types de fichiers autorisés : png, jpg, jpeg','Erlaubte Dateitypen: png, jpg, jpeg'],
                ['387','date-of-birth','global','Date of bitrh','Date of bitrh','تاريخ الميلاد','Date du bitrh','Geburtsdatum'],
                ['388','phone-number','global','Phone number','Phone number','رقم الهاتف','Numéro de téléphone','Telefon-Nummer'],
                ['389','country','global','Country','Country','البلد','Pays d\'origine','Land'],
                ['390','native-language','global','Native Language','Native Language','اللغة الأم','Langue maternelle','Muttersprache'],
                ['391','teaching-subject','global','Teaching Subject','Teaching Subject','موضوع التدريس','Matière enseignée','Unterrichtsfach'],
                ['392','teaching-experience','global','Teaching Experience','Teaching Experience','الخبرة في مجال التدريس','Expérience dans l\'enseignement','Erfahrung in der Lehre'],
                ['393','situation','global','Situation','Situation','الحالة','Situation','Situation'],
                ['394','headline','global','Headline','Headline','العنوان الرئيسي','A la une','Schlagzeile'],
                ['395','interests','global','Interests','Interests','الاهتمامات','Intérêts','Interessen'],
                ['396','motivation','global','Motivation','Motivation','الحافز','Motivation','Motivation'],
                ['397','specialization','global','Specialization','Specialization','التخصص','Spécialisation','Spezialisierung'],
                ['398','experience','global','Experience','Experience','الخبرة','Expérience','Erfahrung'],
                ['399','methodology','global','Methodology','Methodology','المنهجية','Méthodologie','Methodik'],
                ['400','hourly-price','global','Hourly price','Hourly price','سعر الساعة','Prix horaire','Stündlicher Preis'],
                ['401','from','global','From','From','من','De','Von'],
                ['402','to','global','To','To','إلى','Au','bis'],
                ['403','certification-subject','global','Certification Subject','Certification Subject','موضوع الشهادة','Sujet de la certification','Thema der Zertifizierung'],
                ['404','certification-name','global','Certification Name','Certification Name','اسم الشهادة','Nom de la certification','Name der Zertifizierung'],
                ['405','certification-description','global','Certification Description','Certification Description','وصف الشهادة','Description de la certification','Beschreibung der Zertifizierung'],
                ['406','certification-issued-by','global','Certification Issued By','Certification Issued By','الشهادة صادرة عن','Certification délivrée par','Zertifizierung ausgestellt von'],
                ['407','certification-year-from','global','Certification Year From','Certification Year From','سنة الشهادة من','Année de certification du','Zertifizierungsjahr von'],
                ['408','certification-year-to','global','Certification Year To','Certification Year To','سنة الشهادة إلى','Année de certification jusqu\'au','Zertifizierungsjahr bis'],
                ['409','certification-file','global','Certification File','Certification File','ملف الشهادة','Fichier de certification','Zertifizierungsdatei'],
                ['410','video-file-description','global','Upload a video about your self and experience, talk about your headline, interest, motivation, and methodology','Upload a video about your self and experience, talk about your headline, interest, motivation, and methodology','قم بتحميل مقطع فيديو عن نفسك وتجربتك، وتحدث عن محتواك الرئيسي واهتماماتك ودوافعك','Téléchargez une vidéo sur votre personne et votre expérience, parlez de votre titre, de votre intérêt, de votre motivation et de votre méthodologie.','Laden Sie ein Video über sich selbst und Ihre Erfahrungen hoch, sprechen Sie über Ihre Überschrift, Ihr Interesse, Ihre Motivation und Ihre Methodik'],
                ['411','video-file','global','Video File','Video File','ملف الفيديو','Fichier vidéo','Video-Datei'],
                ['412','account-type','global','Account Type','Account Type','نوع الحساب','Type de compte','Konto Typ'],
                ['413','tutor-account-title','global','Tutor Account','Tutor Account','حساب المدرس','Compte de tuteur','Tutor Konto'],
                ['414','tutor-account-description','global','Start teaching others what you are specialized in','Start teaching others what you are specialized in','ابدأ بتعليم الآخرين ما أنت متخصص فيه','Commencez à enseigner aux autres ce dans quoi vous êtes spécialisé','Bringen Sie anderen bei, worauf Sie spezialisiert sind'],
                ['415','student-account-title','global','Student Account','Student Account','حساب الطالب','Compte étudiant','Studenten-Konto'],
                ['416','student-account-description','global','Create student account and start learning from others','Create student account and start learning from others','أنشئ حساب طالب وابدأ في التعلم من الآخرين','Créez un compte étudiant et commencez à apprendre des autres','Erstellen Sie ein Studentenkonto und beginnen Sie von anderen zu lernen'],
                ['417','sign-in','global','Sign-in','Sign-in','تسجيل الدخول','S\'identifier','Anmeldung'],
                ['418','password-confirm-does-not-match','global','The password and its confirmation are not same','The password and its confirmation are not same','كلمة السر وتأكيدها ليسا متماثلين','Le mot de passe et sa confirmation ne sont pas identiques','Das Passwort und seine Bestätigung sind nicht identisch'],
                ['419','password-confirm-required','global','The password confirmation is required','The password confirmation is required','تأكيد كلمة المرور مطلوب','La confirmation du mot de passe est requise','Die Bestätigung des Passworts ist erforderlich'],
                ['420','password-required','global','Password is required','Password is required','كلمة السر مطلوبة','Le mot de passe est requis','Das Passwort ist erforderlich'],
                ['421','courses','global','Courses','Courses','الدروس','Cours','Kurse'],
                ['422','classes','global','Classes','Classes','الصفوف','Cours','Lehrveranstaltungen'],
                ['423','library','global','Library','Library','المكتبة','Bibliothèque','Bibliothek'],
                ['424','pages','global','Pages','Pages','الصفحات','Pages de la bibliothèque','Seiten'],
                ['425','group-classes','global','Group Classes','Group Classes','الدروس الجماعية','Cours collectifs','Gruppen-Kurse'],
                ['426','navigation','global','Navigation','Navigation','تصفح موقعنا','Navigation','Navigation'],
                ['427','about-us','global','ِAbout us','ِAbout us','عنا','ِA propos de nous','ِÜber uns'],
                ['428','categories','global','Categories','Categories','تصنيفات','Catégories','Kategorien'],
                ['429','events','global','Events','Events','الفعاليات','Evénements','Veranstaltungen'],
                ['430','help-center','global','Help Center','Help Center','مركز المساعدة','Centre d\'aide','Hilfe-Center'],
                ['431','for-tutors','global','For Tutors','For Tutors','من أجل المدرسين','Pour les tuteurs','Für Tutoren'],
                ['432','become-an-online-tutor','global','Become an online tutor','Become an online tutor','كن مدرسًا عبر الإنترنت','Devenir tuteur en ligne','Werden Sie ein Online-Tutor'],
                ['433','teach-arabic-online','global','Teach Arabic online','Teach Arabic online','علِّم اللغة العربية عبر الانترنت','Enseigner l\'arabe en ligne','Arabisch online unterrichten'],
                ['434','teach-english-online','global','Teach English online','Teach English online','علّم اللغة الإنجليزية عبر الانترنت','Enseigner l\'anglais en ligne','Englisch online unterrichten'],
                ['435','teach-french-online','global','Teach French online','Teach French online','علّم اللغة الفرنسية عبر الانترنت','Enseigner le français en ligne','Französisch online unterrichten'],
                ['436','teach-german-online','global','Teach German online','Teach German online','تعليم اللغة الألمانية عبر الانترنت','Enseigner l\'allemand en ligne','Deutsch online unterrichten'],
                ['437','teach-spanish-online','global','Teach Spanish online','Teach Spanish online','علّم اللغة الإسبانية عبر الانترنت','Enseigner l\'espagnol en ligne','Spanisch online unterrichten'],
                ['438','location','global','Location','Location','المكان','Lieu de travail','Standort'],
                ['439','address','global','Address','Address','العنوان','Adresse','Adresse'],
                ['440','social-media','global','Social Media','Social Media','مواقع التواصل الإجتماعي','Médias sociaux','Soziale Medien'],
                ['441','facebook','global','Facebook','Facebook','الفيسبوك','Facebook','Facebook'],
                ['442','instagram','global','Instagram','Instagram','انستغرام','Instagram','Instagram'],
                ['443','whats-app','global','Whats app','Whats app','واتساب','Whats app','Whats-App'],
                ['444','linked-in','global','Linked-in','Linked-in','لينكد ان','Linked-in','Linked-in'],
                ['445','youtube','global','Youtube','Youtube','يوتيوب','Youtube','Youtube'],
                ['446','search','global','Search','Search','البحث','Recherche','Suche'],
                ['447','categories-popular','global','Popular Categories','Popular Categories','التصنيفات الشائعة','Catégories populaires','Beliebte Kategorien'],
                ['448','view-categories','global','View Categories','View Categories','عرض التصنيفات','Visualiser les catégories','Kategorien anzeigen'],
                ['449','group-classes-popular','global','Popular Group Classes','Popular Group Classes','الدروس الجماعية الشائعة','Cours collectifs populaires','Beliebte Gruppenkurse'],
                ['450','view-group-classes','global','View Group Classes','View Group Classes','عرض الصفوف الجماعية','Visualiser les cours collectifs','Gruppenkurse ansehen'],
                ['451','our-courses-popular','global','Our Popular Courses','Our Popular Courses','دروسنا الشائعة','Nos cours populaires','Unsere beliebten Kurse'],
                ['452','events-popular','global','Popular Events','Popular Events','الفعاليات الشائعة','Événements populaires','Beliebte Veranstaltungen'],
                ['453','view-events','global','View Events','View Events','عرض الفعاليات','Visualiser les événements','Veranstaltungen ansehen'],
                ['454','popular-packages','global','Popular Packages','Popular Packages','الباقات الشائعة','Forfaits populaires','Beliebte Pakete'],
                ['455','view-packages','global','View Packages','View Packages','عرض الباقات','Visualiser les forfaits','Pakete ansehen'],
                ['456','order-packages','global','Order Packages','Order Packages','طلب الباقات','Commander des forfaits','Pakete bestellen'],
                ['457','get-un-limited-access-and-earn-courses','global','Get un-limited access and earn courses','Get un-limited access and earn courses','احصل على وصول غير محدود للدروس','Obtenez un accès illimité et gagnez des cours','Unbegrenzten Zugang erhalten und Kurse verdienen'],
                ['458','set-up-your-daily-availability-time','global','Set up your daily availability time','Set up your daily availability time','اختر وقت تواجدك اليومي','Définissez votre temps de disponibilité quotidien','Legen Sie Ihre tägliche Verfügbarkeitszeit fest'],
                ['459','experience-tutors','global','Experience Tutors','Experience Tutors','خبرة المدرسين','Expérience des tuteurs','Erfahrung Tutoren'],
                ['460','tutor-nationalities','global','Tutors Nationalities','Tutors Nationalities','جنسيات المدرسين','Nationalités des tuteurs','Nationalitäten der Tutoren'],
                ['461','5-stars-tutor-reviews','global','5 Stars Tutor Reviews','5 Stars Tutor Reviews','مراجعات المدرسين ذو 5 نجوم','Commentaires pour les tuteurs 5 étoiles','5 Sterne Tutorenbewertungen'],
                ['462','tutors','global','Tutors','Tutors','المدرسين','Tuteurs','Tutoren'],
                ['463','search-by-tutor-name','global','Search By Tutor Name','Search By Tutor Name','البحث عن طريق اسم المعلم','Recherche par nom de tuteur','Suche nach Tutornamen'],
                ['464','users','global','Users','Users','المستخدمين','utilisateur','Benutzer'],
                ['465','our-courses','global','our courses','our courses','دوراتنا','nos cours','unsere Kurse'],
                ['466','i-want-to-learn','global','What to learn?','What to learn?','ماذا نتعلم؟','Je vais apprendre quoi ?','Was soll ich lernen?'],
                ['467','languages ','languages ','Languages ','Languages','اللغات','langues','Sprachen'],
                ['468','any-time','global','Any Time','Any Time','في أي وقت','à tout moment','Jederzeit'],
                ['469','name-z-a','global','Name: A-Z','Name: A-Z','الاسم: أ-ي','Nom: A-Z','Name: A-Z'],
                ['470','rating-lowest','global','Rating: Lowest','Rating: Lowest','التقييم: الأدنى','évaluation: Minimale','Bewertungs: Minimalen'],
                ['471','rating-highest','global','Rating: Highest','Rating: Highest','التقييم: الأعلى','Évaluation: Plus élevés ','Bewertungs: Höchsten'],
                ['472','price-highest','global','Pricing: Highest','Pricing: Highest','السعر: الأعلى','Évaluation: Plus élevés ','Preisgestaltung: Höchsten'],
                ['473','price-lowest','global','Pricing: Lowest','Pricing: Lowest','السعر: الأدنى','évaluation: Minimale','Preisgestaltung: Minimalen']
            ];
            
            foreach($data as $item){
                $row = Label::find($item[0]);
                if($row){
                    //$row->name = $item[1];
                    //$row->save();
                    //Translation::whereIn('langid',[20,15])->where('labelid',$item[0])->delete();
                    $req = json_decode('{"name":"'.$item[1].'", "file":"'.$item[2].'", "title":"'.$item[3].'"}');
                    //$trans = json_decode('[{"langid":"1", "title":"'.$item[3].'"},{"langid":"2", "title":"'.$item[4].'"}]',true);
                    $trans = json_decode('[{"langid":"1", "title":"'.$item[4].'"},{"langid":"2", "title":"'.$item[5].'"},{"langid":"20", "title":"'.$item[6].'"},{"langid":"15", "title":"'.$item[7].'"}]',true);
                    //var_dump($trans);exit;
                    $req->trans = $trans;
                    //var_dump($req);exit;
                    $this->addLabelAndTranslations($req);
                }else{
                    $req = json_decode('{"name":"'.$item[1].'", "file":"'.$item[2].'", "title":"'.$item[3].'"}');
                    $trans = json_decode('[{"langid":"1", "title":"'.$item[4].'"},{"langid":"2", "title":"'.$item[5].'"},{"langid":"20", "title":"'.$item[6].'"},{"langid":"15", "title":"'.$item[7].'"}]',true);
                    $req->trans = $trans;
                    $this->addLabelAndTranslations($req);
                }
                
            }
    }
    
}