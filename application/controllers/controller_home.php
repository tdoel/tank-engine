<?php
class controller_home Extends te_controller
{
  public function index($args = null)
  {
    tank_engine::set_title("Home - index");
    return new te_layout_view("home");
  }
}
