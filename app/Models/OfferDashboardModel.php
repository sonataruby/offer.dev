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

    public function updateDashboard($auth_id, $arv){
        $this->where("auth_id",$auth_id);
        $data = $this->first();
        if($data){
            $this->update($arv);
        }else{
            $arv["auth_id"] = $auth_id;
            $this->insert($arv);
        }
        
    }
}