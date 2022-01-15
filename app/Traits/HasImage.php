<?php

namespace App\Traits;


use Illuminate\Http\Request;

trait HasImage
{
    /**
     * @param $max_width
     * @param $max_height
     * @param $source_file
     * @param $dst_dir
     * @param int $quality
     * @return false
     */
    private static function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, int $quality = 80): bool
    {
        $image_size = getimagesize($source_file);
        $width = $image_size[0];
        $height = $image_size[1];
        $mime = $image_size['mime'];

        switch($mime){
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 5;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;

        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if($width_new > $width){
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        }else{
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);

        if($dst_img)
            imagedestroy($dst_img);
        if($src_img)
            imagedestroy($src_img);

        return true;
    }

    /**
     * @param Request $request
     * @param array $validData
     * @return array
     */
    public static function uploadImage(Request $request, array $validData, int $width, int $height): array
    {
        $file = $request->file('image');

        // create new random name
        $name = \Str::random(12) . '.' . $file->getClientOriginalExtension();

        $destinationPatch = '/images/' . now()->year . '/' . now()->month . '/' . now()->day . '/';

        // save image
        $file->move(public_path($destinationPatch), $name);

        // image src
        $src = public_path($destinationPatch) . $name;

        // thumbnail src
        $dest = public_path($destinationPatch) . $name;

        self::resize_crop_image($width, $height, $src, $dest);

        // Thumbnail relative patch
        $thumb = $destinationPatch . $name;

        $validData['image'] = $thumb;

        return $validData;
    }
}
