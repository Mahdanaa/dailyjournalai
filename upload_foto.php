<?php
function upload_foto($File){
    $uploadOk = 1;
    $hasil = array();
    $message = '';

    $FileName = $File['name'];
    $TmpLocation = $File['tmp_name'];
    $FileSize = $File['size'];

    $FileExt = explode('.', $FileName);
    $FileExt = strtolower(end($FileExt));

    //Allowed files:
    $Allowed = array('jpg', 'png', 'gif', 'jpeg');

    if ($FileSize > 500000) {
        $message .= "Sorry, your file is too large, max 500KB. ";
        $uploadOk = 0;
    }

    if(!in_array($FileExt, $Allowed)){
        $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
        $uploadOk = 0;
    }

    // Cek keaslian gambar
    if ($uploadOk == 1) {
        $check = getimagesize($TmpLocation);
        if($check === false) {
            $message .= "Fake Image detected! File is not an actual image. ";
            $uploadOk = 0;
        } else {
            $mime = $check['mime'];

            if ($FileExt == 'jpg' || $FileExt == 'jpeg') {
                if ($mime != 'image/jpeg') $uploadOk = 0;
            } elseif ($FileExt == 'png') {
                if ($mime != 'image/png') $uploadOk = 0;
            } elseif ($FileExt == 'gif') {
                if ($mime != 'image/gif') $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $message .= "File extension does not match its content! ";
            }
        }
    }

    if ($uploadOk == 0) {
        $message .= "Sorry, your file was not uploaded. ";
        $hasil['status'] = false;
    }else{
        $NewName = date("YmdHis"). '.' . $FileExt;
        $UploadDestination = "img/". $NewName;

        if (move_uploaded_file($TmpLocation, $UploadDestination)) {
            $message .= $NewName;
            $hasil['status'] = true;
        }else{
            $message .= "Sorry, there was an error uploading your file. ";
            $hasil['status'] = false;
        }
    }

    $hasil['message'] = $message;
    return $hasil;
}
?>
