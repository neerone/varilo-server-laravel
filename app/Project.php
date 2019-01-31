<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ProjectFile;
use Illuminate\Support\Collection;
use Auth;
use Storage;
function getElementClassNames($elementClassIds, $classes) {

	$classesStringArray = array_map(function ($classId) use ($classes){
	    return $classes->get($classId)->name;
	}, $elementClassIds);


	return implode(" ", $classesStringArray);
}

function getElementAttrs($attributes) {
	if (!isset($attributes)) return "";

	$attrsStringArray = array_map(function ($attribute){
		if (!isset($attribute)) return "";
		$key = $attribute->key;
		$value = $attribute->value;
	    return "$key='$value'";
	}, $attributes);


	return implode(" ", $attrsStringArray);
}


function renderHTMLElement($element, $elements, $classes) {
	
	if (!$element || !isset($element->id) || !isset($element->tag) || !isset($element->kind)  || !isset($element->classes)) {
		return dd($element);
	}


	$id = $element->id;
	$tag = $element->tag;
	$kind = $element->kind;
	$content = $element->content;
	$classIds = $element->classes;
	$attributes = [];
	if (isset($element->attributes)) {
		$attributes = $element->attributes;
	}

	$elementClasses = getElementClassNames($classIds, $classes);

	$elementAttrs = getElementAttrs($attributes);

	switch ($kind) {
	    case "image":
	        return "<img class='$elementClasses' $elementAttrs src='$content'/>";
	    case "input":
       		return "<$tag class='$elementClasses' $elementAttrs />";
	    case "inline":
	    case "button":
       		return "<$tag class='$elementClasses' $elementAttrs >$content</$tag>";
	    case "container":
    		$renderedChilds = renderElementChilds($id, $elements, $classes);
       		return "<$tag class='$elementClasses' $elementAttrs >$renderedChilds</$tag>";
	}
}

function renderElementChilds($parentId, $elements, $classes) {

	$parentElement = $elements->get($parentId);
	$childIds = collect($parentElement->content);

	$childs = $childIds->map(function($childId) use ($elements) {
		return $elements->get($childId);
	});

/*	$childs = $elements->filter(function ($child, $key) use ($parentId) {

	    return $child->parentId == $parentId;
	});*/
	$output = "";
	$childs->each(function ($element, $key) use (&$output, $elements, $classes) {
	    $output .= renderHTMLElement($element, $elements, $classes);
	});
	return $output;
}


function renderIncludes($includesArray, $includesText, $randomver) {
	$output = "";
	$includesStringArray = collect($includesArray)->map(function ($include) use (&$output, $randomver) {
		if (strpos($include, '.js') !== false) {
		    return "<script src='assets/$include?ver={$randomver}'></script>";
		}
		if (strpos($include, '.css') !== false) {
		    return "<link rel='stylesheet' type='text/css' href='assets/$include?ver=$randomver'>";
		}
	})->toArray();
	$output .= implode("", $includesStringArray);
	$output .= $includesText;
	return $output;
}


function bakeHTML($elements, $pageSettings, $classes) {

	$randomver = uniqid();

	$headerIncludes = renderIncludes($pageSettings["headerIncludes"], $pageSettings["headerIncludesText"], $randomver);
	$footerIncludes = renderIncludes($pageSettings["footerIncludes"], $pageSettings["footerIncludesText"], $randomver);
	$elements = renderElementChilds("0", $elements, $classes);
	$title = $pageSettings["title"];

	$initialHTMLTemplate  = "<!DOCTYPE html>";
	$initialHTMLTemplate .= "<html>";
	$initialHTMLTemplate .= 	"<head>";
	$initialHTMLTemplate .= 		"<style>* {padding: 0px;margin: 0px;box-sizing: border-box;border: 0px;outline: none; }</style>"; 
	$initialHTMLTemplate .= 		'<meta name="viewport" content="width=device-width,initial-scale=1">';
	$initialHTMLTemplate .= 		"<title>$title</title>";
	$initialHTMLTemplate .= 		"<meta charset='utf-8'>$headerIncludes"; 
	$initialHTMLTemplate .= 		"<link rel='stylesheet' type='text/css' href='generated_styles.css?ver=$randomver'>";
	$initialHTMLTemplate .= 	"</head>";
	$initialHTMLTemplate .= 	"<body class='body'>$elements $footerIncludes</body>";
	$initialHTMLTemplate .= "</html>";


	return $initialHTMLTemplate;
}

