<?php

namespace Helper;

use Phalcon\Tag;
use Codeception\Module;
use Faker\Factory as Faker;
use Phosphorum\Model\Posts;

/**
 * Post Helper
 *
 * Here you can define custom actions
 * all public methods declared in helper class will be available in $I
 *
 * @package Helper
 */
class Post extends Module
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var \Codeception\Module\Phalcon
     */
    protected $phalcon;

    /**
     * Triggered after module is created and configuration is loaded
     */
    public function _initialize()
    {
        $this->faker = Faker::create();
        $this->phalcon = $this->getModule('Phalcon');
    }

    /**
     * Creates a random post and return its id
     *
     * @param array $attributes Model attributes [Optional]
     * @return int
     */
    public function havePost($attributes = null)
    {
        $attributes = $attributes ?: [];

        $title   = $this->faker->title;
        $default = [
            'title'         => $title,
            'slug'          => Tag::friendlyTitle($title),
            'content'       => $this->faker->text(),
            'users_id'      => $this->faker->numberBetween(),
            'categories_id' => $this->faker->numberBetween(),
        ];

        // do not generate slug manually
        if (array_key_exists('slug', $attributes) && $attributes['slug'] === false) {
            unset($attributes['slug'], $default['slug']);
        }

        return $this->phalcon->haveRecord(Posts::class, array_merge($default, $attributes));
    }
}
