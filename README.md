## Synopsis

**PHP 5** image integration for laravel. makes automatically overwrites the image so you do not have to specify its new location.

## Code Example

First use the imageResizer class in your controller

`use jilsonasis\ImageResizer\Facade as ImageResizer;`

Then you can use method chaining.

`$image_path = 'your_image_path';`
`$image_ext = 'your_image_extension'; // can only process jpg and png.`

`ImageResizer::src($image_path, $image_ext)->maxHeight(640)->maxWidth(800)->quality(80)->save();`

## Installation

No installation yet. I will change this when it is available

## License

This laravel package is free. You can use and modify the code as you want without the author's permission