<?php

namespace jilsonasis\ImageResizer;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * A Laravel wrapper for DOMPDF
 *
 * @package laravel-dompdf
 * @author Barry vd. Heuvel
 */
class ImageResizer
{
    private $image = null;
    private $input_height;
    private $input_width;
    private $src;
    private $max_width;
    private $max_height;
    private $quality = 80;
    private $ext;
    private $output_width;
    private $output_height;


    public function src($src)
    {
        if (!is_file($src)) throw new FileNotFoundException($src);
        $info = new \SplFileInfo($src);
        $this->src = $src;
        $this->ext = strtoupper($info->getExtension());

        if(is_file($src) && ($this->ext == "JPG" OR $this->ext == "JPEG")) {
            $this->image = ImageCreateFromJPEG($src);
        } else if (is_file($src) && $this->ext == "PNG") {
            $this->image = ImageCreateFromPNG($src);
        } else {
            throw new FileNotFoundException($src);
        }

        $this->input_width = imagesx($this->image);
        $this->input_height = imagesy($this->image);

        return $this;
    }

    public function quality($quality)
    {
        $this->quality = (int) $quality;
        return $this;
    }

    public function maxWidth($width)
    {
        $this->max_width = (int) $width;
        return $this;
    }

    public function maxHeight($height)
    {
        $this->max_height = (int) $height;
        return $this;
    }

    public function save()
    {
//        $is_landscape = ($this->input_height < $this->input_width);
//        $is_square = ($this->input_height == $this->input_width);
        $is_width_exceeded = ($this->input_width > $this->max_width);
        $is_height_exceeded = ($this->input_height > $this->max_height);
        $is_set_to_landscape = ($this->max_width > $this->max_height);

        if ($is_width_exceeded || $is_height_exceeded) {
            if ($is_set_to_landscape) { // follow the height
                $this->output_height = $this->max_height;
                $this->output_width = $this->max_height * $this->input_width / $this->input_height;
            } else { // follow the width
                $this->output_width = $this->max_width;
                $this->output_height = $this->max_width * $this->input_height / $this->input_width;
            }
        } else {
            $this->output_width = $this->input_width;
            $this->output_height = $this->input_height;
        }

        $output = ImageCreateTrueColor($this->output_width, $this->output_height);
        imagealphablending($output, false);
        imagesavealpha($output, true);
        $transparent = imagecolorallocatealpha($output, 255, 255, 255, 127);
        imagefilledrectangle($output, 0, 0, $this->output_width, $this->output_height, $transparent);
        ImageCopyResampled($output, $this->image, 0, 0, 0, 0, $this->output_width, $this->output_height, $this->input_width, $this->input_height);

        // Save JPEG
        if($this->ext == "JPG" OR $this->ext == "JPEG") {
            imageJPEG($output, $this->src, $this->quality);
        } else if ($this->ext == "PNG") {
            imagePNG($output, $this->src);
        }

        @ImageDestroy($this->image);
        @ImageDestroy($output);
    }

}