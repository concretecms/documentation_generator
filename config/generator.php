<?php

return array(
    "generators" => array(
        "asset_list" => "\\Concrete\\DocumentationGenerator\\Generator\\Asset\\AssetListGenerator",
        "service_provider" => "\\Concrete\\DocumentationGenerator\\Generator\\ServiceProvider\\ServiceProviderListGenerator",
        "block_type" => "\\Concrete\\DocumentationGenerator\\Generator\\BlockType\\BlockTypeListGenerator",
        "config_group" => "\\Concrete\\DocumentationGenerator\\Generator\\ConfigGroup\\ConfigGroupListGenerator",
        "vendor_javascript" => "\\Concrete\\DocumentationGenerator\\Generator\\Asset\\VendorJavascriptAssetListGenerator",
        "vendor_css" => "\\Concrete\\DocumentationGenerator\\Generator\\Asset\\VendorCssAssetListGenerator",
        "vendor_libraries" => "\\Concrete\\DocumentationGenerator\\Generator\\Vendor\\VendorLibraryListGenerator"
    )
);
