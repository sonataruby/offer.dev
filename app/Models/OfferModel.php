<?php namespace App\Models;

use CodeIgniter\Model;


class OfferModel extends Model
{
	protected $table = 'offer';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name','device','cost','link','maxlead','lead','status','click','permissions'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function updateClick($id, $click){
        $this->update($id,["click" => $click]);
    }


    public function getFinish(){
        return $this->table("offer_finish")->orderBy("id","DESC")->get(30)->getResult();
    }
}