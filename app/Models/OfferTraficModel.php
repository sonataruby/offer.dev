<?php namespace App\Models;

use CodeIgniter\Model;

class OfferTraficModel extends Model
{
	protected $table = 'offer_clicktranfic';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['link','click_id','country','number','runnumber'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function createTranfic($arv=[]){
    	$this->insert($arv);
    }
}