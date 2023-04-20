<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Services;

use App\Models\Category;
use DOMDocument;
use DOMNode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RecursiveIteratorIterator;

class EntityHelper
{
    public static function getUrlMainImageFromDescription(string $description): string|static
    {
        if ($description) {
            $tags = self::getTagsFromDescription($description);

            return self::getMainImageFromTags($tags);
        }

        return '';
    }

    public static function getTagsFromDescription(string $description): array
    {
        $dom = new DOMDocument;
        $dom->loadHTML($description);

        $output = [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDOMIterator($dom),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach($iterator as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE && ($node->nodeName !== 'html' && $node->nodeName !== 'body')) {
                $output[] = array(
                    'name' => $node->nodeName,
                    'value' => trim(self::getInnerHTML($node), PHP_EOL));
            }
        }

        return $output;
    }

    public static function getInnerHTML(DOMNode $element): string
    {
        $innerHTML = '';
        $children  = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    public static function getMainImageFromTags(array $tags): string
    {
        $path = '';

        foreach ($tags as $tag) {
            if ($tag['value'] && is_int(strpos($tag['value'], '<img'))) {
                $path = UploadImagesService::getImageFileNameFromTag($tag['value']);
                break;
            }
        }

        return $path;
    }

    public static function getCategories(int $categoryId, string $relationTable): Collection
    {
        return DB::table('categories', 'cat')
            ->select(['cat.id', 'cat.title', 'cat.slug', DB::raw('count(category_id) as cat')])
            ->leftJoin($relationTable, 'cat.id', '=', 'category_id')
            ->where('category_type_id', $categoryId)
            ->groupBy(['category_id', 'cat.id', 'cat.title'])
            ->get();
    }

    public static function getCategoriesIdFromCategoryArray(array $categories): array
    {
        if (isset($categories[0]['id'])) {
            return array_reduce($categories, static function ($acc, $category) {
                $acc[] = $category['id'];

                return $acc;
            }, []);
        }
        $categoryIds = Category::whereIn('title', $categories)->get('id')->toArray();

        return array_reduce($categoryIds, static function ($acc, $id) {
            $acc[] = $id['id'];

            return $acc;
        }, []);
    }

    public static function getFinallyPhone(string $str): string
    {
        $phoneWithCode = str_replace([' ', '(', ')'], '', $str);

        return substr($phoneWithCode, 4);
    }
}
