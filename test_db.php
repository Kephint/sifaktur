<?php
$c = new mysqli('localhost', 'root', '', 'faktur');
$res = $c->query("ALTER TABLE faktur ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
if ($res) echo "Success";
else echo $c->error;
