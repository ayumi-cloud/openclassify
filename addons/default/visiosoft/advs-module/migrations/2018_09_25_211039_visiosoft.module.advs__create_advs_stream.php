<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class VisiosoftModuleAdvsCreateAdvsStream extends Migration
{

    /**
     * The stream definition.
     *
     * @var array
     */
    protected $stream = [
        'slug' => 'advs',
         'title_column' => 'name',
         'translatable' => true,
         'trashable' => true,
         'searchable' => false,
         'sortable' => false,
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'name' => [
            'translatable' => true,
            'required' => true,
        ],
        'slug' => [
            'required' => true,
        ],
        'advs_desc' => [
            'translatable' => true,
        ],
        'cat1',
        'cat2',
        'cat3',
        'cat4',
        'cat5',
        'cat6',
        'cat7',
        'price' => [
            'required' => true
        ],
        'currency' => [
            'required' => true
        ],
        'foreign_currencies',
        'online_payment',
        'is_get_adv',
        'stock',
        'country' => [
            'required' => true
        ],
        'city' => [
            'required' => true
        ],
        'district',
        'neighborhood',
        'village',
        'map_Val',
        'files',
        'publish_at',
        'finish_at',
        'status',
        'popular_adv',
        'adv_day',
        'cf_json',
        'cover_photo',
        'count_show_phone',
        'count_show_ad'
    ];

}
