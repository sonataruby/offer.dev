<?php namespace App\Models;

use CodeIgniter\Model;


class ChatModel extends Model
{
	protected $table = 'chat';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['auth_id', 'username','messages'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $offer;
    
    public function getMessages(){
        $this->orderBy("id","DESC");
        return $this->findAll(20);
    }

    public function setMessages($auth_id, $username, $msg='')
    {
        $this->insert(["auth_id" => $auth_id, "username" => $username, "messages" => $msg]);
    }
    
}