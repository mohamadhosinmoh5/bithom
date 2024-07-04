<?php

namespace App\Classes\Base;

interface BankPortal {

    public function Request(Array $data);

    public function Verify(Array $data);


}
