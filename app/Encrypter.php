<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Encrypter extends Model
{


    /**
     * Encryption of Id
     * @param $id
     * @return string
     */
    public static function encrypt($id){
        $encryptedId =Crypt::encrypt($id);
        return $encryptedId;
    }

    /**
     * Decryption of id
     * @param $id
     * @return string
     */
    public static  function decrypt($id){
        $decryptedId = Crypt::decrypt($id);
        return $decryptedId;
    }
}
