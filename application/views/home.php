<h1>Tank Engine home</h1>
<p>
  Choo choo! Welcome to the default homepage of Tank Engine. Below you can find
  a few things you may need to do to complete the setup.
</p>

<h2>1. Setting up 0.config.php</h2>
<ol>
  <li>Copy <b><?= $this->data["config_origin"] ?></b> to <b><?= $this->data["config_destination"]; ?></b></li>
  <li>Open <b><?= $this->data["config_destination"]; ?></b> with your favourite text-editor.</li>
  <li>Change line 14 to <b>define("TE_URL_ROOT","<?= $this->data["url_root"]; ?>");</b></li>
</ol>

<h2>2. Setting up .htaccess</h2>
To test if routing is functional, visit <?= $this->link("home/index","this link"); ?>. This should bring you to the page you are viewing right now. If you get a file-not-found error, follow these instructions:
<ol>
  <li>Open <b><?= $this->data["htaccess_path"]; ?></b> with your favourite text-editor.</li>
  <li>Depening on your situation, either</li>
  <ol>
    <li>Uncomment line 6; or</li>
    <li>Uncomment line 12 and alter it to your needs.</li>
  </ol>
  <li>Once you have saved the changes, you can verify .htaccess is configured correctly by visiting the link above.</li>
</ol>
