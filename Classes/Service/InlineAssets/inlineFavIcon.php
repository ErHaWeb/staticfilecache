<?php

declare(strict_types=1);

namespace SFC\Staticfilecache\Service\InlineAssets;

/**
 * Class inlineFavIcon.
 *
 * @author Marcus Förster ; https://github.com/xerc
 */
class inlineFavIcon extends AbstractInlineAssets
{
    /**
     * FavIcon extensions.
     */
    private $fileExtensions = ['svg', 'ico', 'png'];

    /**
     * Check if the class can handle the file extension.
     */
    public function canHandleExtension(string $fileExtension): bool
    {
        return in_array($fileExtension, $this->$fileExtensions, true);
    }

    public function replaceInline(string $content): string
    {
        if(false === preg_match('/<link.+?rel=(").*?icon.*?\1\shref=\1(?<src>.+?\.(?<ext>'.implode('|',$fileExtensions).')))\1[^>]*>/', $content, $match)) {
            return $content;
        }

        $path = $this->streamlineFilePaths((array) $match['src'])[0];
        if(!file_exists($path)) {// CHECK @ streamlineFilePaths ?!
            return $content;
        }

        $file = file_get_contents($path);
        if(empty($match)) {// CHECK ; needet?!
            return $content;
        }

        return str_replace($match['src'],$this->parseAsset($match),$content);
    }
}
