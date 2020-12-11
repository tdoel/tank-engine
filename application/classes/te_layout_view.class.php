<?php
//this class is the default view class, however the default layout is loaded
//around it by default.
class te_layout_view Extends te_view
{
  public function __construct($template, $data = [])
  {
    $this->template = "layout";
    $this->data = $data;
    $this->add_subview("page", new te_view($template, $data));

    //add main.css by default:
    tank_engine::add_css("main");
  }
}
