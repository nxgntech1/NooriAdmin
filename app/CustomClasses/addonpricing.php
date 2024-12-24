<?php

namespace App\CustomClasses;

class addonpricing
{
  public $id_user_app;
  public $model_id;
  public $id_conducteur;
  public $pricingid;
  public $AddOnPricing;
  public $hours;
  public $kms;
  public $add_on_label;
  public function __construct($id_user_app, $model_id, $id_conducteur,$pricingid,$AddOnPricing,$hours,$kms,$add_on_label)
    {
      $this->id_user_app = $id_user_app;
      $this->model_id = $model_id;
      $this->id_conducteur = $id_conducteur;
      $this->pricingid = $pricingid;
      $this->AddOnPricing = $AddOnPricing;
      $this->hours = $hours;
      $this->kms = $kms;
      $this->add_on_label =$add_on_label;
    }
}