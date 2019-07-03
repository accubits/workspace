<?php
namespace Modules\Common\Utilities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Utilities
{
    public static function getUniqueId()
    {
        return uniqid(rand(), true);
    }

    public static function createDateTime($dateTime)
    {
        $dateTime = Carbon::createFromFormat("d/m/Y H:i:s", $dateTime, config('app.userTimezone'));
        $dateTime->setTimezone('UTC');
        return $dateTime;
    }

    public static function createDateTimeToUTC($format = 'd/m/Y H:i:s', $dateTime)
    {
        $format = ($format) ? $format : 'd/m/Y H:i:s';
        $dateTime = Carbon::createFromFormat($format, $dateTime, config('app.userTimezone'));
        $dateTime->setTimezone('UTC');
        return $dateTime;
    }

    public static function createDateTimeFromUtc($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp);
    }


    public static function sortDataCollection(Collection $collection)
    {
        if (request()->has('sortBy')) {
            $attribute = request()->sortBy;
            if (Utilities::sortOrderCollection() == 'asc')
                $collection = $collection->sortBy->{$attribute};
            else
                $collection = $collection->sortByDesc->{$attribute};
        }
        return $collection->values()->all();
    }

    public static function sortOrderCollection()
    {
        $sortOrder = 'asc';
        if (request()->has('sortOrder')) {
            Validator::validate(request()->all(), ['sortOrder' => Rule::in(['asc', 'desc'])]);
            $sortOrder = request()->sortOrder;
        }
        return $sortOrder;
    }

    /**
     * sorting with eloquent
     * @param $collection
     * @return mixed
     */
    public static function sort($collection)
    {
        if (request()->sortBy && request()->sortOrder) {
            $collection = $collection->orderBy(request()->sortBy, request()->sortOrder);
        } else if (request()->sortOrder) {
            $collection = $collection->orderBy('id', request()->sortOrder);
        } else if (request()->sortBy) {
            $collection = $collection->orderBy(request()->sortBy, 'desc');
        }
        return $collection;
    }

    public static function getParams($limit = 10)
    {
        $page    = 1;
        $perPage = $limit;

        if (request()->has('page')) {
            $page = (int)request()->page;
        }

        if (request()->has('perPage')) {
            $perPage = (int)request()->perPage;
        }

        $offset    = ($page * $perPage) - $perPage;
        return array('page' => $page, 'perPage' => $perPage, 'offset' => $offset);
    }

    public static function paginate($items, $perPage = 10, $page = null, $options = [], $count)
    {
        $page = ($page)?  Paginator::resolveCurrentPage() : 1;
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $items instanceof Collection ? $items : Collection::make($items);
        //$items = Utilities::sortDataCollection($items);
        $paginated = new LengthAwarePaginator($items, $count, $perPage, $page, $options);
        return $paginated->appends(request()->all());
    }

    public static function throwError($data, $code) : array
    {
        $content['error'] = $data;
        $content['code']  = $code;
        return $content;
    }

    public static function unsetResponseData($data)
    {
        unset($data['first_page_url']);
        unset($data['last_page_url']);
        unset($data['next_page_url']);
        unset($data['prev_page_url']);
        unset($data['path']);
        unset($data['data']);
        return $data;
    }

    public static function createFilePath($orgSlug, $folderName, $folderSlug)
    {
        return "{$orgSlug}/{$folderName}/{$folderSlug}/";
    }

}
