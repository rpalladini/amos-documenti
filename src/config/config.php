<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

return [
    'params' => [
        'img-default' => '/img/defaultProfilo.png',
        //active the search
        'searchParams' => [
            'documenti' => [
                'enable' => true,
            ]
        ],
        //active the order
        'orderParams' => [
            'documenti' => [
                'enable' => true,
                'fields' => [
                    'titolo',
                    'data_pubblicazione'
                ],
                'default_field' => 'data_pubblicazione',
                'order_type' => SORT_DESC
            ]
        ],
    ]
];
