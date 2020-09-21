<?php
class model_user Extends wp_model
{
  protected static $table_name = 'monopoly_users';

  public function get_default_values_array()
  {
    return array(
      "wp_number" => 0,
      "display_name" => "",
      "mail" => "",
      "username" => "",
      "password_hash" => "",
      "admin" => 0
    );
  }

  public function validate()
  {
    $errors = [];
    if(!isset($this->display_name) || $this->display_name == "")
    {
      $errors[] = "display_name cannot be empty";
    }
    if($this->wp_number == 0)
    {
      //FIXME: deze validatie is echt niet voldoende
      //NON WP user
      if(!isset($this->password) || strlen($this->password) < 8)
      {
        $errors[] = "Password should be at least 8 characters long";
      }
      else
      {
        $this->password_hash = password_hash($this->password, PASSWORD_DEFAULT);
      }
      if(static::username_exists($this->username))
      {
        $errors[] = "The username ".$this->username." is already in use.";
      }
    }
    return $errors;
  }

  //validate a login
  public function password_verify($password)
  {
    return password_verify($password, $this->password_hash);
  }

  //get whether user is associated with a wordpress account
  public function is_wordpress_user()
  {
    return $this->wp_number != 0;
  }

  //get the current position of this user
  public function get_current_position()
  {
    if($street_id = static::get_var("SELECT street_id FROM monopoly_turns WHERE user_id = ".$this->id." ORDER BY time DESC LIMIT 1"))
    {
      return new model_street($street_id);
    }
    else
    {
      return false;
    }
  }
  public function get_current_turn()
  {
    $id = static::get_var("SELECT id FROM monopoly_turns WHERE user_id = ".$this->id." ORDER BY time DESC LIMIT 1");
    return new model_turn($id);
  }
  public function get_turn_number()
  {
    return static::get_var("SELECT count(*) FROM monopoly_turns WHERE user_id = ".$this->id);
  }

  //get current liquid assets (ECTS)
  public function get_liquid_assets()
  {
    //staring amount
    $money = 45;

    //transactions
    $money += static::get_var("SELECT sum(delta_money) FROM monopoly_money_transfers WHERE user_id =".$this->id);
    if(!$money)
    {
      $money = 0;
    }
    return round($money, 3);
  }
  public function get_total_assets()
  {
    $onroerend = static::get_var("SELECT sum(purchase_cost + improvement_cost * improvement_level) FROM monopoly_streets WHERE owner_id = ".$this->id);
    return round($onroerend + $this->get_liquid_assets(),3);
  }
  public function get_info_locations_block($linkobject)
  {
    $str = "";
    $city_ids = static::get_results("SELECT city_id FROM monopoly_streets WHERE owner_id = ".$this->id . " GROUP BY city_id");
    foreach ($city_ids as $value) {
      $streets = model_street::get_all_by(array("city_id" => $value->city_id));
      $str .= $linkobject->link("street/list/_".$streets[0]->city->id,$streets[0]->city->name,"page") . "<br />";
      foreach ($streets as $street) {
        $str .= '<span';
        if($street->owner->id != Response::get_user()->id)
        {
          //we don't own this
          $str .= ' style="opacity: 0.3;"';
        }
        $str .= '><i class="fa fa-map-marker" style="color: '.$street->city->color.';"></i> ';
        $str .= $linkobject->link("street/view/_".$street->id,$street->name,"page")."</span><br />";
      }
      $str .= "<hr />";
    }
    return $str;
  }

  public function clear_if_inactive()
  {
    //check if inactive
    if($turn = $this->get_current_turn())
    {
      $turntime = new DateTime($turn->time);
      $diff = $turntime->diff(new DateTime());
      if($diff->h + $diff->days*24 > 36)
      {
        //inactive for more than 36 hours, remove all streets
        model_street::update(array("improvement_level" => 0,"owner_id" => 0),array("owner_id" => $this->id));
        $msg = new model_message();
        $msg->text = "You have lost all your streets because you have been inactive for more than 36 hours.";
        $msg->target = $this;
        $msg->sender = new model_user(0);
        $msg->save();
      }
    }
  }

  //check if a username exists
  public static function username_exists($username)
  {
    //FIXME this isn't prepared / secured against injection...
    if(static::get_var("SELECT username FROM ".static::$table_name." WHERE username = '".$username."'"))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  //get by username
  public static function get_by_username($username)
  {
    if($user_row = static::get_row("SELECT * FROM ".static::$table_name." WHERE username = '".$username."'"))
    {
      return new model_user($user_row);
    }
    else
    {
      return false;
    }
  }

  //get user by the WP id. Used to login users that login via wordpress
  public static function get_by_wp_id($id)
  {
    if($user_row = static::get_row("SELECT * FROM ".static::$table_name." WHERE wp_number = ".$id, ARRAY_A))
    {
      return new model_user($user_row);
    }
    else
    {
      return false;
    }
  }
}
?>
