<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usermessage extends Model
{
    use HasFactory;

    // protected $fillable = ['message', 'auther_id', 'mood'];
    
    public $table = "messageboard.usermessage";

    // ----------------------------------------------------------------------
    // 
    // ----------------------------------------------------------------------
    public static function getMessage($find)
    {    
        $messages = Usermessage::select('u.id', 'm.nickname', 'u.message', 'u.auther_id', 'm.member_id', 'u.mood', 'e.id AS emotion_id', 'e.mood')
                                ->from('usermessage AS u')
                                ->leftJoin('member AS m', 'u.auther_id', '=', 'm.member_id')
                                ->leftJoin('emotion AS e', 'u.mood', '=', 'e.id')
                                ->orderBy('u.id', 'desc');
        if($find != "")
        {
            $messages->where(function ($query) use ($find) {
            $query->where('u.message', 'LIKE', '%'.$find.'%')
                ->orWhere('m.nickname', 'LIKE', '%'.$find.'%');
                });
        }
        return $messages->paginate(10)->withQueryString();
        // TODO 複檢：get_message/get_all_message合而為一。
    }

    // ----------------------------------------------------------------------
    // 
    // ----------------------------------------------------------------------
    public static function checkMessageId($member_id, $message_id){
        // TODO count的寫法可以再更好一點
        $match_count = Usermessage::select('u.id', 'u.message', 'u.auther_id', 'm.member_id')
                                    ->from('usermessage AS u')
                                    ->leftJoin('member AS m', 'u.auther_id', '=', 'm.member_id')
                                    ->where('u.id', $message_id)
                                    ->where('u.auther_id', $member_id)
                                    ->count();
        $is_match = ($match_count > 0) ? true : false;
        // TODO 複檢：回傳true/false
        return $is_match;
    }






























































//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version1()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status        = $this->form_status;

//         // 檢查起訖日期是否和現有資料有重疊
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//                 $query->where(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '<=', $form_starting_date)
//                         ->where('form_end_date', '>=', $form_end_date);
//                 })->orWhere(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '>=', $form_starting_date)
//                         ->where('form_end_date', '<=', $form_end_date);
//                 })->orWhere(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '>', $form_starting_date)
//                         ->Where('form_end_date', '>', $form_end_date);
//                 })->orWhere(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '<', $form_starting_date)
//                         ->where('form_end_date', '<', $form_end_date);
//                 });
//         })->get();

//         // 判斷重複性資料並回傳相關訊息給使用者
//         if ($data->count() > 0) {
//             if ($form_status == "create" || ($form_status == "edit" && $data[0]->id != $param_id)) {
//                 if ($data[0]->stdorg_category_id == $stdorg_category_id) {
//                     $this->customMessages["stdorg_category_id.repeat"] = "已存在申請單類型名稱";
//                 }

//                 if ($data[0]->stdorg_type_id == $stdorg_type_id) {
//                     $this->customMessages["stdorg_type_id.repeat"] = "已存在組織類型名稱";
//                 }

//                 if (
//                     $data[0]->form_starting_date <= $form_starting_date && $data[0]->form_end_date >= $form_starting_date ||
//                     $data[0]->form_starting_date <= $form_end_date && $data[0]->form_end_date >= $form_end_date ||
//                     $data[0]->form_starting_date >= $form_starting_date && $data[0]->form_end_date <= $form_end_date
//                 ) {
//                     $this->customMessages["formhours_date.repeat"] = "該時間區間已有申請單在申請中了";
//                 }
//             }
//         }
//     }




//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version2()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status        = $this->form_status;

//         // 檢查起訖日期是否和現有資料有重疊
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '<=', $form_starting_date)
//                 ->where('form_end_date', '>=', $form_end_date);
//         })->orWhere(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '>=', $form_starting_date)
//                 ->where('form_end_date', '<=', $form_end_date);
//         })->orWhere(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '>', $form_starting_date)
//                 ->Where('form_end_date', '>', $form_end_date);
//         })->orWhere(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '<', $form_starting_date)
//                 ->where('form_end_date', '<', $form_end_date);
//         })->get();



//         // 判斷重複性資料並回傳相關訊息給使用者
//         if ($data->count() > 0) {
//             if ($form_status == "create" || ($form_status == "edit" && $data[0]->id != $param_id)) {
//                 if ($data[0]->stdorg_category_id == $stdorg_category_id) {
//                     $this->customMessages["stdorg_category_id.repeat"] = "已存在申請單類型名稱";
//                 }

//                 if ($data[0]->stdorg_type_id == $stdorg_type_id) {
//                     $this->customMessages["stdorg_type_id.repeat"] = "已存在組織類型名稱";
//                 }

//                 if (
//                     $data[0]->form_starting_date <= $form_starting_date && $data[0]->form_end_date >= $form_starting_date ||
//                     $data[0]->form_starting_date <= $form_end_date && $data[0]->form_end_date >= $form_end_date ||
//                     $data[0]->form_starting_date >= $form_starting_date && $data[0]->form_end_date <= $form_end_date
//                 ) {
//                     $this->customMessages["formhours_date.repeat"] = "該時間區間已有申請單在申請中了";
//                 }
//             }
//         }
//     }



//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version3()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status        = $this->form_status;
        
//         // 檢查起訖日期是否和現有資料有重疊
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '<', $form_end_date)
//                 ->where('form_end_date', '>', $form_starting_date)
//                 ->where(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '!=', $form_end_date)
//                         ->orwhere("form_end_date", "!=", $form_starting_date);
//                     });
        
