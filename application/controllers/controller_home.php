<?php
class controller_home Extends te_controller
{
  public function index($args = null)
  {
    tank_engine::set_title("Home - index");

    //some data gathering for the default home page
    $data["url_root"] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://"
      . $_SERVER["HTTP_HOST"]
      . $_SERVER["REQUEST_URI"];
    $data["url_root"] = rtrim($data["url_root"],"/");
    $data["config_origin"] = TE_DOCUMENT_ROOT . "/framework/boot/0.boot.php";
    $data["config_destination"] = TE_DOCUMENT_ROOT . "/application/boot/0.boot.php";
    $data["htaccess_path"] = TE_DOCUMENT_ROOT . "./htaccess";


    return new te_layout_view("home",$data);
  }
}
