<?php
require_once('../../controllers/SampleDetailsController.php');

$sample = new SampleDetailsController();

echo $sample->store();