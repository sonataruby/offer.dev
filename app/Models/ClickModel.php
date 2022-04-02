<?php namespace App\Models;

use CodeIgniter\Model;


class ClickModel extends Model
{
	protected $table = 'offer_worker';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['auth_id', 'offer_id','ip','brower','useragent','version','platform','country','state','zip','permissions','status'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $offer;
    
    public function createClick($arv){
        $this->insert($arv);
        return $this->getInsertID();
    }
    
}