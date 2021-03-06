<?php namespace Clake\UserExtended\Traits;

use Exception;
Use Illuminate\Database\Query\Builder;

/**
 * User Extended by Shawn Clake
 * Class Searchable
 * User Extended is licensed under the MIT license.
 *
 * @author Shawn Clake <shawn.clake@gmail.com>
 * @link https://github.com/ShawnClake/UserExtended
 *
 * @license https://github.com/ShawnClake/UserExtended/blob/master/LICENSE MIT
 * @package Clake\UserExtended\Traits
 */
trait Searchable
{

    /**
     * Loops through all the different fields to search by
     * @param $phrase
     * @return mixed
     */
    public function search($phrase)
    {
        $searchable = $this->getSearchableAttributes();

        $results = [];

        foreach($searchable as $field)
        {
            $set = self::searchUserByAttribute($field, $phrase);
            foreach($set as $result)
            {
                $results[$result->id] = $result;
            }
        }

        return $results;
    }

    /**
     * Preforms the actual search
     * @param $field
     * @param $phrase
     * @return mixed
     */
    protected static function searchUserByAttribute($field, $phrase)
    {
        return self::where($field, 'like', '%' . $phrase . '%')->where('status', '!=', 'unavailable')->get();

    }

    /**
     * Called by the system on runtime, Binds an event to the model to adjust timezones
     * @throws Exception
     */
    public static function bootSearchable()
    {
        if (!property_exists(get_called_class(), 'searchable')) {
            throw new Exception(sprintf(
                'You must define a $searchable property in %s to use the Searchable trait.', get_called_class()
            ));
        }
    }

    /**
     * Returns a collection of fields that will be encrypted.
     * @return array
     */
    public function getSearchableAttributes()
    {
        return $this->searchable;
    }

}