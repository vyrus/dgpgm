<?
class MiniPicBilder{


function resize_image($max_width, $max_height, $source, $destination) {


         $w_h=GetImageSize($source);
         $width=$w_h[0];
         $height=$w_h[1];

         $x_ratio = $max_width / $width;
         $y_ratio = $max_height / $height;

         if ( ($width <= $max_width) && ($height <= $max_height) ) {
           $tn_width = $width;
           $tn_height = $height;
         }
         else if (($x_ratio * $height) < $max_height) {
           $tn_height = ceil($x_ratio * $height);
           $tn_width = $max_width;
         }
         else {
           $tn_width = ceil($y_ratio * $width);
           $tn_height = $max_height;
         }

      $width=$tn_width ;
      $height= $tn_height;


      $src = imagecreatefromjpeg($source);
      $img = imagecreatetruecolor($width, $height);
      imagecopyresampled($img, $src, 0, 0, 0, 0, $width, $height, imagesx($src),imagesy($src));
//    @unlink($destination);
      imageJPEG($img, $destination);
       imagedestroy($img);
       imagedestroy($src);

}



function upload_jpg($fname, $filename, $dir, $max_size='2307000')
{

 if (!empty($_FILES[$fname]) && is_uploaded_file($_FILES[$fname]["tmp_name"]))
    {

            $type=$_FILES[$fname]['type'];
            $allowed_types = array("image/jpeg","image/pjpeg" );
            $size=filesize($_FILES[$fname]['tmp_name']);
            if($max_size && $size>$max_size) return array('������ ����� ������ '.$max_size);

        if (empty($_FILES[$fname]["tmp_name"])){
            return array('������ �������� �����. ��� '.$_FILES[$fname]["error"]);
        }

        if ((in_array($type,$allowed_types))){
              $res = move_uploaded_file($_FILES[$fname]["tmp_name"], $dir.$filename );
            if ($res){
              return array();
            }else{
              return array('������ �������� �����');
            }
        }else{
           return array('�� ������ ��� �����. ���������:' .implode(',',$allowed_types));
       }
    }
    else
    {
      return array('���� �� ��� ��������');
    }
}

/*������� ��� �������� �����*/
function upload_zip($fname, $filename, $dir, $max_size='2307000')
{

 if (!empty($_FILES[$fname]) && is_uploaded_file($_FILES[$fname]["tmp_name"]))
    {

            $type=$_FILES[$fname]['type'];
            $allowed_types = array("application/zip", "application/x-zip", "application/octet-stream", "application/x-zip-compressed", "multipart/x-zip");
            $size=filesize($_FILES[$fname]['tmp_name']);
            if($max_size && $size>$max_size) return array('������ ����� ������ '.$max_size);

        if (empty($_FILES[$fname]["tmp_name"])){
            return array('������ �������� �����. ��� '.$_FILES[$fname]["error"]);
        }

        if ((in_array($type,$allowed_types))){
              $res = move_uploaded_file($_FILES[$fname]["tmp_name"], $dir.$filename );
            if ($res){
              return array();
            }else{
              return array('������ �������� �����');
            }
        }else{
           return array('�� ������ ��� �����. �������� ������ zip');
       }
    }
    else
    {
      return array('���� �� ��� ��������');
    }
}
/*������� ��� �������� �����*/

}
?>