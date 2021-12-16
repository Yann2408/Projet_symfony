<?php

namespace App\Service;



class Slugify
{
    public function generate(string $input) : string
    {

      $generate = str_replace(' ','-', $input);
      $generate = str_replace('à','a', $input);
      $generate = str_replace('ç','c', $input);

    //   permet de sélectionner tout ce qui n'est pas une lettre, un chiffre.
    //   $generate = preg_replace('[^A-Za-z0-9]', '', $input);
    //   $generate = preg_replace('-+', '-', $input);

      return $generate;
    }

    



}