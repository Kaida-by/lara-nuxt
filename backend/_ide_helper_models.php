<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $salary
 * @property int $author_id
 * @property int $salary_id
 * @property int $entity_type_id
 * @property int $category_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSalaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 */
	class Announcement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $category_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\EntityStatus|null $entityStatus
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 */
	class Article extends \Eloquent implements \App\Http\Interfaces\EntityInterface {}
}

namespace App\Models{
/**
 * App\Models\CV
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $phone
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\EntityStatus|null $entityStatus
 * @method static \Illuminate\Database\Eloquent\Builder|CV newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CV newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CV query()
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CV whereUpdatedAt($value)
 */
	class CV extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $category_type_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCategoryTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryType
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryType query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryType whereTitle($value)
 */
	class CategoryType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $text
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $entity_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EntityStatus
 *
 * @property int $id
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|EntityStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EntityStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EntityStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|EntityStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityStatus whereId($value)
 */
	class EntityStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Image
 *
 * @property int $id
 * @property string $slug
 * @property string $original_name
 * @property string $uuid
 * @property string $src
 * @property int $is_main
 * @property int $order
 * @property int $is_local
 * @property int $entity_type_id
 * @property int $entity_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereIsLocal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUuid($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Organization
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $address
 * @property string $link
 * @property string $phone
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $status_id
 * @property-read \App\Models\User|null $user
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EntityStatus|null $entityStatus
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 */
	class Organization extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Phone
 *
 * @property int $id
 * @property string $phone
 * @property string $number
 * @property int $entity_type_id
 * @property int $entity_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Phone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereUpdatedAt($value)
 */
	class Phone extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PhotoFact
 *
 * @property int $id
 * @property string $image
 * @property string $description
 * @property string $address
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoFact whereUpdatedAt($value)
 */
	class PhotoFact extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PhotoGallery
 *
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotoGallery whereUpdatedAt($value)
 */
	class PhotoGallery extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Poster
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $date
 * @property string $price
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $images_count
 * @property-read \App\Models\EntityStatus|null $entityStatus
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Poster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster query()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereUpdatedAt($value)
 */
	class Poster extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Profile
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property int $user_id
 * @property int $entity_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StaticPage
 *
 * @property int $id
 * @property string $text-page
 * @property int $entity_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereTextPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereUpdatedAt($value)
 */
	class StaticPage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $role_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSocial[] $social
 * @property-read int|null $social_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject {}
}

namespace App\Models{
/**
 * App\Models\UserRole
 *
 * @property int $id
 * @property string $code
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 */
	class UserRole extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserSocial
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $social_id
 * @property string $service
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereUserId($value)
 */
	class UserSocial extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserStatus
 *
 * @property int $id
 * @property string $code
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereId($value)
 */
	class UserStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Vacancy
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $phone
 * @property int $author_id
 * @property int $entity_type_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\EntityStatus|null $entityStatus
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereSalaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacancy whereUpdatedAt($value)
 */
	class Vacancy extends \Eloquent {}
}

