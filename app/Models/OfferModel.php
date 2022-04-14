<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\ClickModel;
use App\Models\OfferFinishModel;
use App\Models\OfferDashboardModel;
use App\Models\OfferTraficModel;

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

    public function updateClick($id){
        $db = db_connect();
        $read = $db->query("select * from offer_lead where offer_id='".$id."' and lead_day='".date("Y-m-d",now())."'")->getRow();
        if($read){
            $db->query("update offer_lead SET click_number='".($read->click_number + 1)."' WHERE id='".$read->id."'");
        }else{
            $db->query("insert into offer_lead SET click_number='1', offer_id='".$id."', lead_day='".date("Y-m-d",now())."'");
        }
        
    }

    public function setFinish($click_id){
        $woker = new ClickModel;
        $readWoker = $woker->where("id",$click_id)->first();
        if(!$readWoker) return "error";
        //Update Offer
        $offer = $this->where("id",$readWoker->offer_id)->first();
        $arvOffer = [
            "lead" => $offer->lead + 1
        ];
        $this->update($woker->offer_id, $arvOffer);
        $finish = new OfferFinishModel;
        $arvFinish = (array)$readWoker;
        unset($arvFinish["id"]);
        //unset($arvFinish["created_at"]);
        unset($arvFinish["updated_at"]);
        unset($arvFinish["deleted_at"]);

        
        $arvFinish["cost"] = $offer->cost;
        $arvFinish["click_id"] = $click_id;
        $finish->setFinish($arvFinish);

        $dashboard = new OfferDashboardModel;
        $dashboard->updateDashboard($readWoker->auth_id, $offer->cost);
        
        $tranffic = new OfferTraficModel;
        $tranffic->createTranfic(["click_id" => $click_id, "number" => $offer->clicktranfic, "link" => $offer->link, 'country' => $offer->country]);

        $woker->delete($click_id);
        
    }

    public function getTranffic(){
        $tranffic = new OfferTraficModel;
        return $tranffic->findAll();
    }

    public function getTranfficInfo($id){
        $tranffic = new OfferTraficModel;
        return $tranffic->find($id);
    }

    public function updateTranfficInfo($id,$arv){
        $tranffic = new OfferTraficModel;
        return $tranffic->update($id,$arv);
    }

}