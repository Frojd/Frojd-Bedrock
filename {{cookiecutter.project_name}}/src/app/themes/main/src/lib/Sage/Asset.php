<?php

namespace Roots\Sage;

use Roots\Sage\Assets\ManifestInterface;

/**
 * Class Template
 * @package Roots\Sage
 * @author QWp6t
 */
class Asset {
    public static $dist = '/frontend/dist';
    public static $dist_no_manifest = '/frontend';

    /** @var ManifestInterface Currently used manifest */
    protected $manifest;

    protected $asset;

    public function __construct($file, ManifestInterface $manifest = null) {
        $this->manifest = $manifest;
        $this->asset = $file;
    }

    public function __toString() {
        return $this->getUri();
    }

    public function getUri() {
        return $this->manifest && $this->manifest->get($this->asset)
            ? get_stylesheet_directory_uri() . self::$dist . "/" . $this->manifest->get($this->asset)
            : get_stylesheet_directory_uri() . self::$dist_no_manifest . '/' . $this->asset;
    }
}
