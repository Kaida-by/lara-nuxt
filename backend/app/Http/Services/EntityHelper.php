<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Services;

use App\Models\Category;
use DOMDocument;
use DOMNode;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RecursiveIteratorIterator;

class EntityHelper
{
    public const TYPE_ANNOUNCEMENT = 1;
    public const TYPE_ARTICLE = 2;
    public const TYPE_PROFILE = 3;
    public const TYPE_PHONES = 4;
    public const TYPE_PHOTO_FACT = 5;
    public const TYPE_ORGANIZATION = 6;
    public const TYPE_VACANCY = 7;
    public const TYPE_CV = 8;
    public const TYPE_POSTERS = 9;
    public const TYPE_PHOTO_GALLERY = 10;
    public const TYPE_STATIC = 11;

    public const ENTITY_STATUS_ACTIVE = 1;
    public const ENTITY_STATUS_UNDER_MODERATION = 2;

    public const IS_ANNOUNCEMENT_CATEGORIES = 1;
    public const IS_ARTICLE_CATEGORIES = 2;

    /**
     * @param string $description
     * @return string|static
     */
    public static function getUrlMainImageFromDescription(string $description): string|static
    {
        if ($description) {
            $tags = self::getTagsFromDescription($description);

            return self::getMainImageFromTags($tags);
        }

        return '';
    }

    /**
     * @param string $description
     * @return array
     */
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

    /**
     * @param DOMNode $element
     * @return string
     */
    public static function getInnerHTML(DOMNode $element): string
    {
        $innerHTML = '';
        $children  = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    /**
     * @param array $tags
     * @return string
     */
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

    /**
     * @param int $categoryId
     * @return Collection
     */
    public static function getCategories(int $categoryId, string $relationTable): Collection
    {
        return DB::table('categories', 'cat')
            ->select(['cat.id', 'cat.title', 'cat.slug', DB::raw('count(category_id) as cat')])
            ->leftJoin($relationTable, 'cat.id', '=', 'category_id')
            ->where('category_type_id', $categoryId)
            ->groupBy(['category_id', 'cat.id', 'cat.title'])
            ->get();
    }

    /**
     * @param array $categories
     * @return array
     */
    public static function getCategoriesIdFromCategoryArray(array $categories): array
    {
        $categoryIds = Category::whereIn('title', $categories)->get('id')->toArray();

        return array_reduce($categoryIds, static function ($acc, $id) {
            $acc[] = $id['id'];

            return $acc;
        }, []);
    }
}