/* CSS ================ */

function preprocessPropValue($value) {
	if (!isset($value)) return "";
	return $value;
}


function propertiesToString($props) {
  $classPropsArray = $props->map(function($prop) {
  	$k = $prop->key;
  	$v = preprocessPropValue($prop->value);
    return "$k:$v;";
  })->toArray();
  return implode("", $classPropsArray);
}


function renderClassProps($className, $props) {
	$stringifiedProps = propertiesToString($props);
	return ".$className { $stringifiedProps }";
}


function renderStyleInner($className, $currentClass, $media, $mediaList) {
	$props = renderClassProps($className, collect($currentClass->props));


	$pseudosString = "";
	if (isset($currentClass->pseudos)) {
		$pseudos = collect($currentClass->pseudos);
		$pseudosStringArray = $pseudos->map(function($pseudoObject, $pseudoIndex) use ($className, $media, $mediaList) {
			$pseudoName = $pseudoObject->name;
			return renderStyleInner("$className:$pseudoName", $pseudoObject, $media, $mediaList);
		})->toArray();
		$pseudosString = implode("", $pseudosStringArray);
	}
	

	$mediaString = "";
	if (isset($currentClass->media)) {
		$mediaObjectsIds = collect($currentClass->media);
		$mediaObjectsStringArray = $mediaObjectsIds->map(function($mediaId) use ($media, $className, $mediaList) {

			$mediaWidth = 0;

			$mediaList->each(function ($mediaListObject, $mw) use ($mediaId, &$mediaWidth) {
				if (collect($mediaListObject->mediaLinks)->contains($mediaId)) $mediaWidth = $mw;
			});

			$currentMediaProps = collect($media->get($mediaId)->props);

			$mediaOverrides = renderClassProps($className, $currentMediaProps);

			return "@media only screen and (max-width: {$mediaWidth}px) { $mediaOverrides }";
		})->toArray();
		$mediaString = implode("", $mediaObjectsStringArray);
	}

	return $props . " " . $pseudosString . " " . $mediaString;
}



function bakeCSS($classList, $classes, $media, $mediaList) {
	$total = $classList->reduce(function ($result, $classId) use ($classes, $media, $mediaList) {
		$currentClass = $classes->get($classId);
		$className = $currentClass->name;

		$mainClass = renderStyleInner($className, $currentClass, $media, $mediaList);
		$result[] = $mainClass;
	    return $result;
	}, []);
	return implode("", $total);
}


class Project extends Model
{
    public function assets() {
    	return ProjectFile::where("project_id", $this->id)->where("type", "asset")->get();
    }
    public function files() {
    	return ProjectFile::where("project_id", $this->id)->where("type", '<>', "asset")->get();
    }







    public function bake() {
    	$projectState = json_decode($this->project_state);
    	$elements = collect($projectState->elements);
    	$pageSettings = $projectState->pageSettings;


    	$classes = collect($projectState->classes);
    	$classList = collect($projectState->classList);

    	$media = collect($projectState->media);
    	$mediaList = collect($projectState->mediaList);


    	$html = bakeHTML($elements, collect($pageSettings), $classes);
    	$css = bakeCSS($classList, $classes, $media, $mediaList);
    	
        $userId = Auth::user()->id;
    	Storage::disk('local')->put('public/projects/'.$userId.'/'.$this->id.'/generated_styles.css', $css);
    	Storage::disk('local')->put('public/projects/'.$userId.'/'.$this->id.'/index.html', $html);


    	return $html;
    }
}