//         })->get();

        
// // gpt說我這樣改寫會有邏輯上的混淆
//     }





//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version3()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status   

//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '<', $form_end_date)
//                 ->where('form_end_date', '>', $form_starting_date)
//                 ->where(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '!=', $form_end_date)
//                         ->where('form_end_date', '!=', $form_starting_date);
//                 });
//         })->get();
//     }





//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version3()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status  

//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '<=', $form_end_date)
//                 ->where('form_end_date', '>=', $form_starting_date)
//                 ->where(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '!=', $form_end_date)
//                         ->orWhere("form_end_date", "!=", $form_starting_date);
//                 });
//         })->get();
//     }
    



//     // ----------------------------------------------------------------------
//     // 這是日期卡控的定版，我只要這樣檢查日期重疊性就好了
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version3()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status  

//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where('form_starting_date', '<=', $form_end_date)
//                 ->where('form_end_date', '>=', $form_starting_date);
//                 })
//         ->get();
//     }




// /*-------------------------------下面是要處理三條件唯一性判斷----------------------------------------------------------------------------- */








//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version1()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->param_id;
//         $form_status  

//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//             $query->where(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '<=', $form_end_date)
//                             ->where('form_end_date', '>=', $form_starting_date);
//                 });
//         })->where(function ($query) use ($stdorg_category_id, $stdorg_type_id) {
//             $query->where(function ($query) use ($stdorg_category_id, $stdorg_type_id) {
//                 $query->where('stdorg_category_id', $stdorg_category_id)
//                     ->whereIn('stdorg_type_id', ['學生社團', 'ideaNTU創意社群']);
//             })->orWhere(function ($query) use ($stdorg_category_id, $stdorg_type_id) {
//                 $query->where('stdorg_category_id', '重啟組織')
//                     ->whereIn('stdorg_type_id', ['學生社團', 'ideaNTU創意社群']);
//             });
//         })->get();
//     }




//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version2()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->paraid;
//         $form_status  

//         // 先篩選申請單類型+組織類型名稱+開放起訖日是否重複
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($stdorg_category_id) {
//             $query->where("stdorg_category_id", $stdorg_category_id)
//                     ->where("stdorg_type_id", $stdorg_type_id)
//                     ->where(function ($query) use ($form_starting_date, $form_end_date) {
//                     $query->where('form_starting_date', '<=', $form_end_date)
//                     ->where('form_end_date', '>=', $form_starting_date);
//                 })
//                 ;
//         });

//         if($data->count() > 0){
//             $this->customMessages["data.repeat"] = "申請日期已重疊";
//         }

//         // 再篩選組織類型名稱+開放起訖日是否重複
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($stdorg_category_id) {
//             $query->where("stdorg_type_id", $stdorg_type_id)
//                 ->where(function ($query) use ($form_starting_date, $form_end_date) {
//                 $query->where('form_starting_date', '<=', $form_end_date)
//                 ->where('form_end_date', '>=', $form_starting_date);
//                 })
//             ;
//         });

//         if($data->count() > 0){
//             $this->customMessages["data.repeat"] = "申請日期已重疊";
//         }

//         // 再篩選開放起訖日是否重複
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($stdorg_category_id) {
//             $query->where("stdorg_type_id", $stdorg_type_id)
//                 ->where(function ($query) use ($form_starting_date, $form_end_date) {
//                 $query->where('form_starting_date', '<=', $form_end_date)
//                 ->where('form_end_date', '>=', $form_starting_date);
//                 })
//             ;
//         });

//         if($data->count() > 0){
//             $this->customMessages["data.repeat"] = "申請日期已重疊";
//         }
//     }











//     // ----------------------------------------------------------------------
//     // 
//     // ----------------------------------------------------------------------
//     private function checkFormRepeat version3()
//     {
//         $formhours_semester = $this->formhours_semester;
//         $stdorg_category_id = $this->stdorg_category_id;
//         $stdorg_type_id     = $this->stdorg_type_id;
//         $form_starting_date = $this->form_starting_date;
//         $form_end_date      = $this->form_end_date;
//         $param_id           = $this->paraid;
//         $form_status  


//         // 先去找尋，是否有三條件都篩選申請單類型+組織類型名稱+開放起訖日是否重複
//         $data = ExtrStdorgFormhoursParam::where(function ($query) use ($stdorg_category_id) {
//             $query->where("formhours_semester")
//                 ->where("stdorg_category_id", $stdorg_category_id)
//                 ->where("stdorg_type_id", $stdorg_type_id)                ;
//         });

//         // 如果三條件相同，就幫我驗日期一樣否，   否則，就不用驗日期一樣否
//         // 這邊的條件判斷中，輯狀態下的判斷部分是表達不包刮自己之下，仍有相同資料時，就進入if判斷式裡面
//         if(($form_status == "create" && $data->count() > 0) || ($form_status == "edit" && $data->count() > 0 && $data[0]->id != $param_id)){
//             $data = ExtrStdorgFormhoursParam::where(function ($query) use ($form_starting_date, $form_end_date) {
//                 $query->where('form_starting_date', '<=', $form_end_date)
//                     ->where('form_end_date', '>=', $form_starting_date);
//                     })
//             ->get();
            
//             if($data->count() > 0){
//                 $this->customMessages["stdorg_category_id.repeat"] = "申請日期重疊";
//             }
//         }
//     }
}