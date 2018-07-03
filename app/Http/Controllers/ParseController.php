<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ParseController extends Controller
{
    public function parse() {
    	$xml = XmlParser::load('http://productdata.zanox.com/exportservice/v1/rest/36486875C136412227.xml?ticket=5884B2735F89579174332B8A754C4BCF&productIndustryId=1&gZipCompress=null');
		$result = $xml->parse([
			'products' => ['uses' => 'product[price,name,longDescription>description,color,size,largeImage>image,deepLink>link,::zupid>code]'],
		]);

		foreach($result["products"] as $product){
			DB::insert("INSERT INTO `products` (`price`, `name`, `description`, `color`, `size`, `image`, `link`, `code`) 
						VALUES(?, ?, ?, ?, ?, ?, ?, ?)", [$product["price"], $product["name"], $product["description"], $product["color"], $product["size"], $product["image"], $product["link"], $product["code"]]);
		}
		echo '<h1>Pasing compleat<h1>';
    }
}
