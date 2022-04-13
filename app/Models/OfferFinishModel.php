<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\ClickModel;

class OfferFinishModel extends Model
{
	protected $table = 'offer_finish';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['auth_id', 'click_id', 'offer_id','ip','brower','useragent','version','platform','country','state','zip','permissions','status','cost'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

   

    public function getFinish(){
        $this->join("users_profile","users_profile.user_id=offer_finish.auth_id","left");
        $this->join("offer","offer.id=offer_finish.offer_id","left");
        return $this->orderBy("offer_finish.id","DESC")->get(30)->getResult();
    }

    public function getFinishByClick($click_id){
        $this->join("users_profile","users_profile.user_id=offer_finish.auth_id","left");
        $this->join("offer","offer.id=offer_finish.offer_id","left");
        $this->where("click_id",$click_id);
        return $this->first();
    }

    public function setFinish($arv){
        $click_id = $arv["click_id"];
        $db = db_connect();
        $read = $db->query("SELECT * FROM offer_finish WHERE click_id ='".$click_id."' LIMIT 1")->getRow();

        if(!$read) $this->insert($arv);
    }
}