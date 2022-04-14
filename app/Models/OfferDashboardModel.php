<?php namespace App\Models;

use CodeIgniter\Model;


class OfferDashboardModel extends Model
{
	protected $table = 'offer_dashboard';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['auth_id','total_money','total_click','total_lead','total_error'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function updateDashboard($auth_id, $cost){
        $db = db_connect();
        
        $data = $db->query("select * from offer_dashboard where auth_id='".$$auth_id."' limit 1")->getRow();
        if($data){
            
            $db->query("update offer_dashboard set total_money='"+($data->total_money + $cost)+"', total_click='".($data->total_click + 1)."', total_lead='".($data->total_lead + 1)."' where auth_id='".$$auth_id."'");
        }else{
            $arv["auth_id"] = $auth_id;
            $arv["total_money"] = $cost;
            $arv["total_click"] = 1;
            $arv["total_lead"] = 1;
            $this->insert($arv);
        }
        
    }
}